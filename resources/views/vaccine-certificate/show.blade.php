@extends('layouts.app')

@section('template_title')
    CARTILLA VIRTUAL
@endsection

@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/redsheets/timeline.css') }}">
@endsection


@section('content')
    <section class="content container-fluid">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p>{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-12">
                @php
                    $birthday = \Carbon\Carbon::parse($pet->birthday);
                    $now = now();

                    $years = $birthday->diffInYears($now);
                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                    $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);

                    $genreName = $pet->genre?->name;
                    $reproductiveStatusName = $pet->reproductiveStatus?->name;
                    $classificationName = $pet->petClassification?->name;
                @endphp


                <div class="appointment-content-wrapper bg-primary-soft">

                    <div class="card-panel card-panel--pet-info">
                        <div class="reception-summary">
                            <div class="reception-summary-item">
                                <div class="reception-summary-label">
                                    Cartilla virtual
                                </div>
                            </div>
                        </div>

                        <x-pet-info :pet="$pet" :years="$years" :months="$months" :days="$days"
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName" :show-weight-actions="true" />

                        {{-- Botones de acción: fila propia a ancho completo --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">

                                    <button type="button" class="action-link" onclick="OpenCarnet()">
                                        <span class="badge custom-badge-pill"><span
                                                class="healthicons--syringe-vaccine"></span></span> Registrar
                                    </button>

                                    <a href="{{ route('certificate.imprimir', $pet?->id) }}" target="blank"
                                        class="action-link">
                                        <i class="fas fa-pills"></i>
                                        <span>Imprimir Cartilla</span>
                                    </a>

                                    {{--  
                                    <button type="button" class="action-link"
                                        onclick="window.location.href='{{ route('pets.edit', ['pet' => $pet->id, 'return_to' => 'medical_history']) }}'">
                                        <i class="fas fa-edit"></i>
                                        <span>Editar mascota</span>
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col d-flex justify-content-between align-items-center my-2">
                            <div class="col">

                            </div>
                        </div>
                    </div>

                    @php
                        // Mismo filtro que ya usaba la vista (por service_id),
                        // solo calculado una vez para reusarlo en el check de
                        // "Sin información" y en el @foreach de cada tabla.
                        $vaccinesRegisters = $vaccineCertificates->where('service_id', 1);
                        $dewormingExternalRegisters = $vaccineCertificates->where('service_id', 3);
                        $dewormingInternalRegisters = $vaccineCertificates->where('service_id', 2);
                    @endphp

                    <div class="card-panel">
                        {{-- Vacunas --}}
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#vaccinesCollapse"
                            aria-expanded="true" aria-controls="vaccinesCollapse">
                            <h5 class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                Vacunas
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="vaccinesCollapse">
                            <div class="mt-2">
                                @if ($vaccinesRegisters->isEmpty())
                                    <p class="text-muted text-center py-3 mb-0">Sin información</p>
                                @else
                                    <table class="table table-striped table-hover responsive w-100"
                                        id="vaccinesTable">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Vacuna</th>
                                                <th>Próxima Vacunación</th>
                                                <th>M.V.Z</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vaccinesRegisters as $register)
                                                <tr>
                                                    <td>{{ $register->application_date }}</td>
                                                    <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                        {{ $register->lab }} {{ $register->lote }}</td>
                                                    <td>{{ $register->next_application_date }}</td>
                                                    <td>{{ $register->vet->name }}</td>
                                                    <td>{{ $register->observations }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                        {{-- Desparacitación externa --}}
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2 mt-3"
                            style="cursor: pointer;" data-bs-toggle="collapse"
                            data-bs-target="#dewormingExternalCollapse" aria-expanded="true"
                            aria-controls="dewormingExternalCollapse">
                            <h5 class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                Desparacitación externa
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="dewormingExternalCollapse">
                            <div class="mt-2">
                                @if ($dewormingExternalRegisters->isEmpty())
                                    <p class="text-muted text-center py-3 mb-0">Sin información</p>
                                @else
                                    <table class="table table-striped table-hover responsive w-100"
                                        id="dewormingExternalTable">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Producto</th>
                                                <th>Próxima Aplicación</th>
                                                <th>M.V.Z</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dewormingExternalRegisters as $register)
                                                <tr>
                                                    <td>{{ $register->application_date }}</td>
                                                    <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                        {{ $register->dose }}
                                                    </td>
                                                    <td>{{ $register->next_application_date }}</td>
                                                    <td>{{ $register->vet->name }}</td>
                                                    <td>{{ $register->observations }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                        {{-- Desparacitación interna --}}
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2 mt-3"
                            style="cursor: pointer;" data-bs-toggle="collapse"
                            data-bs-target="#dewormingInternalCollapse" aria-expanded="true"
                            aria-controls="dewormingInternalCollapse">
                            <h5 class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                Desparacitación interna
                            </h5>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="dewormingInternalCollapse">
                            <div class="mt-2">
                                @if ($dewormingInternalRegisters->isEmpty())
                                    <p class="text-muted text-center py-3 mb-0">Sin información</p>
                                @else
                                    <table class="table table-striped table-hover responsive w-100"
                                        id="dewormingInternalTable">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Producto</th>
                                                <th>Próxima Aplicación</th>
                                                <th>M.V.Z</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dewormingInternalRegisters as $register)
                                                <tr>
                                                    <td>{{ $register->application_date }}</td>
                                                    <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                        {{ $register->dose }}
                                                    </td>
                                                    <td>{{ $register->next_application_date }}</td>
                                                    <td>{{ $register->vet->name }}</td>
                                                    <td>{{ $register->observations }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="ModalCertificate" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl" role="document">
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
                            <div class="col-12">
                                @include('vaccine-certificate.fillform')
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

    <script src="{{ asset('js/vaccine-certificates/show.js') }}" defer></script>

    {{-- Mismo comportamiento de "sombra al expandir" que ya usa
         appointment/create.blade.php para sus secciones chevron-toggle. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['#vaccinesCollapse', '#dewormingExternalCollapse', '#dewormingInternalCollapse'].forEach(function(selector) {
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
