@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Appointment
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
                                <p style="font-weight: bold">Tipo: <span style="font-weight: normal">
                                        {{ $reception->reason->name }} </span></p>
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
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS ESPECÍFICOS
                            </h5>
                        </div>
                        <form method="POST" action="{{ route('appointments.store') }}" role="form" id="NewAppointment"
                            enctype="multipart/form-data">
                            @csrf

                            @include('appointment.form')

                        </form>
                    </div>
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="material-symbols--prescriptions-outline "></span> FÓRMULA MÉDICA
                            </h4>
                        </div>
                        <form method="POST" onsubmit="AddPrescription()" role="form" id="NewPrescription"
                            enctype="multipart/form-data">
                            @csrf

                            @include('prescription.form')

                        </form>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                                <span class="map--veterinary-care"></span> PRODUCTOS/SERVICIOS
                            </h5>
                        </div>
                        <div class="row">
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="OpenCarnet()">
                                        <span class="badge custom-badge-pill"><span
                                                class="healthicons--syringe-vaccine"></span></span> CARTILLA Virtual
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="OpenLabs()">
                                        <span class="badge custom-badge-pill"><span
                                                class="hugeicons--chemistry-02"></span></span> EXÁMENES DE GABINETE
                                    </button>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4"
                                        onclick="OpenImgs()">
                                        <span class="badge custom-badge-pill"><span
                                                class="hugeicons--x-ray"></span></span> IMÁGENES DIAGNÓSTICAS </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <button class="btn btn-primary btn-lg text-uppercase rounded-4" onclick="EndAppointment()">
                            Finalizar Consulta <i class="fas fa-file-medical fa-lg"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> Registros
                            </h5>
                        </div>
                        <div class="row">
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover responsive w-100" id="DataLabs">
                                    <thead class="thead table-primary text-uppercase">
                                        <tr>
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
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover responsive w-100" id="DataImgs">
                                    <thead class="thead table-primary text-uppercase">
                                        <tr>
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
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
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
                                            <th>Tipo</th>
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
    <div class="modal" id="ModalLab" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                <div class="modal-header">
                    <div class="col-11 d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                            <span class="hugeicons--chemistry-02"></span> Exámenes de Gabinete
                        </h5>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn-close" onclick="closeModalLabs()" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" onsubmit="AddLabs()" id="NewLab" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @include('appointment-service.formlab')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="ModalImg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                <div class="modal-header">
                    <div class="col-11 d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                            <span class="hugeicons--x-ray"></span> Imágenes Diagnósticas
                        </h5>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn-close" onclick="closeModalImgs()" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" onsubmit="AddImgs()" id="NewImg" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @include('appointment-service.form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="ModalCertificate" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #e9eced; border-radius: 20px;">
                <div class="modal-header">
                    <div class="col-11 d-flex justify-content-between align-items-center">
                        <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                            <span class="map--veterinary-care"></span> VACUNAS Y DESPARACITACIONES
                        </h5>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-3">
                            <button class="btn btn-costum-services btn-sm text-uppercase rounded-4"
                                onclick="window.location.href='{{ route('vaccine-certificates.show', $reception->pet_id) }}'">
                                Ver Cartilla
                            </button>
                        </div>
                        <div class="col-1"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @include('vaccine-certificate.form')
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
    <script src="{{ asset('js/appointments/create.js') }}" defer></script>
@endpush
