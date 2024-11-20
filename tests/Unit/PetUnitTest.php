<?php

namespace Tests\Unit;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Carbon\Carbon;

class PetUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        DB::table('fam_classifications')->insert(['id' => 1, 'name' => 'yes']);
        DB::table('families')->insert(['id' => 1, 'name' => 'Doe Family', 'phone' => '1234567890', 'email' => 'doe@example.com', 'address' => 'required', 'contact_name' => 'required', 'contact_number' => '8444', 'fam_classification_id' => 1]);
        DB::table('genres')->insert(['id' => 1, 'name' => 'Male']);
        DB::table('reproductive_statuses')->insert(['id' => 2, 'name' => 'Neutered']);
        DB::table('pet_classifications')->insert(['id' => 1, 'name' => 'Domestic']);
     
      $pet = Pet::create([
        'family_id' => 1,
        'name' => 'Firulais',
        'specie' => 'Perro',
        'raza' => 'Labrador',
        'gender_id' => 1,
        'birthday' => '2020-01-01',
        'reproductive_status_id' => 2,
        'weight' => '20kg',
        'physic_descrip' => 'Color marrón, orejas largas',
        'notes' => 'Amigable con niños',
        'pet_classification_id' => 1,
        'deceased' => 0,
    ]);

    $this->assertEquals('Firulais', $pet->name);
    $this->assertEquals('Perro', $pet->specie);
    $this->assertEquals('Labrador', $pet->raza);
    $this->assertEquals('20kg', $pet->weight);
    $this->assertEquals('Amigable con niños', $pet->notes);
    $this->assertEquals(0, $pet->deceased);
 
    $this->assertDatabaseHas('pets', [
        'name' => 'Firulais',
        'specie' => 'Perro',
        'raza' => 'Labrador',
    ]);

    $pet->delete();

    $this->assertSoftDeleted($pet);
}
}