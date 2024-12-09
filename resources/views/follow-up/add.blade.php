@extends('layouts.app')

@section('template_title')
    {{ __('Add') }} Follow Ups
@endsection

@section('content')
<input type="hidden" id="reception_id_followup" value="{{ $reception->id }}">

    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="clarity--note-edit-line"></span> PASE DE GUARDIA
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES DE LA MASCOTA
                            </h5>
                        </div>
                        <div class="col-md-4 d-flex justify-content-left">
                            <div class="form-group text-center">
                                <img src="{{ asset('img/pet_pic.png') }}" alt="Foto Mascota" id="preview"
                                    class="img-fixed"
                                    style="width: 115px; height: 115px; object-fit: cover; border-radius: 70px; ">
                                <h5>{{ $reception->pet->name }}</h5>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Especie: <span style="font-weight: normal">
                                        {{ $reception->pet->specie }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Raza: <span style="font-weight: normal">
                                        {{ $reception->pet->raza }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Género: <span style="font-weight: normal">
                                        {{ $reception->pet->genre->name }} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Descripción física: <span style="font-weight: normal">
                                        {{ $reception->pet->physic_descrip }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $reception->pet->weight }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Clasificación: <span style="font-weight: normal">
                                        {{ $reception->pet->petClassification->name }} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3">
                                <p style="font-weight: bold">E. Reproductivo: <span style="font-weight: normal">
                                        {{ $reception->pet->reproductiveStatus->name }} </span></p>
                            </div>
                            @php
                                $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                                $now = \Carbon\Carbon::now();

                                $years = $birthday->diffInYears($now);
                                $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                                $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                            @endphp
                            <div class="col-md-3">
                                <p style="font-weight: bold">Edad: <span style="font-weight: normal">
                                        {{ $years }} años, {{ $months }} meses, y {{ $days }} días
                                    </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fallecido: <span style="font-weight: normal">
                                        {{ $reception->pet->deceased == 1 ? 'Sí' : 'No' }}
                                    </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center mt-2" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Admisión: <span style="font-weight: normal">
                                        {{ $reception->admissionType->name }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center " style="margin-bottom: -11px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Area: <span style="font-weight: normal">
                                        {{ $reception->area->name }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center ">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fecha: <span style="font-weight: normal">
                                        {{ $reception->entry_date }} </span></p>
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="#">
                                        <span class="badge custom-badge-pill"><span class="solar--shield-check-linear"></span></span>
                                        Internos
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="#">
                                        <span class="badge custom-badge-pill"><span class="healthicons--surgical-sterilization-outline"></span></span>
                                        Quirurgicos
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="OpenCritics()">
                                        <span class="badge custom-badge-pill"><span class="fluent--important-16-filled"></span></span>
                                        Criticos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> Criticos
                            </h5>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover responsive w-100" id="critics_table">
                                    <thead class="thead table-primary text-uppercase">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Presiones</th>
                                            <th>Temperatura</th>
                                            <th>Glicemias</th>
                                            <th>Vomitos</th>
                                            <th>Defeco</th>
                                            <th>Orino</th>
                                            <th>Comio</th>
                                            <th>Infusiones</th>
                                            <th>Terapeutica</th>
                                            <th>Pendientes</th>
                                            <th>Imagenes</th>
                                            <th>M.V.Z.</th>
                                            <th>Acciones</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

  
    <div class="modal" id="ModalCritics" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                <div class="modal-header">
                    <div class="col-11 d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                            <span class="clarity--note-edit-line"></span> Pase de Guardia de Criticos
                        </h5>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn-close" onclick="CloseCritics()" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" onsubmit="AddCritics()" id="NewCritic" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @include('followups-critic.form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
@endsection

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Reception_Id = {{ $reception->id }};
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";

    </script>
    <script src="{{ asset('js/followups/add.js') }}" defer></script>
@endpush
