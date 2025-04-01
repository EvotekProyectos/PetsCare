<?php

namespace Tests\Feature;

use App\Models\AdvancePayment;
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
        $reception = Reception::factory()->create(); //Creamos recepcion ficticia para el test

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
}
