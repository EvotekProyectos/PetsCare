@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Grooming
@endsection

@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
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
                @php
                    $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                    $now = now();

                    $years = $birthday->diffInYears($now);
                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                     $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);

                    $genreName = $reception->pet->genre?->name;
                    $reproductiveStatusName = $reception->pet->reproductiveStatus?->name;
                    $classificationName = $reception->pet->petClassification?->name;
                @endphp

                <div class="appointment-content-wrapper bg-primary-soft">
                    <div class="card-panel card-panel--pet-info">
                        <div class="d-flex justify-content-between align-items-center pb-3 mb-2 ">
                            <div style="font-size: 0.95rem;">
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    Tipo de recepción -
                                </small>
                                <span class="fw-bold" style="color: #0455A0">{{ $reception->receptionType->name }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-secondary">
                                <i class="fas fa-calendar-alt" style="font-size: 12px;"></i>
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    Fecha -
                                </small>

                                <span style="font-size: 13px;">
                                    {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <x-pet-info :pet="$reception->pet" :years="$years" :months="$months" :days="$days"
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName" />

                    </div>
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#specificDataCollapse"
                            aria-expanded="true" aria-controls="specificDataCollapse">
                            <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                SERVICIOS
                                {{-- <i class="fas fa-chevron-down text-primary"></i> --}}
                        </div>

                        <div class="collapse show" id="specificDataCollapse">
                            <div class="mt-2">
                                <div class="row g-3">
                                    <form method="POST" role="form" enctype="multipart/form-data" id="NewGroomingServ"
                                        class="col-md-6">
                                        @csrf

                                        @include('grooming.form')

                                    </form>

                                    <form method="POST" role="form" enctype="multipart/form-data" id="NewVaccineServ"
                                        class="col-md-6">
                                        @csrf

                                        @include('grooming.form-vaccine')

                                    </form>
                                </div>

                                <div class="col-12 mt-2 d-flex justify-content-end">
                                    <button type="button" onclick="Add()" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Agregar </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-flat-rows responsive w-100" id="table">
                                            <thead class="thead table-header-solid text-uppercase">
                                                <tr>
                                                    <th>Servicio</th>
                                                    {{-- <th>Notas</th> --}}
                                                    <th>Precio</th>
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
                        <div class="d-flex justify-content-end mt-2">
                            <div id="total-price"
                                style="
                                color: #0455a0;
                                font-weight: 700;
                                font-size: 1rem;
                                background: #d3f0f3;
                                border: 1.5px solid #0455a0;
                                padding: 8px 24px;
                                border-radius: 999px;
                                white-space: nowrap;
                            ">
                                Total: $0.00</div>
                        </div>
                    </div>
                    <div class="card-panel">

                        <div class="card-body">
                            <form method="POST" role="form" enctype="multipart/form-data" id="NewGrooming">
                                @csrf

                                @include('general-grooming.form')

                            </form>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Registrar: barra fija al fondo del viewport, mismo componente que
         Finalizar Consulta (Consulta) y Dar alta (Hospitalización). --}}
    <div class="finalize-sticky-bar">
        <button type="button" class="btn btn-finalizar-consulta btn-sm text-uppercase rounded-4 px-3"
            onclick="generate(event)">
            <i class="bi bi-check-lg"></i> Siguiente
        </button>
    </div>
@endsection

@push('scripts')
    <script>
        const type = {{ $reception->reception_type_id }};
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/groomings/create.js') }}" defer></script>
    <script src="{{ asset('js/receptions/transfer.js') }}" defer></script>
@endpush

@push('modals')
    @include('reception.partials.transfer-modal')
@endpush
