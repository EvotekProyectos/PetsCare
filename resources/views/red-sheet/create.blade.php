@extends('layouts.app')

@section('template_title')
    HOSPITALIZACIÓN
@endsection
@section('design')
    {{-- ?v=filemtime evita que el navegador sirva una copia cacheada de este
         CSS tras cada cambio (asset() no versiona por sí solo). --}}
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}?v={{ filemtime(public_path('css/appointment.css')) }}">
    {{-- Estilos de los toggles Sí/No (radios ocultos + .radio-label) usados por
         los formularios de Seguimientos (followup-intern/followups-critic/
         followup-surgical): antes solo se cargaban en sus create/edit standalone,
         no en el flujo de modal. --}}
    <link rel="stylesheet" href="{{ asset('css/followups/form.css') }}">
    {{-- Acordeón por día + bitácora timeline de "Resumen días hospitalizado". --}}
    <link rel="stylesheet" href="{{ asset('css/redsheets/timeline.css') }}">
    {{-- Anti-FOUC: los 3 selects múltiples de "Registro clínico" viven
         visibles en el contenido (no dentro de un modal oculto como en otras
         pantallas), así que se ven como listbox nativo grande antes de que
         Select2 los inicialice en $(document).ready(). Select2 marca el
         <select> original con select2-hidden-accessible una vez montado —
         mientras no la tenga, se oculta sin reservar espacio. --}}
    <style>
        #service_type_id:not(.select2-hidden-accessible),
        #lab_type_id:not(.select2-hidden-accessible),
        #imaging_type_id:not(.select2-hidden-accessible) {
            visibility: hidden;
            position: absolute;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" id="reception_id_followup" value="{{ $reception->id }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

                    {{-- Header: breadcrumb + fecha --}}


                    {{-- Card de mascota: avatar + info + acciones --}}
                    <div class="card-panel card-panel--pet-info">
                        <div class="d-flex justify-content-between align-items-center pb-3 mb-2 ">
                            <div style="font-size: 0.95rem;">
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    <i class="fas fa-door-open pet-info-icon"></i>Admisión -
                                </small>
                                <span class="fw-bold"
                                    style="color: #0455A0">{{ $reception->admissionType?->name ?? 'Sin definir' }}</span>
                            </div>
                            <div class="eyebrow-col" style="font-size: 0.95rem;">
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    <i class="fas fa-map-marker-alt pet-info-icon"></i>Área -
                                </small>
                                <span class="fw-bold"
                                    style="color: #0455A0">{{ $reception->area?->name ?? 'Sin definir' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-secondary eyebrow-col">
                                <small class="text-uppercase fw-semibold" style="font-size: 0.75rem; color: #000000;">
                                    <i class="fas fa-calendar-alt pet-info-icon"></i>Fecha -
                                </small>

                                <span style="font-size: 13px;">
                                    {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                         <x-pet-info :pet="$reception->pet" :years="$years" :months="$months" :days="$days"
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName" />

                        {{-- Botones de acción: fila propia a ancho completo --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">
                                    <button type="button" class="action-link"
                                        onclick="openTransferModal({{ $reception->id }}, {{ $reception->reception_type_id }})">

                                        <i class="fas fa-exchange-alt"></i>

                                        <span>Trasladar</span>

                                    </button>

                                    <button type="button" class="action-link"  onclick="Transfer({{ $reception->admission_type_id }})">
                                        <i class="fas fa-sync-alt"></i>
                                        <span>Cambiar admisión</span>
                                    </button>

                                    <button type="button" class="action-link"
                                        onclick="window.open('{{ route('pet-history.index', ['id' => $reception->pet_id, 'type' => 2]) }}', '_blank')">
                                        <i class="fas fa-notes-medical"></i>
                                        <span>Historial médico</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    @php
                        use Carbon\Carbon;
                        $entryDate = Carbon::parse($reception->entry_date)->format('Y-m-d 00:00:00');
                        $today = Carbon::now()->format('Y-m-d 00:00:00');
                        $dayCount = $entryDate <= $today ? Carbon::parse($entryDate)->diffInDays($today) + 1 : 1;
                    @endphp
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
                                <form method="POST" onsubmit="NewEntry()" role="form" enctype="multipart/form-data"
                                    id="NewRedSheet">
                                    @csrf

                                    @include('red-sheet.form')

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#specificDataCollapse"
                            aria-expanded="true" aria-controls="specificDataCollapse">
                            <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                Procedimientos
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="specificDataCollapse">
                            <div class="card-body">
                                <div class="row g-3 justify-content-start">
                                    <div class="col-md-4 col-12">
                                        <div class="service-card" onclick="OpenSurgeries()">
                                            <div class="service-card-icon"><span
                                                    class="healthicons--surgical-sterilization-outline"></span></div>
                                            <div class="service-card-text">
                                                <span class="fw-bold text-uppercase">Procedimientos</span>
                                                <span class="service-card-desc">Registra cirugías y procedimientos</span>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="service-card" onclick="OpenFollowUps()">
                                            <div class="service-card-icon"><span class="clarity--note-edit-line"></span>
                                            </div>
                                            <div class="service-card-text">
                                                <span class="fw-bold text-uppercase">Seguimientos</span>
                                                <span class="service-card-desc">Registra la evolución del paciente</span>
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-body" id="DisplayRedSheet">

                            <div id="table-loader" style="display:none; text-align:center; padding: 2rem;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando información...</p>
                            </div>
                            <div id="table-container"></div>
                        </div>
                    </div>
                </div>




                <div class="card-body" id="DisplayRedSheet">
                    {{-- <h5 id="card_title" class="text-primary text-uppercase">
                        <span class="ic--twotone-pets"></span> CONSUMIBLES CON VALES
                    </h5> --}}
                    <div id="table-container"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Dar alta: barra fija al fondo del viewport, siempre visible sin
         importar el scroll ni el estado de colapso de ninguna card. Mismo
         componente que Finalizar Consulta en appointment/create.blade.php. --}}
    <div class="finalize-sticky-bar">
        <button type="button" class="btn btn-finalizar-consulta btn-sm text-uppercase rounded-4 px-3"
            onclick="discharge({{ $reception->id }})">
            Dar alta <i class="fas fa-check-square"></i>

        </button>
    </div>

    <div class="modal fade" id="ModalSurgeries" tabindex="-1" aria-labelledby="ModalSurgeriesTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                        <div>
                            <div class="fw-bold fs-4 text-dark" id="ModalSurgeriesTitle">Cirugías</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="CloseSurgeries()"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" onsubmit="AddSurgery()" id="NewSurgery" role="form"
                        enctype="multipart/form-data">
                        @csrf
                        @include('surgery.form')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"
                        onclick="CloseSurgeries()">Cancelar</button>
                    <button type="submit" form="NewSurgery" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Registrar
                    </button>
                </div>
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

        @php
            $followUpModalByAreaId = [
                1 => 'ModalFollowupSurgical', // Quirúrgicos
                2 => 'ModalFollowupsCritic', // Cuidado intensivo
                3 => 'ModalFollowupIntern', // Internos
                4 => 'ModalFollowupsCritic', // Exóticos
                5 => 'ModalFollowupsCritic', // Infecciosos
            ];

            $followUpModalId = $followUpModalByAreaId[$reception->area_id ?? null] ?? 'ModalFollowUps';
        @endphp


        var FollowUpModalId = @json($followUpModalId);
    </script>

    <script src="{{ asset('js/hospitalizations/createredsheets.js') }}" defer></script>
    <script src="{{ asset('js/receptions/transfer.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/detail-modal.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/sign-modal.js') }}" defer></script>
    <script>
        function setCurrentDateTime() {
            const input = document.getElementById('date');

            if (!input) return;

            const now = new Date();

            // Ajustar a la zona horaria local
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

            input.value = now.toISOString().slice(0, 16);
        }

        // Cuando se abra el modal de cirugía
        $('#ModalSurgeries').on('shown.bs.modal', function() {
            setCurrentDateTime();
        });
    </script>
@endpush

@push('modals')
    @include('reception.partials.transfer-modal')
    @include('voucher.partials.detail-modal')
    @include('voucher.partials.sign-modal')
    @include('follow-up.modal')
    @include('followup-surgical.modal')

    @include('followup-intern.modal')
    @include('followups-critic.modal')
@endpush
