<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReferenceGeneratorTest extends TestCase
{
    use RefreshDatabase;
   
    // public function test_pet_creation()
    // {
    //     $pet =  Pet::factory()->create();
    
    //     $this->assertDatabaseHas('pets', [
    //         'id' => $pet->id,
    //     ]);
    // }
    public function test_valid_reference()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();

        // Autenticar al usuario en la prueba
        $this->actingAs($user);
        //Recepción imaginaria para probar
        $reception = Reception::factory()->create();

        //Llamar a la función que genera la referencia
        $response = $this->getJson(route('advance-payments.refrence', $reception->id));
        $response->assertStatus(200);

        //Obtener referencia geenrada
        $reference = $response->json('reference');

        // Verificar que la referencia tiene el formato correcto
        $this->assertMatchesRegularExpression('/^\d{6}\d{2}\d{2}\d{2}[A-Z]\d$/', $reference);
    }

    /** @test */
    public function it_generates_unique_references()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create();

        // Autenticar al usuario en la prueba
        $this->actingAs($user);
        
        // 🟢 Arrange: Crear múltiples recepciones
        $receptions = Reception::factory()->count(10)->create();
        $references = [];

        // 🔵 Act: Generar referencias para cada recepción
        foreach ($receptions as $reception) {
            $response = $this->getJson(route('advance-payments.refrence', $reception->id));
            $response->assertStatus(200);

            $reference = $response->json('reference');
            $references[] = $reference;
        }

        // 🟣 Assert: Asegurar que todas las referencias sean únicas
        $this->assertCount(count($references), array_unique($references));
    }
}
