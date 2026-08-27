<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Account;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\ReceptionTransfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ReceptionTransferTest extends TestCase
{
    use RefreshDatabase;

    private $user;

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
            'pet_id' => Pet::factory()->create()->id,
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

    /** @test */
    public function transfer_consulta_a_hospitalizacion_hereda_episodio()
    {
        $origin = $this->makeOriginReception(1);

        $response = $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 2,
            'reason' => 'Requiere hospitalización',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'reception_type_id' => 2]);

        $destinationId = ReceptionTransfer::where('from_reception_id', $origin->id)->value('to_reception_id');
        $this->assertNotNull($destinationId);

        $destination = Reception::find($destinationId);
        $this->assertEquals($origin->episode_id, $destination->episode_id);
        $this->assertEquals($origin->pet_id, $destination->pet_id);

        $this->assertDatabaseHas('reception_status_histories', [
            'reception_id' => $origin->id,
        ]);
        $this->assertDatabaseHas('hospitalization_status_histories', [
            'reception_id' => $destination->id,
            'hospitalization_status_id' => 1,
        ]);

        $this->assertTrue($origin->fresh()->isTransferred());
        $this->assertFalse($destination->fresh()->isTransferred());
    }

    /** @test */
    public function cremacion_no_puede_ser_origen()
    {
        $origin = $this->makeOriginReception(5);

        $response = $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 1,
            'reason' => 'No debería permitirse',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function no_se_puede_trasladar_dos_veces_la_misma_recepcion()
    {
        $origin = $this->makeOriginReception(1);

        $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 3,
            'reason' => 'Primer traslado',
        ])->assertStatus(200);

        $response = $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 2,
            'reason' => 'Segundo traslado, no debería permitirse',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function no_permite_trasladar_al_mismo_tipo()
    {
        $origin = $this->makeOriginReception(1);

        $response = $this->actingAs($this->user)->postJson(route('receptions.episodeTransfer', $origin->id), [
            'reception_type_id' => 1,
            'reason' => 'Mismo tipo, no debería permitirse',
        ]);

        $response->assertStatus(422);
    }
}
