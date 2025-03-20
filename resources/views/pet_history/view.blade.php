@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} PetHistory
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p>{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> MASCOTA
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
                                <h5>{{ $pet->name }}</h5>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Especie: <span style="font-weight: normal">
                                        {{ $pet->specie }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Raza: <span style="font-weight: normal">
                                        {{ $pet->raza }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Género: <span style="font-weight: normal">
                                        {{ $pet->genre->name }} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center" style="margin-bottom: -11px;">
                            <div class="col-md-3">
                                <p style="font-weight: bold">Descripción física: <span style="font-weight: normal">
                                        {{ $pet->physic_descrip }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Peso: <span style="font-weight: normal">
                                        {{ $pet->weight }} </span></p>
                            </div>
                            <div class="col-md-3">
                                <p style="font-weight: bold">Clasificación: <span style="font-weight: normal">
                                    {{ $pet->petClassification?->name ?? '' }} </span></p>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3">
                                <p style="font-weight: bold">E. Reproductivo: <span style="font-weight: normal">
                                        {{ $pet->reproductiveStatus->name }} </span></p>
                            </div>
                            @php
                                $birthday = \Carbon\Carbon::parse($pet->birthday);
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
                                        {{ $pet->deceased == 1 ? 'Sí' : 'No' }}
                                    </span></p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                                    <span class="map--veterinary-care"></span> PRODUCTOS/SERVICIOS
                                </h5>
                            </div>
                            <div class="row d-flex justify-content-around align-content-center mb-3">
                                <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                    <div class="col">
                                        <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                            href="{{ route('vaccine-certificates.show', $pet->id) }}">
                                            <span class="badge custom-badge-pill"><span
                                                    class="healthicons--syringe-vaccine"></span> </span> <small>
                                                CARTILLA Virtual</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                    <div class="col">
                                        <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                            href="{{ route('prescription.create', $pet->id) }}">
                                            <span class="badge custom-badge-pill"><span
                                                    class="material-symbols--prescriptions-outline "></span> </span> <small>
                                                FÓRMULA MÉDICA </small></a>
                                    </div>

                                </div>
                                <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                    <div class="col">
                                        <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                            href='{{ route('formats.created', $pet->id) }}'">
                                            <span class="badge custom-badge-pill"><span
                                                    class="solar--document-add-broken " style="font-size: 23px;"></span> </span> <small>
                                                FORMATOS </small></a>
                                    </div>
                                    {{-- <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button  class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="window.location.href='{{ route('prescription.create', $pet->id) }}'" >
                                        <span class="badge custom-badge-pill"><span class="material-symbols--prescriptions-outline "></span> </span> FÓRMULA MÉDICA </button>
                                </div>
                                
                             </div>
                             <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="window.location.href='{{ route('formats.created', $pet->id) }}'">
                                        <span class="badge custom-badge-pill"><span class="material-symbols--prescriptions-outline"></span></span> FORMATOS
                                    </button>
                                </div>
                                
                                
                                
                            {{--<div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button  class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="" >
                                        <span class="badge custom-badge-pill"><span class="hugeicons--x-ray"></span></span> IMÁGENES DIAGNÓSTICAS </button>
                                </div>
                            </div> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class="text-primary text-uppercase">
                                        <span class="ic--twotone-pets"></span> historial clínico
                                    </h5>
                                </div>


                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover responsive w-100" id="table">
                                            <thead class="thead table-primary text-uppercase">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>M.V.Z</th>
                                                    <th>Recepción</th>
                                                    {{-- <th>Tipo</th> --}}
                                                    <th>Detalles</th>
                                                </tr>
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
@endsection

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pic_id = {{ $pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $pet->file->route ?? '' }}";
        var Pet_Id = {{ $pet->id }};
    </script>
    <script src="{{ asset('js/pet-history/view.js') }}" defer></script>
@endpush
