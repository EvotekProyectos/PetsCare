@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Hotel
@endsection

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
                                <span class="icon-park-outline--hotel" style="font-size: 20px; text-align:center;"></span>PENSIÓN
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES MASCOTA
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
                                        {{-- {{ $reception->pet->petClassification->name }} </span> --}}
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
                                <p style="font-weight: bold">No. de collar: <span style="font-weight: normal">
                                        {{ $reception->num }}
                                    </span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES FAMILIA
                            </h5>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Familia: <span style="font-weight: normal">
                                        {{ $reception->family->name }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p> 
                                {{-- style="font-weight: bold">Raza: <span style="font-weight: normal">
                                        {{ $reception->pet->raza }} </span> --}}
                                    </p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">M.V.Z: <span style="font-weight: normal">
                                        {{ $reception->vet->name }} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Teléfono: <span style="font-weight: normal">
                                        {{ $reception->family->phone }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p> 
                                {{-- style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $reception->pet->weight }} </span> --}}
                                    </p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fecha de entrada: <span style="font-weight: normal">
                                        {{ $reception->entry_date }} </span>
                                    </p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Contacto emergencia: <span style="font-weight: normal">
                                        {{ $reception->family->contact_name }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p> 
                                {{-- style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $reception->pet->weight }} </span> --}}
                                    </p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Fecha de salida: <span style="font-weight: normal">
                                        {{ $reception->exit_date }} </span>
                                    </p>
                            </div>
                        </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                     <button class="btn btn-costum-services btn-sm text-uppercase rounded-4"
                                    onclick="window.open('{{ route('vaccine-certificates.show', $reception->pet->id) }}', '_blank')">
                                    <span class="badge custom-badge-pill"><span
                                            class="healthicons--syringe-vaccine"></span></span> CARTILLA VIRTUAL </button>
                                </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-sm text-uppercase rounded-4"
                                    Onclick="window.open('{{ route('pet-history.index', $reception->pet_id) }}', '_blank')">
                                        <span class="badge custom-badge-pill"><span
                                            class="akar-icons--folder-add"></span></span> 
                                                HISTORIAL MÉDICO
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button 
                                        class="btn btn-costum-services btn-sm text-uppercase rounded-4" 
                                        onclick="window.open('{{ route('cubicle.view') }}', '_blank')">
                                        <span class="badge custom-badge-pill">
                                            <span class="game-icons--dog-house "></span>
                                        </span>
                                       DISPONIBILIDAD CUBÍCULOS
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                   
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                            DETALLES DEL SERVICIO
                        </h5>
                    </div>

    
                    <div class="card-body">
                        <form method="POST" onsubmit="NewEntry()"  role="form" 
                        enctype="multipart/form-data" id="NewService">
                            @csrf

                            @include('hotel.form')

                        </form>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Días</th>
                                                <th>No. Cubículo</th>
                                                <th>Precio por día</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <h5 id="total-price">Total  Final: $0.00</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="button" onclick="generate(event)" 
                            class="btn btn-primary">
                FINALIZAR  <i class="fas fa-arrow-right"></i></button>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const type = {{ $reception->reception_type_id }};
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/hotels/create.js') }}" defer></script>
@endpush
