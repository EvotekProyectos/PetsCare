@extends('layouts.app')

@section('template_title')
    CREAR HOTEL
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/hotel/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
@endpush

@section('content')
    <input type="hidden" id="reception_id_followup" value="{{ $reception->id }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                                    Fecha entrada-
                                </small>

                                <span style="font-size: 13px;">
                                    {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-secondary">
                                <i class="fas fa-calendar-alt" style="font-size: 12px;"></i>
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    Fecha salida-
                                </small>

                                <span style="font-size: 13px;">
                                    {{ \Carbon\Carbon::parse($reception->exit_date)->format('d/m/Y') }}
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
                                DETALLES DEL SERVICIO
                                {{-- <i class="fas fa-chevron-down text-primary"></i> --}}
                        </div>

                        <div class="collapse show" id="specificDataCollapse">
                            <div class="mt-2">
                                <form method="POST" onsubmit="NewEntry()" role="form" enctype="multipart/form-data"
                                    id="NewService">
                                    @csrf

                                    @include('hotel.form')

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-panel">
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

                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 id="total-price" class="mb-0" style="color: #0455A0;">Total Final: $0.00</h5>
                            <button type="button" onclick="generate(event)"
                                class="btn btn-primary btn-sm text-uppercase rounded-4">
                                <i class="fas fa-arrow-right"></i> Finalizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const type = {{ $reception->reception_type_id }};
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/hotels/create.js') }}" defer></script>
    <script src="{{ asset('js/receptions/transfer.js') }}" defer></script>
@endpush

@push('modals')
    @include('reception.partials.transfer-modal')
@endpush
