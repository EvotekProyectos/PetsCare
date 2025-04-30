<?php

namespace Tests\Feature;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\HospitalDischarge;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\ReceptionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HospitalizationTest extends TestCase
{
    use RefreshDatabase; //limpia la BD de pruebas antes de correr los test
    private $user; //Usuario ficticio para hacer las pruebas

    protected function setUp(): void //Establecmeos las bases para las pruebas como el usuario que actuara en todas
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']); // Ejecuta los seeders de permisos y roles
        $this->user = User::factory()->create(); //Creacion del usuario para pruebas
        $this->user->assignRole('administrador'); //asigna el rol de administrador al usuario de pruebas

        //Lllamar a los seeders necesarios para realizar las pruebas
        Artisan::call('db:seed', ['--class' => 'FormatTypeSeeder']); 
        // Artisan::call('db:seed', ['--class' => 'HospitalDischargeSeeder']); 
    }

    /** @test */
    public function show_new_redsheet()
    {
        //Datos para una recepction del tipo hopital
        $data = [
            'pet_id' => Pet::factory()->create()->id, // Crea una mascota aleatoria
            'reception_type_id' =>   ReceptionType::factory()->create()->id, // Crea un tipo de recepción
            'admission_type_id' => AdmissionType::factory()->create()->id, // Relation de tipo de admision opcional
            'area_id' => Area::factory()->create()->id, // Relacion con las areas opcional
            'family_id' => Family::factory()->create()->id, // crea una familia 
            'veterinarian_id' => User::factory()->create()->id, // Crea un usuario veterinario
            'recepcionist_id' => User::factory()->create()->id, // Crea un usuario recepcionista
            'entry_date' => '2025-04-05 10:41:00', //fecha de ingreso aleatoria
        ];

        $reception = Reception::factory()->create($data); //Creamos recepcion ficticia para el test

        //actuando como el usuario creado solicitamos la vista del formulario de nuevo registro
        $response = $this->actingAs($this->user)->get(route('redsheet.entry', $reception->id));

        $response->assertStatus(200); //confirmamos status de la peticion
        $response->assertViewIs('red-sheet.create'); //checamos que muesra la vista correcta

    }

     /** @test */
     public function save_redsheet()
     {
        //datos para entrada ficticia
        $data = [
            'reception_id' => Reception::factory()->create()->id,
            'service_type_id' => 113992,
            'observations' => 'cuidadosamente',
			'day_count' => 1,
            'vet_id' => User::factory()->create()->id,
        ];

        //actuando como el usuario de prueba, enviamos los datos a guardar para la funcion store
        $response = $this->actingAs($this->user)->post(route('red-sheets.store'), $data);

        $this->assertDatabaseHas('red_sheets', $data); //confirmamos que los datos enviados se encuentren en la tabla
     }

     /** @test */
     public function show_sign_volunteer_exit_page()
     {
        $reception = Reception::factory()->create(); //creamos el registro de prueba para llenar el formato
        
        $response = $this->actingAs($this->user)->get(route('alta.voluntaria', $reception->id)); //con el registro de prueba mandamos llamar la funcion

        $response->assertStatus(200); // La vista se carga correctamente
        $response->assertViewIs('hospital-discharge.alta_voluntaria'); // Verifica que se usa la vista correcta
    
     }

     /** @test */
     public function save_sign_volunteer_exit_pdf ()
     {
        $reception = Reception::factory()->create(); //creamos el registro de prueba para llenar el formato
        //generamos la firma para el pdf
        $data = [
            'signature' => 'data:image/png;base64,' . base64_encode(UploadedFile::fake()->image('signature.png')->getContent()),
        ];

        $response = $this->actingAs($this->user)->post(route('altaVoluntaria.pdf', $reception->id), $data); //mandamos los datos a la funcion
        $response->assertStatus(200); // verificamos que la petición se procesa correctamente
        $response->assertJsonStructure(['url']); // comprobamos que nos regresa un url valido

        // // Verifica que el PDF se haya guardado
        $pdfPath = "public/hospitalizations/voluntary_discharge_{$reception->id}.pdf";
        $this->assertTrue(Storage::disk('public')->exists("hospitalizations/voluntary_discharge_{$reception->id}.pdf"));
     }

     /** @test */
     public function save_hospital_discharge()
     {
        //establecemos los datos para la prueba
        $data = [
            'reception_id' => Reception::factory()->create()->id,
            'hospital_discharges_id' => HospitalDischarge::factory()->create()->id,
        ];

        $response = $this->actingAs($this->user)->post(route('hospitalization.discharge'), $data); // mandamos los datos a la funcion que los procesa

        $this->assertDatabaseHas('hospitalizations', $data); //confirmamos que los datos enviados se encuentren en la tabla

     }
}
