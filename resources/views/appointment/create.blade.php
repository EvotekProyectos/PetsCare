@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Appointment
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> CONSULTA 
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                 DATOS GENERALES
                            </h5>
                        </div>
                        <div class="col-md-4 d-flex justify-content-left">
                            <div class="form-group text-center">
                                    <img src="{{ asset('img/pet_pic.png') }}" alt="Foto Mascota"
                                         id="preview" class="img-fixed" style="width: 115px; height: 115px; object-fit: cover; border-radius: 70px; ">
                                <label class="form-label">Pet Name</label>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Especie: <span style="font-weight: normal"> Pet Specie </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Raza: <span style="font-weight: normal"> Pet Raza </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Género: <span style="font-weight: normal"> Pet gender </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Descripción física: <span style="font-weight: normal"> Pet physic_descrip </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Peso: <span style="font-weight: normal"> Pet weight </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Clasificación: <span style="font-weight: normal"> Pet Classification </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3">
                                <p style="font-weight: bold">E. Reproductivo: <span style="font-weight: normal"> Pet Rep Status </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Edad: <span style="font-weight: normal"> Pet Age </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fallecido: <span style="font-weight: normal"> Pet Deceasedn </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center mt-2" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Tipo: <span style="font-weight: normal"> Reason </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center ">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fecha: <span style="font-weight: normal"> DATE:TIME </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                 DATOS ESPECÍFICOS
                            </h5>
                        </div>
                        <form method="POST" action="{{ route('appointments.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('appointment.form')

                        </form>
                    </div>
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                 RECETA MÉDICA
                            </h5>
                        </div>
                        {{-- <form method="POST" action="{{ route('appointments.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('appointment.form')

                        </form> --}}
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #007c84">
                                 PRODUCTOS/SERVICIOS
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class="text-primary text-uppercase" >
                                <span class="ic--twotone-pets"></span> historial clínico
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
