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

                        {{-- Botones de acción: fila propia a ancho completo --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">
                                    <button type="button" class="action-link"
                                        onclick="openTransferModal({{ $reception->id }}, 1)">
                                        <i class="fas fa-exchange-alt"></i>
                                        <span>Trasladar</span>
                                    </button>
                                    {{-- 
                                    <button type="button" class="action-link" onclick="openBudgetModal()">
                                        <i class="fas fa-money-check-alt"></i>
                                        <span>Presupuestos</span>
                                    </button> --}}

                                    <button type="button" class="action-link"
                                        onclick="window.open('{{ route('pet-history.index', ['id' => $reception->pet_id, 'type' => 1]) }}', '_blank')">
                                        <i class="fas fa-notes-medical"></i>
                                        <span>Historial médico</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Registro clínico --}}
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
                                <form method="POST" action="{{ route('appointments.store') }}" role="form"
                                    id="NewAppointment" enctype="multipart/form-data">
                                    @csrf
                                    @include('appointment.form')
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Servicios --}}
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
                                <div class="row g-3">
                                    <div class="col-md-4 col-12">
                                        <div class="service-card" onclick="OpenCarnet()">
                                            <div class="service-card-icon"><span
                                                    class="healthicons--syringe-vaccine"></span></div>
                                            <div class="service-card-text">
                                                <span class="fw-bold text-uppercase">CARTILLA Virtual</span>
                                                <span class="service-card-desc">Registra vacunas y desparasitaciones</span>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="service-card" onclick="OpenLabs()">
                                            <div class="service-card-icon"><span class="hugeicons--chemistry-02"></span>
                                            </div>
                                            <div class="service-card-text">
                                                <span class="fw-bold text-uppercase">EXÁMENES DE GABINETE</span>
                                                <span class="service-card-desc">Solicita estudios de laboratorio</span>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="service-card" onclick="OpenImgs()">
                                            <div class="service-card-icon"><span class="hugeicons--x-ray"></span></div>
                                            <div class="service-card-text">
                                                <span class="fw-bold text-uppercase">IMÁGENES DIAGNÓSTICAS</span>
                                                <span class="service-card-desc">Solicita estudios de imagen</span>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>

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
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 d-flex justify-content-end">
                                    <button type="button" id="btnGenerarValeAppointment"
                                        class="action-link action-link--success btn-icon-circle" style="display: none;"
                                        title="Generar vale">
                                        <span class="heroicons-outline--ticket"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fórmula médica --}}
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#prescriptionCollapse"
                            aria-expanded="true" aria-controls="prescriptionCollapse">
                            <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                FÓRMULA MÉDICA
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="prescriptionCollapse">
                            <div class="mt-2">
                                <form method="POST" onsubmit="AddPrescription()" role="form" id="NewPrescription"
                                    enctype="multipart/form-data">
                                    @csrf

                                    @include('prescription.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>

    {{-- Finalizar Consulta: barra fija al fondo del viewport, siempre visible
         sin importar el scroll ni el estado de colapso de ninguna card. --}}
    <div class="finalize-sticky-bar">
        <button type="button" class="btn btn-finalizar-consulta btn-sm text-uppercase rounded-4 px-3"
            onclick="EndAppointment();">
            Finalizar Consulta <i class="fas fa-file-medical ms-1"></i>
        </button>
    </div>

    <div class="modal fade" id="ModalLab" tabindex="-1" aria-labelledby="ModalLabTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                        <div>
                            <div class="fw-bold fs-4 text-dark" id="ModalLabTitle">Exámenes de Gabinete</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="closeModalLabs()" aria-label="Close"></button>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        onclick="closeModalLabs()">Cancelar</button>
                    <button type="submit" form="NewLab" class="btn btn-primary btn-sm">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalImg" tabindex="-1" aria-labelledby="ModalImgTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                        <div>
                            <div class="fw-bold fs-4 text-dark" id="ModalImgTitle">Imágenes Diagnósticas</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="closeModalImgs()" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="width: 100%;">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" onsubmit="AddImgs()" id="NewImg" role="form"
                                enctype="multipart/form-data">
                                @csrf
                                @include('appointment-service.form', ['hideSubmit' => true])
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        onclick="closeModalImgs()">Cancelar</button>
                    <button type="submit" form="NewImg" class="btn btn-primary btn-sm">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCertificate" tabindex="-1" aria-labelledby="ModalCertificateTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 950px;">
            <div class="modal-content">

                <div class="modal-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                        <div>
                            <div class="fw-bold fs-4 text-dark" id="ModalCertificateTitle">
                                Vacunas y Desparasitaciones
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 ms-auto">

                        <button type="button" class="btn btn-outline-primary btn-sm rounded-4 px-3 py-1"
                            onclick="window.open('{{ route('vaccine-certificates.show', $reception->pet_id) }}', '_blank')">

                            <i class="fas fa-file-medical"></i>
                            Ver Cartilla

                        </button>


                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                        </button>

                    </div>
                </div>

                <div class="modal-body" style="width: 100%;">
                    @include('vaccine-certificate.form')
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
        var Reason_Id = {{ $reception->reason_id }};
        var Pet_Id = {{ $reception->pet_id }};
        var vet_id = {{ $reception->veterinarian_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
        var services = @json($products);
    </script>
    <script src="{{ asset('js/appointments/create.js') }}" defer></script>
    <script src="{{ asset('js/appointments/draft.js') }}" defer></script>
    <script src="{{ asset('js/receptions/transfer.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/detail-modal.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/sign-modal.js') }}" defer></script>
    <script src="{{ asset('js/budgets/appointment-modal.js') }}" defer></script>
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

@push('modals')
    @include('reception.partials.transfer-modal')
    @include('voucher.partials.detail-modal')
    @include('voucher.partials.sign-modal')
    @include('budget.partials.modal')
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
