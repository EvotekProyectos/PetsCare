@extends('layouts.app')

@section('template_title')
   CREAR CREMACIÓN
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
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName"
                            :show-weight-actions="true" :reception="$reception" />

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
                                <form method="POST" onsubmit="Cremation(event)" role="form" id="newCremation"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @include('cremation.form')
                                </form>
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
        var Reception_Id = {{ $reception->id }};
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
    </script>
    <script src="{{ asset('js/cremations/create.js') }}" defer></script>
    <script src="{{ asset('js/pet-weights/index.js') }}" defer></script>
@endpush

@push('modals')
    @include('pet-weights.partials.register-modal')
    @include('pet-weights.partials.history-modal')
@endpush
