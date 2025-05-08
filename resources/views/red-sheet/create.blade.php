@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Red Sheet
@endsection
@push('styles')

<link rel="stylesheet" href="{{ asset('css/redsheets/form.css') }}">
@endpush


@section('content')
<input type="hidden" id="reception_id_followup" value="{{ $reception->id }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ri--hospital-lineB"></span> HOSPITALIZACIÓN
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
                                    {{ $reception->pet->petClassification?->name ?? ''}}
                                    </p>
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
                                    onclick="discharge( {{ $reception->id }})">
                                    <span class="badge custom-badge-pill"><span class="mynaui--inbox-up"></span></span>
                                    Dar Alta
                                </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="Transfer()">
                                        <span class="badge custom-badge-pill"><span
                                                class="clarity--two-way-arrows-line"></span></span> Trasladar
                                    </button>
                                </div>
                            </div>
                            {{-- <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button 
                                        class="btn btn-costum-services btn-sm text-uppercase rounded-4" 
                                        onclick="ButtonDeath({{ $reception->id }})">
                                        <span class="badge custom-badge-pill">
                                            <span class="ph--cross-duotone"></span>
                                        </span>
                                        Falleció
                                    </button>
                                </div>
                            </div> --}}
                            
                        </div>
                    </div>
                    @php
                        use Carbon\Carbon;
                        $entryDate = Carbon::parse($reception->entry_date)->format('Y-m-d 00:00:00');
                        $today = Carbon::now()->format('Y-m-d 00:00:00');
                        $dayCount = $entryDate <= $today ? Carbon::parse($entryDate)->diffInDays($today) + 1 : 1;
                    @endphp
                    <div class="row card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                 DÍA {{ $dayCount }}
                            </h5>
                        </div>
                        <form method="POST" onsubmit="NewEntry()" role="form" enctype="multipart/form-data"
                            id="NewRedSheet">
                            @csrf

                            @include('red-sheet.form')

                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="OpenSurgeries()">
                                        <span class="badge custom-badge-pill"><span
                                                class="healthicons--surgical-sterilization-outline"></span></span> PROCEDIMIENTOS
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                    onclick="window.open('{{ route('hospitalization.followups', $reception->id) }}', '_blank')">
                                        <span class="badge custom-badge-pill"><span
                                                class="clarity--note-edit-line"></span></span> Seguimientos
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="window.open('{{ route('pet-history.index', $reception->pet_id) }}', '_blank')">
                                        <span class="badge custom-badge-pill"><span
                                                class="akar-icons--folder-add"></span></span> Historial Clínico </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="DisplayRedSheet">
                        <h5 id="card_title" class="text-primary text-uppercase">
                            <span class="ic--twotone-pets"></span> RESUMEN DÍAS HOSPITALIZADO
                        </h5>
                        <div id="table-container"></div>
                    </div>
                    
                    {{-- <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> Cirugias
                            </h5>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover responsive w-100" id="surgeries">
                                    <thead class="thead table-primary text-uppercase">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Nombre</th>
                                            <th>Observaciones</th>
                                            <th>M.V.Z.</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>



    <div class="modal" id="ModalSurgeries" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                <div class="modal-header">
                    <div class="col-11 d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                            <span class="healthicons--surgical-sterilization-outline"></span> CIRUGÍAS
                        </h5>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn-close" onclick="CloseSurgeries()" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" onsubmit="AddSurgery()" id="NewSurgery" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @include('surgery.form')
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="CloseSurgeries()">
                        Cerrar
                    </button>

                </div> --}}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var admisiones = @json($admissions);
        var altas = @json($discharges);
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Reception_Id = {{ $reception->id }};
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";

        document.getElementById("reception_id_followup").value = Reception_Id;
    </script>
    
    <script src="{{ asset('js/hospitalizations/createredsheets.js') }}" defer></script>
@endpush
