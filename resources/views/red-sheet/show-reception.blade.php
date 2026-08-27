@extends('layouts.app')

@section('template_title')
    HOSPITALIZACIÓN
@endsection
@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}?v={{ filemtime(public_path('css/appointment.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/redsheets/timeline.css') }}">
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
                             solo lectura (Trasladar/Cambiar admisión son acciones de
                             captura, no aplican aquí). --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">
                                    <button type="button" class="action-link"
                                        onclick="window.open('{{ route('pet-history.index', ['id' => $reception->pet_id, 'type' => 2]) }}', '_blank')">
                                        <i class="fas fa-notes-medical"></i>
                                        <span>Historial médico</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Resumen días hospitalizado (solo lectura): acordeón por día +
                         bitácora, poblado por public/js/hospitalizations/show.js. --}}
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

    <script src="{{ asset('js/hospitalizations/show.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/detail-modal.js') }}" defer></script>
    <script src="{{ asset('js/vouchers/sign-modal.js') }}" defer></script>
@endpush

@push('modals')
    @include('voucher.partials.detail-modal')
    @include('voucher.partials.sign-modal')
    @include('follow-up.modal')
    @include('followup-surgical.modal')
    @include('followup-intern.modal')
    @include('followups-critic.modal')
@endpush
