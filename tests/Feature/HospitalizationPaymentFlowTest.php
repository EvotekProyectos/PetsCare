<?php

namespace Tests\Feature;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Episode;
use App\Models\Account;
use App\Models\Family;
use App\Models\HospitalizationStatus;
use App\Models\HospitalizationStatusHistory;
use App\Models\Reception;
use App\Models\ReceptionTransfer;
use App\Models\RedSheet;
use App\Models\User;
use App\Services\AccountStatementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Flujo nuevo Consulta -> Traslado -> Red Sheet -> Pago de consulta +
 * anticipo de servicios hospitalarios (ver RedSheetController::entry(),
 * AccountStatementService::hospitalizacionAnticipoRequerido()/
 * paymentMinimumRequired(), AdvancePaymentController::payHospitalizacion()).
 *
 * Algunas pruebas de aquí ejercitan cálculos que resuelven precios vía
 * OrdenVentaService contra Firebird (mismo comportamiento que el resto del
 * sistema, ver AccountStatementService::consultaTotal()/
 * hospitalizacionServiciosTotal()): requieren conectividad real a Firebird,
 * igual que cualquier otra prueba que toque AccountStatementService.
 */
class HospitalizationPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    // ARTICULO_ID real usado ya en esta sesión para verificar el flujo
    // contra Firebird (ver storage/app/test_new_flow.php).
    private const SERVICE_TYPE_ID = 1620;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->user = User::factory()->create();
        $this->user->assignRole('administrador');

        Artisan::call('db:seed', ['--class' => 'ReceptionTypeSeeder']);
        Artisan::call('db:seed', ['--class' => 'AttentionStatusSeeder']);
        Artisan::call('db:seed', ['--class' => 'GroomingStatusSeeder']);
        Artisan::call('db:seed', ['--class' => 'HospitalizationStatusSeeder']);
    }

    private function makeOriginReception(int $receptionTypeId): Reception
    {
        $episode = Episode::create([
            'pet_id' => \App\Models\Pet::factory()->create()->id,
            'status' => Episode::STATUS_OPEN,
            'opened_at' => now(),
        ]);

        Account::create([
            'episode_id' => $episode->id,
            'status' => Account::STATUS_OPEN,
        ]);

        return Reception::factory()->create([
            'reception_type_id' => $receptionTypeId,
            'family_id' => Family::factory()->create()->id,
            'episode_id' => $episode->id,
        ]);
    }

    private function setHospitalizationStatus(Reception $reception, string $statusName): void
    {
        HospitalizationStatusHistory::create([
            'reception_id' => $reception->id,
            'hospitalization_status_id' => HospitalizationStatus::where('name', $statusName)->value('id'),
            'changed_by' => $this->user->id,
            'changed_at' => now(),
        ]);
    }

    /** @test */
    public function traslado_consulta_a_hospitalizacion_no_requiere_pago_previo_y_redirige_a_red_sheet()
    {
        $origin = $this->makeOriginReception(1);

        $response = $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 2,
            'reason' => 'Requiere hospitalización',
            'admission_type_id' => AdmissionType::factory()->create()->id,
            'area_id' => Area::factory()->create()->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $destinationId = ReceptionTransfer::where('from_reception_id', $origin->id)->value('to_reception_id');
        $this->assertNotNull($destinationId);

        $this->assertStringContainsString(
            'hospitalizations/entries/' . $destinationId,
            $response->json('redirect')
        );

        $this->assertEquals(
            'Trasladado',
            Reception::find($destinationId)->currentHospitalizationStatus?->hospitalizationStatus?->name
        );
    }

    /** @test */
    public function red_sheet_es_editable_en_estatus_trasladado()
    {
        $destination = $this->makeOriginReception(2);
        $this->setHospitalizationStatus($destination, 'Trasladado');

        $response = $this->actingAs($this->user)->get(route('redsheet.entry', $destination->id));

        $response->assertStatus(200);
        $response->assertViewIs('red-sheet.create');
    }

    /** @test */
    public function red_sheet_es_editable_en_estatus_hospitalizado()
    {
        $destination = $this->makeOriginReception(2);
        $this->setHospitalizationStatus($destination, 'Hospitalizado');

        $response = $this->actingAs($this->user)->get(route('redsheet.entry', $destination->id));

        $response->assertStatus(200);
        $response->assertViewIs('red-sheet.create');
    }

    /** @test */
    public function red_sheet_queda_bloqueado_cuando_ya_fue_dado_de_alta()
    {
        $destination = $this->makeOriginReception(2);
        $this->setHospitalizationStatus($destination, 'Hospitalizado');
        $this->setHospitalizationStatus($destination, 'Dado de alta');

        $response = $this->actingAs($this->user)->get(route('redsheet.entry', $destination->id));

        $response->assertRedirect(route('redsheet.show', $destination->id));
    }

    /** @test */
    public function red_sheet_queda_bloqueado_si_esta_hospitalizacion_ya_fue_trasladada_a_otra()
    {
        $destination = $this->makeOriginReception(2);
        $this->setHospitalizationStatus($destination, 'Hospitalizado');

        // isTransferred() es la única señal confiable para "se trasladó
        // hacia afuera" (ver comentario en RedSheetController::entry()):
        // el estatus sigue diciendo "Hospitalizado", pero ya no debe ser
        // editable porque esta recepción ya no es la vigente del episodio.
        ReceptionTransfer::create([
            'from_reception_id' => $destination->id,
            'to_reception_id' => $this->makeOriginReception(5)->id,
            'reason' => 'Traslado a cremación',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('redsheet.entry', $destination->id));

        $response->assertRedirect(route('redsheet.show', $destination->id));
    }

    /** @test */
    public function calculo_de_servicios_hospitalarios_incluye_todo_lo_agregado_en_red_sheet()
    {
        $destination = $this->makeOriginReception(2);

        RedSheet::create([
            'reception_id' => $destination->id,
            'service_type_id' => self::SERVICE_TYPE_ID,
            'day_count' => 1,
            'vet_id' => $this->user->id,
        ]);

        $statementService = app(AccountStatementService::class);

        $this->assertGreaterThan(0.0, $statementService->hospitalizacionServiciosTotal($destination));
    }

    /** @test */
    public function sin_servicios_hospitalarios_el_anticipo_no_se_inventa()
    {
        $destination = $this->makeOriginReception(2);

        $statementService = app(AccountStatementService::class);

        $this->assertEquals(0.0, $statementService->hospitalizacionServiciosTotal($destination));
        $this->assertEquals(0.0, $statementService->hospitalizacionAnticipoRequerido($destination));
    }

    /** @test */
    public function servicios_de_otra_hospitalizacion_no_se_mezclan_en_el_calculo()
    {
        $destination = $this->makeOriginReception(2);
        $otraHospitalizacion = $this->makeOriginReception(2);

        RedSheet::create([
            'reception_id' => $otraHospitalizacion->id,
            'service_type_id' => self::SERVICE_TYPE_ID,
            'day_count' => 1,
            'vet_id' => $this->user->id,
        ]);

        $statementService = app(AccountStatementService::class);

        $this->assertEquals(0.0, $statementService->hospitalizacionServiciosTotal($destination));
    }

    /** @test */
    public function servicio_agregado_despues_del_primer_calculo_se_refleja_al_recalcular()
    {
        $destination = $this->makeOriginReception(2);
        $statementService = app(AccountStatementService::class);

        $this->assertEquals(0.0, $statementService->hospitalizacionServiciosTotal($destination));

        RedSheet::create([
            'reception_id' => $destination->id,
            'service_type_id' => self::SERVICE_TYPE_ID,
            'day_count' => 1,
            'vet_id' => $this->user->id,
        ]);

        // hospitalizacionServiciosTotal() nunca guarda el valor anterior:
        // vuelve a consultar RedSheet/OrdenVentaService en cada llamada.
        $this->assertGreaterThan(0.0, $statementService->hospitalizacionServiciosTotal($destination->fresh()));
    }

    /** @test */
    public function el_minimo_requerido_es_el_saldo_de_consulta_mas_el_anticipo_pendiente()
    {
        // Sin traslado (episodio sin Reception de Consulta): consultaBalance
        // es 0, así que el mínimo queda igual al anticipo (hoy 0.0, regla
        // pendiente de definir).
        $destination = $this->makeOriginReception(2);
        $statementService = app(AccountStatementService::class);

        $this->assertEquals(
            $statementService->consultaBalance($destination) + $statementService->hospitalizacionAnticipoRequerido($destination),
            $statementService->paymentMinimumRequired($destination)
        );
    }

    /** @test */
    public function payment_summary_expone_servicios_y_minimo_actualizados()
    {
        $destination = $this->makeOriginReception(2);

        RedSheet::create([
            'reception_id' => $destination->id,
            'service_type_id' => self::SERVICE_TYPE_ID,
            'day_count' => 1,
            'vet_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->getJson(route('receptions.paymentSummary', $destination->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'consulta' => ['balance'],
            'hospitalizacion' => ['servicios', 'servicios_total', 'anticipo_requerido'],
            'minimum_required',
        ]);
        $this->assertCount(1, $response->json('hospitalizacion.servicios'));
    }

    /** @test */
    public function pay_hospitalizacion_rechaza_un_monto_menor_al_minimo_requerido()
    {
        $origin = $this->makeOriginReception(1);
        $destination = $this->makeOriginReception(2);
        // Simula que la hospitalización comparte episodio/cuenta con una
        // consulta con saldo pendiente (mismo efecto que un traslado real).
        $destination->update(['episode_id' => $origin->episode_id]);

        $response = $this->actingAs($this->user)->postJson(
            route('advance-payments.payHospitalizacion', $destination->id),
            ['amount' => '0.01', 'concept' => 'ignorado']
        );

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function pay_hospitalizacion_con_monto_suficiente_registra_el_pago_de_consulta()
    {
        $origin = $this->makeOriginReception(1);
        $destination = $this->makeOriginReception(2);
        $destination->update(['episode_id' => $origin->episode_id]);

        $statementService = app(AccountStatementService::class);
        $minimo = $statementService->paymentMinimumRequired($destination->fresh());

        $response = $this->actingAs($this->user)->postJson(
            route('advance-payments.payHospitalizacion', $destination->id),
            ['amount' => (string) $minimo, 'concept' => 'ignorado']
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0.0, $statementService->consultaBalance($destination->fresh()));
    }

    /** @test */
    public function consulta_parcialmente_pagada_solo_cobra_el_balance_restante()
    {
        $origin = $this->makeOriginReception(1);
        $destination = $this->makeOriginReception(2);
        $destination->update(['episode_id' => $origin->episode_id]);

        $statementService = app(AccountStatementService::class);
        $totalConsulta = $statementService->consultaBalance($destination->fresh());

        if ($totalConsulta <= 0) {
            $this->markTestSkipped('Esta recepción no genera cargo de consulta; nada que probar aquí.');
        }

        $account = $origin->episode->account;
        \App\Models\AdvancePayment::create([
            'reception_id' => $origin->id,
            'account_id' => $account->id,
            'reference' => 'TEST',
            'concept' => 'Anticipo consulta',
            'amount' => $totalConsulta / 2,
            'status' => 0,
            'date' => now(),
        ]);

        $balanceRestante = $statementService->consultaBalance($destination->fresh());

        $this->assertEqualsWithDelta($totalConsulta / 2, $balanceRestante, 0.01);
    }
}
