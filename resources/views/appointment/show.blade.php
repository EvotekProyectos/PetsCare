@extends('layouts.app')

@section('template_title')
    CONSULTA
@endsection

@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}?v={{ filemtime(public_path('css/appointment.css')) }}">
@endsection

@section('assignments', 'active border-start border-3 border-primary')
@section('content')
    <section class="container-fluid">
        <div class="row">
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

                         <x-pet-info :pet="$reception->pet" :years="$years" :months="$months" :days="$days"
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName" />

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
