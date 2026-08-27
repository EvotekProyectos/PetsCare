@extends('layouts.app')

@section('template_title')
   HISTORIAL MASCOTA
@endsection

@section('design')
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/redsheets/timeline.css') }}">
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
                                    HISTORIAL MÉDICO
                                </div>

                                {{-- <div class="reception-summary-value">
                                    {{ $reason->name }}
                                </div> --}}
                            </div>

                            {{-- <div class="reception-summary-item reception-summary-date">
                                <div class="reception-summary-label">
                                    <i class="fas fa-calendar-alt reception-calendar-icon"></i>
                                    Fecha
                                </div>

                                <div class="reception-summary-value">
                                    {{ \Carbon\Carbon::parse($entry_date)->format('d/m/Y') }}
                                </div>
                            </div> --}}
                        </div>

                       <x-pet-info :pet="$pet" :years="$years" :months="$months" :days="$days"
                            :genre-name="$genreName" :reproductive-status-name="$reproductiveStatusName" :classification-name="$classificationName" />

                        {{-- Botones de acción: fila propia a ancho completo --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-center gap-3 action-buttons-divider">
                                    <button type="button" class="action-link" onclick="openTransferModal()">
                                       <i class="fas fa-syringe"></i>
                                        <span>Cartilla</span>
                                    </button>

                                    <button type="button" class="action-link" onclick="openBudgetModal()">
                                      <i class="fas fa-pills"></i>
                                        <span>Formúla médica</span>
                                    </button>

                                    <button type="button" class="action-link" onclick="openTransferModal())">
                                      <i class="fas fa-folder-open"></i>
                                        <span>Formatos</span>
                                    </button>

                                    @if ($hasTransfers)
                                        <button type="button" class="action-link"
                                            onclick="openTransfersTrackingModal({ pet_id: {{ $pet->id }} })">
                                           <i class="fas fa-exchange-alt"></i>
                                            <span>Traslados</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- <div class="card-body">
                        {{-- <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #0455A0">
                                <span class="map--veterinary-care"></span> PRODUCTOS/SERVICIOS
                            </h5>
                        </div>
                        <div class="row d-flex justify-content-around align-content-center mb-3">
                            <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                        href="{{ route('vaccine-certificates.show', $pet->id) }}">
                                        <span class="badge custom-badge-pill"><span
                                                class="healthicons--syringe-vaccine"></span> </span> <small>
                                            CARTILLA Virtual</small>
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                        href="{{ route('prescription.create', $pet->id) }}">
                                        <span class="badge custom-badge-pill"><span
                                                class="material-symbols--prescriptions-outline "></span> </span> <small>
                                            FÓRMULA MÉDICA </small></a>
                                </div>

                            </div>
                            <div class="col-12 col-lg-3 d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <a class="btn btn-costum-services btn-lg text-uppercase rounded-5 shadow w-100 fs-6 d-flex justify-content-around align-items-center "
                                        href='{{ route('formats.created', $pet->id) }}'">
                                        <span class="badge custom-badge-pill"><span class="solar--document-add-broken "
                                                style="font-size: 23px;"></span> </span> <small>
                                            FORMATOS </small></a>
                                </div> --}}

                    {{-- <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button  class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="window.location.href='{{ route('prescription.create', $pet->id) }}'" >
                                        <span class="badge custom-badge-pill"><span class="material-symbols--prescriptions-outline "></span> </span> FÓRMULA MÉDICA </button>
                                </div>
                                
                             </div>
                             <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="window.location.href='{{ route('formats.created', $pet->id) }}'">
                                        <span class="badge custom-badge-pill"><span class="material-symbols--prescriptions-outline"></span></span> FORMATOS
                                    </button>
                                </div>
                                
                                
                                
                            {{-- <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                    <button  class="btn btn-costum-services btn-lg text-uppercase rounded-4" onclick="" >
                                        <span class="badge custom-badge-pill"><span class="hugeicons--x-ray"></span></span> IMÁGENES DIAGNÓSTICAS </button>
                                </div>
                            </div>
                    </div> --}}


                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#servicesCollapse"
                            aria-expanded="true" aria-controls="servicesCollapse">
                            <div class="d-flex align-items-center gap-2">
                                <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                    Historial recepciones
                                </h5>
                            </div>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="servicesCollapse">
                            <div class="mt-2">

                                <div class="row g-2 mb-2 align-items-end">
                                    <div class="col">
                                        <label for="filterHistorialTipo" class="form-label mb-1">Tipo</label>
                                        <select id="filterHistorialTipo" class="form-control form-control-sm">
                                            <option value="">Todas</option>
                                            <option value="1" {{ $typeFilter == 1 ? 'selected' : '' }}>Consulta</option>
                                            <option value="2" {{ $typeFilter == 2 ? 'selected' : '' }}>Hospitalización</option>
                                            <option value="3" {{ $typeFilter == 3 ? 'selected' : '' }}>Grooming</option>
                                            <option value="4" {{ $typeFilter == 4 ? 'selected' : '' }}>Hotel</option>
                                            <option value="5" {{ $typeFilter == 5 ? 'selected' : '' }}>Cremación</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="filterHistorialVet" class="form-label mb-1">M.V.Z.</label>
                                        <select id="filterHistorialVet" class="form-control form-control-sm">
                                            <option value="">Todos</option>
                                            @foreach ($vets as $vet)
                                                <option value="{{ $vet->id }}">{{ $vet->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" id="btnClearHistorialFilters"
                                            class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                            title="Limpiar filtros" aria-label="Limpiar filtros">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="table-responsive service-table-wrapper">
                                            <table class="table table-hover table-flat-rows responsive w-100"
                                                id="DataServices">
                                                <thead class="text-uppercase">
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>M.V.Z</th>
                                                        <th>Tipo</th>
                                                        <th>Detalles</th>
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

                    {{-- Estados de cuenta: mismo endpoint/columnas que account/index.blade.php
                         (ver initAccountsTable() en public/js/accounts/table.js), acotado a
                         esta mascota (pet_id fijo, sin filtro de mascota visible). --}}
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center chevron-toggle pb-2"
                            style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#accountsCollapse"
                            aria-expanded="true" aria-controls="accountsCollapse">
                            <div class="d-flex align-items-center gap-2">
                                <h5 id="card_title" class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">
                                    Estados de cuenta
                                </h5>
                            </div>
                            <i class="fas fa-chevron-down text-primary"></i>
                        </div>

                        <div class="collapse show" id="accountsCollapse">
                            <div class="mt-2">

                                <div class="row g-2 mb-2 align-items-end">
                                    <div class="col">
                                        <label for="filterPetAccountEstatus" class="form-label mb-1">Estatus</label>
                                        <select id="filterPetAccountEstatus" class="form-control form-control-sm">
                                            <option value="">Todos</option>
                                            <option value="OPEN">Abierta</option>
                                            <option value="CLOSED">Cerrada</option>
                                            <option value="PAID">Pagada</option>
                                            <option value="CANCELLED">Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="filterPetAccountFechaDesde" class="form-label mb-1">Desde</label>
                                        <input type="date" id="filterPetAccountFechaDesde"
                                            class="form-control form-control-sm" value="">
                                    </div>
                                    <div class="col">
                                        <label for="filterPetAccountFechaHasta" class="form-label mb-1">Hasta</label>
                                        <input type="date" id="filterPetAccountFechaHasta"
                                            class="form-control form-control-sm" value="">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" id="btnClearPetAccountFilters"
                                            class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                            title="Limpiar filtros" aria-label="Limpiar filtros">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="table-responsive service-table-wrapper">
                                            <table class="table table-hover table-flat-rows responsive w-100"
                                                id="petAccountsTable">
                                                <thead class="text-uppercase">
                                                    <tr>
                                                        <th>Mascota</th>
                                                        <th>Familia</th>
                                                        <th>Fecha</th>
                                                        <th>Estatus</th>
                                                        <th>Total</th>
                                                        <th>Folio ODV</th>
                                                        <th>Acciones</th>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('modals')
    @include('reception.partials.transfers-tracking-modal')
    @include('account.partials.detail-modal')
@endpush

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pic_id = {{ $pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $pet->file->route ?? '' }}";
        var Pet_Id = {{ $pet->id }};
    </script>
    <script src="{{ asset('js/receptions/transfers-tracking.js') }}" defer></script>
    <script src="{{ asset('js/accounts/table.js') }}" defer></script>
    <script src="{{ asset('js/pet-history/view.js') }}" defer></script>
@endpush
