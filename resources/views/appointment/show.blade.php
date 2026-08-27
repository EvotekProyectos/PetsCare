@extends('layouts.app')

@section('template_title')
    CONSULTA
@endsection

@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}?v={{ filemtime(public_path('css/appointment.css')) }}">
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                @php
                    $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                    $now = now();

                    $years = $birthday->diffInYears($now);
                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);

                    $genreName = $reception->pet->genre?->name;
                    $reproductiveStatusName = $reception->pet->reproductiveStatus?->name;
                    $classificationName = $reception->pet->petClassification?->name;
                @endphp

                <div class="appointment-content-wrapper bg-primary-soft">

                    <div class="card-panel card-panel--pet-info">
                        <div class="reception-summary">
                            <div class="reception-summary-item">
                                <div class="reception-summary-label">
                                    Tipo de consulta
                                </div>

                                <div class="reception-summary-value">
                                    {{ $reception->reason->name }}
                                </div>
                            </div>

                            <div class="reception-summary-item reception-summary-date">
                                <div class="reception-summary-label">
                                    <i class="fas fa-calendar-alt reception-calendar-icon"></i>
                                    Fecha
                                </div>

                                <div class="reception-summary-value">
                                    {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-start mt-2">

                            {{-- Foto y nombre de la mascota --}}
                            <div class="col-md-2 d-flex justify-content-center pt-2">
                                <div class="form-group text-center">
                                    <img src="{{ asset('img/pet_pic.png') }}" alt="Foto Mascota" id="preview"
                                        class="img-fixed"
                                        style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;">

                                    <h5 class="mt-2 mb-1">
                                        {{ $reception->pet->name }}
                                    </h5>

                                    <span class="badge rounded-pill px-3 py-1"
                                        style="background-color: #E6F1FB; color: #0455A0; font-weight: 500;">
                                        Paciente
                                    </span>
                                </div>
                            </div>


                            {{-- Información de la mascota --}}
                            <div class="col-md-8">
                                <div class="collapse show pt-3" id="petInfoCollapse">

                                    <div class="row g-3">

                                        {{-- Especie --}}
                                        <div class="col-md-3 col-6  pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-paw pet-info-icon"></i>Especie
                                            </small>

                                            <div class="{{ $reception->pet->specie ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $reception->pet->specie ?: 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Raza --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-tag pet-info-icon"></i>Raza
                                            </small>

                                            <div class="{{ $reception->pet->raza ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $reception->pet->raza ?: 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Género --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-venus-mars pet-info-icon"></i>Género
                                            </small>

                                            <div class="{{ $genreName ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $genreName ?? 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Edad --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-birthday-cake pet-info-icon"></i>Edad
                                            </small>

                                            <div class="fw-semibold">
                                                {{ $years }} años {{ $months }} meses
                                            </div>
                                        </div>


                                        {{-- Peso --}}
                                        <div class="col-md-3 col-6  pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-weight pet-info-icon"></i>Peso
                                            </small>

                                            <div class="{{ $reception->pet->weight ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $reception->pet->weight ? $reception->pet->weight . ' kg' : 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Estado reproductivo --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-notes-medical pet-info-icon"></i>Estado reproductivo
                                            </small>

                                            <div class="{{ $reproductiveStatusName ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $reproductiveStatusName ?? 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Clasificación --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-list pet-info-icon"></i>Clasificación
                                            </small>

                                            <div class="{{ $classificationName ? 'fw-semibold' : 'text-muted' }}">
                                                {{ $classificationName ?? 'No definido' }}
                                            </div>
                                        </div>

                                        {{-- Fallecido --}}
                                        <div class="col-md-3 col-6 pet-info-col">
                                            <small class="text-muted">
                                                <i class="fas fa-heartbeat pet-info-icon"></i>Fallecido
                                            </small>

                                            <div class="fw-semibold">
                                                {{ $reception->pet->deceased ? 'Sí' : 'No' }}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            {{-- Descripción física --}}
                            <div class="col-md-2" style="margin-left: -30px;">
                                <div class="pet-description-card">

                                    <div class="pet-description-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>

                                    <div class="pet-description-title">
                                        Descripción física
                                    </div>

                                    <div class="pet-description-text">
                                        {{ $reception->pet->physic_descrip ?: 'Sin descripción registrada.' }}
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- Botones de acción: solo Historial médico en esta vista de
                             solo lectura (Trasladar/Presupuestos son acciones de
                             captura, no aplican aquí). --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">
                                    <button type="button" class="action-link"
                                        onclick="window.open('{{ route('pet-history.index', ['id' => $reception->pet_id, 'type' => 1]) }}', '_blank')">
                                        <i class="fas fa-notes-medical"></i>
                                        <span>Historial médico</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Registro clínico (solo lectura) --}}
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#specificDataCollapse"
                            aria-expanded="true" aria-controls="specificDataCollapse">
                            <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                Registro clínico
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="specificDataCollapse">
                            <div class="mt-2">
                                @include('appointment.form-readonly')
                            </div>
                        </div>
                    </div>

                    {{-- Servicios (solo lectura: solo la tabla, sin botones de captura) --}}
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#servicesCollapse"
                            aria-expanded="true" aria-controls="servicesCollapse">
                            <div class="d-flex align-items-center gap-2">
                                <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                    SERVICIOS
                                </h5>
                            </div>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="servicesCollapse">
                            <div class="mt-2">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="table-responsive service-table-wrapper">
                                            <table class="table table-hover table-flat-rows responsive w-100"
                                                id="DataServices">
                                                <thead class="text-uppercase">
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Nombre</th>
                                                        <th>Observaciones</th>
                                                        <th>M.V.Z.</th>
                                                        <th>Precio</th>
                                                        <th>Vale</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fórmula médica (solo lectura) --}}
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#prescriptionCollapse"
                            aria-expanded="true" aria-controls="prescriptionCollapse">
                            <div class="d-flex align-items-center gap-2">
                                <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                    FÓRMULA MÉDICA
                                </h5>
                                <i class="fas fa-chevron-down text-primary"></i>
                            </div>
                        </div>

                        <div class="collapse show" id="prescriptionCollapse">
                            <div class="mt-2">
                                @include('prescription.form-readonly')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
@endsection

@push('scripts')
    <script>
        var Reception_Id = {{ $reception->id }};
    </script>
    <script src="{{ asset('js/appointments/show.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['#specificDataCollapse', '#servicesCollapse', '#prescriptionCollapse'].forEach(function(selector) {
                const collapseEl = document.querySelector(selector);
                const cardPanel = collapseEl?.closest('.card-panel');
                if (!collapseEl || !cardPanel) return;

                collapseEl.addEventListener('shown.bs.collapse', function() {
                    cardPanel.classList.add('no-shadow');
                });
                collapseEl.addEventListener('hidden.bs.collapse', function() {
                    cardPanel.classList.remove('no-shadow');
                });

                if (collapseEl.classList.contains('show')) {
                    cardPanel.classList.add('no-shadow');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .chevron-toggle i {
            transition: transform 0.2s ease-in-out;
        }

        .chevron-toggle[aria-expanded="false"] i {
            transform: rotate(180deg);
        }
    </style>
@endpush
