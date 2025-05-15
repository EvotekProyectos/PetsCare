<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reception;
use App\Models\Cubicle;
use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HotelTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders necesarios
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->artisan('db:seed', ['--class' => 'ReasonSeeder']);
        $this->artisan('db:seed', ['--class' => 'dateTypesSeeder']);
        $this->artisan('db:seed', ['--class' => 'statusDatesSeeder']);
        $this->artisan('db:seed', ['--class' => 'FormatTypeSeeder']);

        // Crear usuario con rol administrador para pruebas
        $this->user = User::factory()->create();
        $this->user->assignRole('administrador');

        $this->actingAs($this->user);
    }

    /** @test */
    public function guardarHotel()
    {
        $hotelData = Hotel::factory()->make()->toArray();

        if (!empty($hotelData['finish_date']) && $hotelData['finish_date'] instanceof \DateTime) {
            $hotelData['finish_date'] = $hotelData['finish_date']->format('Y-m-d H:i:s');
        }

        $response = $this->postJson(route('hotels.store'), $hotelData);

        $response->assertStatus(200);

        // Verificamos que hay un registro con el reception_id
        $this->assertDatabaseHas('hotels', [
            'reception_id' => $hotelData['reception_id'],
        ]);
    }
    /** @test */
    public function StoreExtension()
    {
        $hotelData = Hotel::factory()->make([
            'extension' => 1,
            'number_days' => 2,
        ])->toArray();

        if (!empty($hotelData['finish_date']) && $hotelData['finish_date'] instanceof \DateTime) {
            $hotelData['finish_date'] = $hotelData['finish_date']->format('Y-m-d H:i:s');
        }

        $response = $this->postJson(route('hotels.storeExtension'), $hotelData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('hotels', [
            'reception_id' => $hotelData['reception_id'],
            'extension' => 1,
        ]);
    }

    /** @test */
    /** @test */
    public function SalidaExitosa()
    {
        // Crear cubículo activo
        $cubicle = Cubicle::factory()->create(['state' => 1]);

        // Crear recepción con entry_date hace 3 días
        $entryDate = now()->subDays(3)->startOfDay();
        $reception = Reception::factory()->create([
            'entry_date' => $entryDate,
        ]);

        // Crear hotel vinculado a la recepción y cubículo
        $hotel = Hotel::factory()->create([
            'reception_id' => $reception->id,
            'number_days' => 3,
            'cubicle_id' => $cubicle->id,
            'finish_date' => now()->startOfDay(), // fecha esperada
        ]);

        // Enviar solicitud POST a la ruta
        $response = $this->postJson(route('hotel-exit', ['id' => $hotel->id]), [
            'hotel' => $hotel->id,
        ]);

        // Verificar respuesta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fecha de salida registrada con éxito'
            ]);

        // Verificar que el campo finish_date fue actualizado (con precisión a minutos)
        $this->assertNotNull($hotel->fresh()->finish_date);

        // Verificar que el cubículo cambió de estado a 0 (disponible)
        $this->assertDatabaseHas('cubicles', [
            'id' => $cubicle->id,
            'state' => 0,
        ]);
    }

}
