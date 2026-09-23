<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AdvancePayment;
use App\Models\Episode;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdvancePaymentTest extends TestCase
{

    use RefreshDatabase; //limpia la BD de pruebas antes de correr los test
    private $user; //Usuario ficticio para hacer las pruebas
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    protected function setUp(): void //Crea el usuario ficticio que actuara para todas las pruebas
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']); // Ejecuta los seeders de permisos y roles
        $this->user = User::factory()->create(); //Creacion del usuario para pruebas
        $this->user->assignRole('administrador'); //asigna el rol de administrador al usuario de pruebas
    }

    /**
     * Crea una Reception con su Episode + Account, igual que
     * ReceptionController::store() en producción. ReceptionFactory no
     * replica ese efecto secundario por su cuenta, así que cualquier prueba
     * que dependa de la cadena reception->episode->account debe armarla así.
     */
    private function receptionWithAccount(array $receptionAttributes = [], string $accountStatus = Account::STATUS_OPEN, ?\DateTimeInterface $closedAt = null): Reception
    {
        $reception = Reception::factory()->create($receptionAttributes);

        $episode = Episode::create([
            'pet_id' => $reception->pet_id,
            'status' => Episode::STATUS_OPEN,
            'opened_at' => now()->subDay(),
        ]);

        Account::create([
            'episode_id' => $episode->id,
            'status' => $accountStatus,
            'closed_at' => $closedAt,
        ]);

        $reception->update(['episode_id' => $episode->id]);

        return $reception->fresh();
    }

    /** @test */
    public function it_generates_a_pdf_receipt()
    {
        $payment = AdvancePayment::factory()->create(); //crea un registro de prueba en la tabla de anticipos

        //Mandamos llamar a la ruta que nos genera el PDF usando el registro de prueba
        $response = $this->actingAs($this->user)->get(route('advance-payments.pdf', $payment->id));

        $response->assertStatus(200); //comprobamos el status
        $response->assertHeader('content-type', 'application/pdf'); //confirmamos que la respuesta es un PDF
    }

    /** @test */
    public function show_index_view()
    {
        //actuando como el usuario creado solicitamos abriri la vista de index
        $response = $this->actingAs($this->user)->get(route('advance-payments.index'));

        $response->assertStatus(200); //confirmamos status de la peticion
        $response->assertViewIs('advance-payment.index'); //confirmamos la vista
    }

    /** @test */
    public function show_new_form_for_advancepayment()
    {
        $reception = Reception::factory()->create(); //Creamos recepcion ficticia para el test

        //actuando como el usuario creado solicitamos la vista del formulario de nuevo registro
        $response = $this->actingAs($this->user)->get(route('advance-payments.add', $reception->id));

        $response->assertStatus(200); //confirmamos status de la peticion
        $response->assertViewIs('advance-payment.create'); //checamos que muesra la vista correcta
    }

    /** @test */
    public function save_new_register_for_advancepayment()
    {
        // Reception::factory() por sí sola no crea Episode/Account (ese
        // efecto secundario solo lo produce ReceptionController::store() en
        // producción) — se arma aquí a mano para reflejar el flujo real.
        $reception = $this->receptionWithAccount();

        //establecemos los datos reuqridos para un nuevo registro de anticpo
        $data = [
            'date' => '2025-03-31 11:44:00',
            'reception_id' => $reception->id,
            'amount' => '1000',
            'concept' => 'Anticipo de manjeo de herida',
            'status' => '0',
        ];

        //actuando como el usuario de prueba, enviamos los datos a guardar para la funcion store
        $response = $this->actingAs($this->user)->post(route('advance-payments.store'), $data);

        $this->assertDatabaseHas('advance_payments', $data); //confirmamos que los datos enviados se encuentren en la tabla
    }

    /** @test */
    public function caso_1_nuevo_anticipo_queda_ligado_a_la_cuenta_de_su_recepcion()
    {
        $reception = $this->receptionWithAccount();
        $account = $reception->episode->account;

        $response = $this->actingAs($this->user)->post(route('advance-payments.store'), [
            'date' => now()->format('Y-m-d H:i:s'),
            'reception_id' => $reception->id,
            'amount' => '500',
            'concept' => 'Anticipo consulta',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('advance_payments', [
            'reception_id' => $reception->id,
            'account_id' => $account->id,
        ]);
    }

    /** @test */
    public function caso_2_segundo_anticipo_de_la_misma_cuenta_queda_ligado_a_la_misma_cuenta()
    {
        $reception = $this->receptionWithAccount();
        $account = $reception->episode->account;

        foreach (['Primer anticipo', 'Segundo anticipo'] as $concepto) {
            $this->actingAs($this->user)->post(route('advance-payments.store'), [
                'date' => now()->format('Y-m-d H:i:s'),
                'reception_id' => $reception->id,
                'amount' => '300',
                'concept' => $concepto,
            ]);
        }

        $this->assertEquals(
            2,
            AdvancePayment::where('reception_id', $reception->id)->where('account_id', $account->id)->count()
        );
    }

    /** @test */
    public function caso_3_mascota_con_varias_hospitalizaciones_liga_cada_anticipo_a_su_propia_cuenta()
    {
        $petId = \App\Models\Pet::factory()->create()->id;

        $receptionA = $this->receptionWithAccount(['pet_id' => $petId]);
        $receptionB = $this->receptionWithAccount(['pet_id' => $petId]);

        $this->actingAs($this->user)->post(route('advance-payments.store'), [
            'date' => now()->format('Y-m-d H:i:s'),
            'reception_id' => $receptionA->id,
            'amount' => '100',
            'concept' => 'Anticipo hospitalización A',
        ]);

        $this->actingAs($this->user)->post(route('advance-payments.store'), [
            'date' => now()->format('Y-m-d H:i:s'),
            'reception_id' => $receptionB->id,
            'amount' => '200',
            'concept' => 'Anticipo hospitalización B',
        ]);

        $accountA = $receptionA->episode->account;
        $accountB = $receptionB->episode->account;

        $this->assertNotEquals($accountA->id, $accountB->id);
        $this->assertDatabaseHas('advance_payments', ['reception_id' => $receptionA->id, 'account_id' => $accountA->id]);
        $this->assertDatabaseHas('advance_payments', ['reception_id' => $receptionB->id, 'account_id' => $accountB->id]);
    }

    /** @test */
    public function caso_4_sin_cuenta_valida_no_crea_el_anticipo_y_regresa_error_controlado()
    {
        $before = AdvancePayment::count();

        // Reception::factory() sin receptionWithAccount(): episode_id queda
        // null a propósito, para simular "sin contexto de cuenta válido".
        $reception = Reception::factory()->create();

        $response = $this->actingAs($this->user)->post(route('advance-payments.store'), [
            'date' => now()->format('Y-m-d H:i:s'),
            'reception_id' => $reception->id,
            'amount' => '100',
            'concept' => 'Anticipo sin cuenta',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals($before, AdvancePayment::count());
    }

    /** @test */
    public function show_edit_fomr_for_advancepayment()
    {
        $advancepayment = AdvancePayment::factory()->create(); //creamos anticipo de prueba

        //actuando como el usuario creado solicitamos la vista del formulario para editar registro
        $response = $this->actingAs($this->user)->get(route('advance-payments.edit', $advancepayment->id));

        $response->assertStatus(200); //confirmamos status de la peticion
        $response->assertViewIs('advance-payment.edit'); //checamos que muesra la vista correcta

    }

    /** @test */
    public function update_register_for_advancepayment()
    {
        $advancepayment = AdvancePayment::factory()->create(); //creamos anticipo de prueba

        //Establecemos los datos a modificar
        $data = [
            'date' => '2025-03-31 11:44:00',
            'amount' => '750',
            'concept' => 'Pago de quimica sanguinea 15 elementos',
            'status' => '1',
        ];

        //actuando como el usuario creado mandamos los nuevos datos y el id del anticipo a la ruta para el update
        $response = $this->actingAs($this->user)->put(route('advance-payments.update', $advancepayment->id), $data);

        $this->assertDatabaseHas('advance_payments', $data); //confirmamos que nuestros datos esten en la tabla
        $response->assertRedirect(route('advance-payments.index')); //confirmamos a que vista nos regreso
    }

    /** @test */
    public function delete_register_for_advancepayment()
    {
        $advancepayment = AdvancePayment::factory()->create(); //creamos anticipo de prueba

        //actuando como el usuario creado llamar la funcion destroy junto al id del anticipo a borrar
        $response = $this->actingAs($this->user)->delete(route('advance-payments.destroy', $advancepayment->id));

        $this->assertSoftDeleted('advance_payments', ['id' => $advancepayment->id]);
    }

    /** @test */
    public function list_data_for_advancepayment()
    {
        // creamos registros de prueba
        AdvancePayment::factory()->count(3)->create();

        // actuuando como el usuario llamamos la funcion de list
        $response = $this->actingAs($this->user)
            ->getJson(route('advance-payments.list'));

        // Vconfirmamos el status de la peticion y la correcta estructura de los datos para la datatable
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'reception_id', 'user_id', 'amount', 'reference']
                ]
            ]);
    }

    /** @test */
    public function caso_5_backfill_liga_los_historicos_resolvibles_y_deja_sin_tocar_los_que_no_lo_son()
    {
        $reception = $this->receptionWithAccount();
        $account = $reception->episode->account;

        // Histórico resolvible: tiene reception_id (y por tanto account_id
        // resolvible), pero fue creado antes de que existiera la columna.
        $resolvible = AdvancePayment::factory()->create(['reception_id' => $reception->id]);

        // Histórico sin cuenta identificable: sin reception_id en absoluto.
        $sinCuenta = AdvancePayment::factory()->create(['reception_id' => null]);

        Artisan::call('advance-payments:backfill-accounts');

        $this->assertEquals($account->id, $resolvible->fresh()->account_id);
        $this->assertNull($sinCuenta->fresh()->account_id);
    }

    /** @test */
    public function caso_6_dry_run_no_modifica_ningun_registro()
    {
        $reception = $this->receptionWithAccount();
        $resolvible = AdvancePayment::factory()->create(['reception_id' => $reception->id]);

        Artisan::call('advance-payments:backfill-accounts', ['--dry-run' => true]);

        $this->assertNull($resolvible->fresh()->account_id);
    }
}
