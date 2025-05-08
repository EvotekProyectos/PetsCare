<?php

namespace Tests\Feature;

use App\Http\Controllers\ReceptionController;
use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\ReceptionType;
use App\Models\RedSheet;
use App\Models\Surgery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReceptionTest extends TestCase
{
    use RefreshDatabase; //limpia la BD de pruebas antes de correr los test
    private $user; //Usuario ficticio para hacer las pruebas

    protected function setUp(): void //Establecmeos las bases para las pruebas como el suuario que actuara en todas
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']); // Ejecuta los seeders de permisos y roles
        $this->user = User::factory()->create(); //Creacion del usuario para pruebas
        $this->user->assignRole('administrador'); //asigna el rol de administrador al usuario de pruebas

        //Lllamar a los seeders necesarios para realizar las pruebas
        Artisan::call('db:seed', ['--class' => 'AdmissionTypeSeeder']);
        Artisan::call('db:seed', ['--class' => 'FormatTypeSeeder']);
    }

    /** @test */
    public function create_reception()
    {
        //establecemos los datos para guardar la recepcion
        $data = [
            'pet_id' => Pet::factory()->create()->id,
            'reception_type_id' => ReceptionType::factory()->create()->id,
            'admission_type_id' => AdmissionType::factory()->create()->id,
            'area_id' => Area::factory()->create()->id,
            'family_id' => Family::factory()->create()->id,
            'veterinarian_id' => User::factory()->create()->id,
            'recepcionist_id' =>  User::factory()->create()->id,
            'entry_date' => '2025-03-25 10:41:00',
        ];

        //actuando como el usuario creado mandamos los nuevos datos para guardar
        $response = $this->actingAs($this->user)->post(route('receptions.store'), $data);

        $this->assertDatabaseHas('receptions', $data); //confirmamos que los datos enviados se encuentren en la tabla
    }

    /** @test */
    public function edit_reception()
    {
        $reception = Reception::factory()->create();

        $data = [
            'pet_id' => $reception->pet_id,
            'reception_type_id' => ReceptionType::factory()->create()->id,
            'admission_type_id' => AdmissionType::factory()->create()->id,
            'area_id' => Area::factory()->create()->id,
            'veterinarian_id' => User::factory()->create()->id,
            'recepcionist_id' =>  User::factory()->create()->id,
            'entry_date' => '2025-03-31 11:00:00',
            'exit_date' => '2025-04-04 11:00:00',
            'num' => 13,
        ];

        //actuando como el usuario creado mandamos los nuevos datos y el id de la recepcion a la ruta para el update
        $response = $this->actingAs($this->user)->put(route('receptions.update', $reception->id), $data);



        $this->assertDatabaseHas('receptions', $data); //confirmamos que los datos enviados se encuentren en la tabla
        $response->assertRedirect(route('receptions.index')); //confirmamos a que vista nos regreso
    }

    /** @test */
    public function delete_reception()
    {
        $reception = Reception::factory()->create();; //creamos recepcion de prueba

        //actuando como el usuario creado llamar la funcion destroy junto al id del recepcion a borrar
        $response = $this->actingAs($this->user)->delete(route('receptions.destroy', $reception->id));

        $this->assertSoftDeleted('receptions', ['id' => $reception->id]);
    }

    /** @test */
    public function list_by_receptiontype()
    {
        // Creamos un tipo de recepción
        $type = ReceptionType::factory()->create();

        // Creamos 3 recepciones con ese tipo
        Reception::factory()->count(3)->create([
            'reception_type_id' => $type->id,
        ]);

        // Y 2 recepciones con otro tipo para asegurar que se filtren
        Reception::factory()->count(2)->create();

        // Hacemos la petición al método list enviando el tipo como parámetro
        $response = $this->actingAs($this->user)->getJson(route('reception.list', ['reception_type_id' => $type->id]));

        // Verificamos que la petición fue exitosa
        $response->assertStatus(200);

        // Verificamos que haya exactamente 3 resultados
        $responseData = $response->json('data');
        $this->assertCount(3, $responseData);

        // También puedes verificar que el tipo sea correcto en todos los resultados
        foreach ($responseData as $reception) {
            $this->assertEquals($type->id, $reception['reception_type_id']);
        }
    }

    /** @test */
    public function transfer_area()
    {
        // Crear una recepción de prueba
        $reception = Reception::factory()->create([
            'admission_type_id' => 1
        ]);

        $data = ['admission_type_id' => 2]; //nueva a rea a tranferir

        //llamamos la funcion
        $response = $this->actingAs($this->user)->put(route('reception.transfer', $reception->id), $data);

        //verificamos
        $response->assertStatus(200);
        $this->assertDatabaseHas('receptions', [
            'id' => $reception->id,
            'admission_type_id' => 2
        ]);
    }

    /** @test */
    public function generate_and_save_hospitalizaton_autorization()
    {
        $reception = Reception::factory()->create(); //recepcion para la prueba

        //generamos la firma y total para el pdf
        $data = [
            'total' => 350,
            'signature' => 'data:image/png;base64,' . base64_encode(UploadedFile::fake()->image('signature.png')->getContent()),
        ];

        $response = $this->actingAs($this->user)->post(route('hospital.pdf', $reception->id), $data); //mandamos los datos a la funcion
        $response->assertStatus(200); // verificamos que la petición se procesa correctamente
        $response->assertJsonStructure(['url']); // comprobamos que nos regresa un url valido

        // // Verifica que el PDF se haya guardado
        $pdfPath = "public/receptions/reception_{$reception->id}.pdf";
        $this->assertTrue(Storage::disk('public')->exists("receptions/reception_{$reception->id}.pdf"));
    }

    /** @test */
    public function load_grooming()
    {
        $reception = Reception::factory()->create(); //recepcion para la prueba

        $response = $this->actingAs($this->user)->get(route('receptions.grooming', $reception->id)); //mandamos llamar la funcion 

        $response->assertStatus(200); //confirmamos status de la peticion
        $response->assertViewIs('grooming.create'); //checamos que muesra la vista correcta
    }

   
}
