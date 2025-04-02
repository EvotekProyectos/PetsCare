<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use App\Models\GeneralGrooming;
use App\Models\Grooming;
use App\Models\GroomingStatus;
use App\Models\PaymentOrder;
use App\Models\Reception;
use App\Models\User;
use App\Notifications\GroomingStatus as NotificationsGroomingStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GroomingTest extends TestCase
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
        Artisan::call('db:seed', ['--class' => 'ReasonSeeder']); 
        Artisan::call('db:seed', ['--class' => 'dateTypesSeeder']); 
        Artisan::call('db:seed', ['--class' => 'statusDatesSeeder']); 
        Artisan::call('db:seed', ['--class' => 'FormatTypeSeeder']); 
    }

    /** @test */
    public function send_notification()
    {
        // Simular notificaciones
        Notification::fake();

        // Crear usuario recepcionista para asegurarnos de que hay notificables
        $recepcionista = User::factory()->create();
        $recepcionista->assignRole('recepcionista');

        $reception = Reception::factory()->create(); //Creamos recepcion ficticia para el test
        $groomingStatus = GroomingStatus::factory()->create(); //creamos status para la prueba

        //establecemos los datos para actualizar el estado 
        $data = [
            'reception_id' => $reception->id,
            'grooming_status_id' => $groomingStatus->id,
        ];

        //actuando como el usuario de prueba, enviamos los datos para actualizar el estado
        $response = $this->actingAs($this->user)->post(route('grooming.status'), $data);


        $response->assertStatus(201); // Asegurar que la respuesta fue exitosa

        // Verificar que se creó el historial correctamente
        $this->assertDatabaseHas('grooming_status_histories', [
            'reception_id' => $reception->id,
            'grooming_status_id' => $groomingStatus->id,
        ]);

        // Verificar que se envió la notificación
        Notification::assertSentTo(
            User::role('recepcionista')->get(),
            NotificationsGroomingStatus::class
        );
    }

    /** @test */
    public function folio_limit()
    {
        $generalgroomings = GeneralGrooming::factory()->count(200)->create(); //Creamos 200 general grooming ficticias para el test

        $response = $this->actingAs($this->user)->get(route('general-groomings.folio')); //mandamos llamar la funcion que genera los folios

        $response->assertStatus(200); //comprobamos el status
        $response->assertJson(['folio' => 1]); //comprobamos que nos regreso el folio 1
    }

    /** @test */
    public function create_general_grooming()
    {
        $reception = Reception::factory()->create(); //Creamos recepcion ficticia para el test

        //establecemos los datos para guaradr la general grooming
        $data = [
            'reception_id' => $reception->id,
            'instructions' => 'Baño y Cepillado exclusivamente',
            'next_service' => '2025-04-20 11:45:00',
            'critic_status' => 0,
            'delivery_service' => 1,
            'delivery_references' => 'Entre dos calles',
        ];

        $response = $this->actingAs($this->user)->post(route('general-groomings.store'), $data);

        $this->assertDatabaseHas('general_groomings', $data); //confirmamos que los datos enviados se encuentren en la tabla
    }

    /** @test */
    public function shows_critic_sign_page()
    {
        $grooming = GeneralGrooming::factory()->create(); //creamos el registro de prueba para llenar el formato

        $response = $this->actingAs($this->user)->get(route('critic.status.sign', $grooming->reception_id)); //con el registro de prueba mandamos llamar la funcion

        $response->assertStatus(200); // La vista se carga correctamente
        $response->assertViewIs('grooming.critic-status'); // Verifica que se usa la vista correcta
        $response->assertViewHas('general'); // La variable $general está disponible
    }

    /** @test */
    public function generates_and_stores_critic_pdf()
    {
        $grooming = GeneralGrooming::factory()->create(); //creamos el registro de prueba para llenar el formato

        $data = [
            'signature' => 'data:image/png;base64,' . base64_encode(UploadedFile::fake()->image('signature.png')->getContent()),
            'name' => 'Test User'
        ]; //generamos el nombre y firma de prueba

        $response = $this->actingAs($this->user)->post(route('critic.status.pdf', $grooming->id), $data); //mandamos los dato a la funcion

        $response->assertStatus(200); // verificamos que la petición se procesa correctamente
        $response->assertJsonStructure(['url']); // comprobamos que nos regresa un url valido

        // // Verifica que el PDF se haya guardado
        $pdfPath = "public/groomings/critic-statuses/responsive_{$grooming->id}.pdf";
        $this->assertTrue(Storage::disk('public')->exists("groomings/critic-statuses/responsive_{$grooming->id}.pdf"));
    }

    /** @test */
    public function generates_pdf_delveryservice()
    {
        $generalgrooming = GeneralGrooming::factory()->create(); //crea un registro de prueba en la tabla de general groomings

        //Mandamos llamar a la ruta que nos genera el PDF usando el registro de prueba
        $response = $this->actingAs($this->user)->get(route('pdf.delivery', $generalgrooming->reception_id));

        $response->assertStatus(200); //comprobamos el status
        $response->assertHeader('content-type', 'application/pdf'); //confirmamos que la respuesta es un PDF
    }

    /** @test */
    public function add_grooming_services()
    {
        $reception = Reception::factory()->create(); //Creamos recepcion ficticia para el test

        //Datos ficticios a guardar en la tabla de grooming
        $data1 = [
            'reception_id' => $reception->id,
            'service_id' => 1670,
        ];
        $data2 = [
            'reception_id' => $reception->id,
            'service_id' => 72820,
        ];

        // Enviamos los primeros datos a la funcion de store
        $response1 = $this->actingAs($this->user)->postJson(route('groomings.store'), $data1);
        $response1->assertStatus(200); // verificamos un status exitoso

        // Enviamos los segundos datos a la funcion de store
        $response2 = $this->actingAs($this->user)->postJson(route('groomings.store'), $data2);
        $response2->assertStatus(200); // verificamos un status exitoso

        // Verificar que tengamos dos registros para la misma reception
        $this->assertEquals(2, Grooming::where('reception_id', $reception->id)->count());
    }

     /** @test */
     public function shows_grooming_reception_sign_page()
     {
        $grooming = GeneralGrooming::factory()->create(); //creamos el registro de prueba para llenar el formato
        //creamos registros de prueba para los servicios de grooming
        $data1 = [
            'reception_id' => $grooming->reception_id,
            'service_id' => 1670,
        ];
        $data2 = [
            'reception_id' => $grooming->reception_id,
            'service_id' => 72820,
        ];
        $this->actingAs($this->user)->postJson(route('groomings.store'), $data1);
        $this->actingAs($this->user)->postJson(route('groomings.store'), $data2);
        //Creamos folio de pago de microsip de prueba
        $data3 = [
            'reception_id' => $grooming->reception_id,
            'folio_odv' => 'N00000171',
        ];
        PaymentOrder::factory()->create($data3);
    

        $response = $this->actingAs($this->user)->get(route('grooming.sign', $grooming->reception_id)); //con el registro de prueba mandamos llamar la funcion

        $response->assertStatus(200); // La vista se carga correctamente
        $response->assertViewIs('grooming.pdf'); // Verifica que se usa la vista correcta

    }

     /** @test */
     public function generates_and_stores_grooming_reception()
     {
        $grooming = GeneralGrooming::factory()->create(); //creamos el registro de prueba para llenar el formato
        //creamos registros de prueba para los servicios de grooming
        $data1 = [
            'reception_id' => $grooming->reception_id,
            'service_id' => 1670,
        ];
        $data2 = [
            'reception_id' => $grooming->reception_id,
            'service_id' => 72820,
        ];
        $this->actingAs($this->user)->postJson(route('groomings.store'), $data1);
        $this->actingAs($this->user)->postJson(route('groomings.store'), $data2);
        //Creamos folio de pago de microsip de prueba
        $data3 = [
            'reception_id' => $grooming->reception_id,
            'folio_odv' => 'N00000171',
        ];
        PaymentOrder::factory()->create($data3);

        //generamos la firma para el pdf
        $data = [
            'signature' => 'data:image/png;base64,' . base64_encode(UploadedFile::fake()->image('signature.png')->getContent()),
        ]; 

        $response = $this->actingAs($this->user)->post(route('grooming.pdf', $grooming->reception_id), $data); //mandamos los datos a la funcion
        $response->assertStatus(200); // verificamos que la petición se procesa correctamente
        $response->assertJsonStructure(['url']); // comprobamos que nos regresa un url valido

        // // Verifica que el PDF se haya guardado
        $pdfPath = "public/groomings/critic-statuses/responsive_{$grooming->reception_id}.pdf";
        $this->assertTrue(Storage::disk('public')->exists("groomings/grooming_{$grooming->reception_id}.pdf"));
     }

    }
