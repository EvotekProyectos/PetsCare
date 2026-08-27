@extends('layouts.app')

@section('template_title')
    RECEPCIÓN
@endsection

@section('receptions', 'active border-start border-3 border-primary')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/redsheets/timeline.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center px-2">

                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                                RECEPCIÓN
                            </h3>

                            <button type="button" class="btn btn-primary rounded-4 reception-new-btn"
                                data-bs-toggle="modal" data-bs-target="#receptionModal"
                                onclick="openCreateReceptionModal()">
                                <i class="fas fa-plus"></i> NUEVA RECEPCIÓN
                            </button>

                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <div class="reception-card">
                                    <ul class="nav nav-tabs reception-tabs" id="receptionTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="tab-consultas-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab-consultas" type="button" role="tab"
                                                aria-controls="tab-consultas" aria-selected="true">

                                                <i class="fas fa-user-md"></i>
                                                <span>CONSULTAS</span>
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-hospitalizaciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab-hospitalizaciones" type="button" role="tab"
                                                aria-controls="tab-hospitalizaciones" aria-selected="false">

                                                 <i class="fas fa-briefcase-medical"></i>
                                                <span>HOSPITAL</span>
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-grooming-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab-grooming" type="button" role="tab"
                                                aria-controls="tab-grooming" aria-selected="false">

                                                <i class="fas fa-cut"></i>
                                                <span>GROOMING</span>
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-hotel-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab-hotel" type="button" role="tab"
                                                aria-controls="tab-hotel" aria-selected="false">

                                                <i class="fas fa-bed"></i>
                                                <span>HOTEL</span>
                                            </button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-cremaciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#tab-cremaciones" type="button" role="tab"
                                                aria-controls="tab-cremaciones" aria-selected="false">

                                              <i class="fas fa-prescription-bottle-alt"></i>


                                                <span>CREMACIONES</span>
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content p-3" id="receptionTabsContent">

                                        <div class="tab-pane fade show active card-panel" id="tab-consultas" role="tabpanel"
                                            aria-labelledby="tab-consultas-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                            <div class="col">
                                                <label for="filterConsultaFecha" class="form-label mb-1">Desde</label>
                                                <input type="date" id="filterConsultaFecha"
                                                    class="form-control form-control-sm"
                                                    value="{{ now()->format('Y-m-d') }}">
                                            </div>
                                            <div class="col">
                                                <label for="filterConsultaFechaHasta" class="form-label mb-1">Hasta</label>
                                                <input type="date" id="filterConsultaFechaHasta"
                                                    class="form-control form-control-sm"
                                                    value="{{ now()->format('Y-m-d') }}">
                                            </div>
                                            <div class="col">
                                                <label for="filterConsultaEstado" class="form-label mb-1">Estado</label>
                                                <select id="filterConsultaEstado" class="form-control form-control-sm">
                                                    <option value="">Todos</option>
                                                    @foreach ($attentionStatuses as $attentionStatus)
                                                        <option value="{{ $attentionStatus->id }}">{{ $attentionStatus->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="filterConsultaMedico" class="form-label mb-1">Médico</label>
                                                <select id="filterConsultaMedico" class="form-control form-control-sm">
                                                    <option value="">Todos</option>
                                                    @foreach ($veterinarians as $veterinarian)
                                                        <option value="{{ $veterinarian->id }}">{{ $veterinarian->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="filterConsultaCuenta" class="form-label mb-1">Cuenta</label>
                                                <select id="filterConsultaCuenta" class="form-control form-control-sm">
                                                    <option value="">Todos</option>
                                                    <option value="OPEN">Abierta</option>
                                                    <option value="CLOSED">Cerrada</option>
                                                    <option value="PAID">Pagada</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" id="btnClearConsultaFilters"
                                                    class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                    title="Limpiar filtros" aria-label="Limpiar filtros">
                                                    <i class="fas fa-eraser"></i>
                                                </button>
                                            </div>
                                        </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100"
                                                    id="table">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>M.V.Z</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Motivo</th>
                                                            <th>Consultorio</th>
                                                            <th>Estatus</th>
                                                            <th>Cuenta</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade card-panel" id="tab-hospitalizaciones" role="tabpanel"
                                            aria-labelledby="tab-hospitalizaciones-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterHospFecha" class="form-label mb-1">Desde
                                                        (abierto)</label>
                                                    <input type="date" id="filterHospFecha"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHospFechaHasta" class="form-label mb-1">Hasta
                                                        (abierto)</label>
                                                    <input type="date" id="filterHospFechaHasta"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHospEstatus" class="form-label mb-1">Estatus</label>
                                                    <select id="filterHospEstatus" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($hospitalizationStatuses as $hospitalizationStatus)
                                                            <option value="{{ $hospitalizationStatus->id }}">
                                                                {{ $hospitalizationStatus->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterHospArea" class="form-label mb-1">Área</label>
                                                    <select id="filterHospArea" class="form-control form-control-sm">
                                                        <option value="">Todas</option>
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}">{{ $area->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterHospAdmision"
                                                        class="form-label mb-1">Admisión</label>
                                                    <select id="filterHospAdmision" class="form-control form-control-sm">
                                                        <option value="">Todas</option>
                                                        @foreach ($admissions as $admission)
                                                            <option value="{{ $admission->id }}">{{ $admission->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterHospCuenta" class="form-label mb-1">Cuenta</label>
                                                    <select id="filterHospCuenta" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="OPEN">Abierta</option>
                                                        <option value="CLOSED">Cerrada</option>
                                                        <option value="PAID">Pagada</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearHospFilters"
                                                        class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                        title="Limpiar filtros" aria-label="Limpiar filtros">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100"
                                                    id="table2">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>M.V.Z</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Area</th>
                                                            <th>Admisión</th>
                                                            <th>Estatus</th>
                                                            <th>Cuenta</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade card-panel" id="tab-grooming" role="tabpanel"
                                            aria-labelledby="tab-grooming-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterGroomingFecha" class="form-label mb-1">Desde</label>
                                                    <input type="date" id="filterGroomingFecha"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterGroomingFechaHasta" class="form-label mb-1">Hasta</label>
                                                    <input type="date" id="filterGroomingFechaHasta"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterGroomingEstatus"
                                                        class="form-label mb-1">Estatus</label>
                                                    <select id="filterGroomingEstatus"
                                                        class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($groomingStatuses as $groomingStatus)
                                                            <option value="{{ $groomingStatus->id }}">
                                                                {{ $groomingStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterGroomingColaborador"
                                                        class="form-label mb-1">Colaborador</label>
                                                    <select id="filterGroomingColaborador"
                                                        class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($collaborators as $collaborator)
                                                            <option value="{{ $collaborator->id }}">
                                                                {{ $collaborator->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterGroomingCuenta" class="form-label mb-1">Cuenta</label>
                                                    <select id="filterGroomingCuenta" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="OPEN">Abierta</option>
                                                        <option value="CLOSED">Cerrada</option>
                                                        <option value="PAID">Pagada</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearGroomingFilters"
                                                        class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                        title="Limpiar filtros" aria-label="Limpiar filtros">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100"
                                                    id="table3">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Colaborador</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Fecha de salida</th>
                                                            <th>Estatus</th>
                                                            <th>Cuenta</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade card-panel" id="tab-hotel" role="tabpanel"
                                            aria-labelledby="tab-hotel-tab">
                                            <div id="hotelCubicleAvailability" class="mb-2 d-flex flex-wrap gap-2"></div>
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterHotelFechaAbierta" class="form-label mb-1">Desde
                                                        (abierta)</label>
                                                    <input type="date" id="filterHotelFechaAbierta"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHotelFechaAbiertaHasta" class="form-label mb-1">Hasta
                                                        (abierta)</label>
                                                    <input type="date" id="filterHotelFechaAbiertaHasta"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHotelEstatus"
                                                        class="form-label mb-1">Estatus</label>
                                                    <select id="filterHotelEstatus" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($hotelStatuses as $hotelStatus)
                                                            <option value="{{ $hotelStatus->id }}">
                                                                {{ $hotelStatus->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterHotelFechaSalida" class="form-label mb-1">Fecha
                                                        salida</label>
                                                    <input type="date" id="filterHotelFechaSalida"
                                                        class="form-control form-control-sm" value="">
                                                </div>
                                                <div class="col">
                                                    <label for="filterHotelCuenta" class="form-label mb-1">Cuenta</label>
                                                    <select id="filterHotelCuenta" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="OPEN">Abierta</option>
                                                        <option value="CLOSED">Cerrada</option>
                                                        <option value="PAID">Pagada</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearHotelFilters"
                                                        class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                        title="Limpiar filtros" aria-label="Limpiar filtros">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100"
                                                    id="table4">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th>Fecha ingreso</th>
                                                            <th>M.V.Z</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Fecha de salida</th>
                                                            <th>Estatus</th>
                                                            <th>Cuenta</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade card-panel" id="tab-cremaciones" role="tabpanel"
                                            aria-labelledby="tab-cremaciones-tab">

                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterCremacionFecha"
                                                        class="form-label mb-1">Desde</label>
                                                    <input type="date" id="filterCremacionFecha"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterCremacionFechaHasta"
                                                        class="form-label mb-1">Hasta</label>
                                                    <input type="date" id="filterCremacionFechaHasta"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterCremacionEstatus"
                                                        class="form-label mb-1">Estatus</label>
                                                    <select id="filterCremacionEstatus"
                                                        class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($cremationStatuses as $cremationStatus)
                                                            <option value="{{ $cremationStatus->id }}">
                                                                {{ $cremationStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="filterCremacionCuenta" class="form-label mb-1">Cuenta</label>
                                                    <select id="filterCremacionCuenta" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="OPEN">Abierta</option>
                                                        <option value="CLOSED">Cerrada</option>
                                                        <option value="PAID">Pagada</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearCremacionFilters"
                                                        class="btn btn-outline-secondary btn-sm btn-clear-filters"
                                                        title="Limpiar filtros" aria-label="Limpiar filtros">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="cremationBulkBar"
                                                class="d-flex align-items-center justify-content-end gap-2 mb-2 p-2 rounded"
                                                style="display: none;">
                                                <span id="cremationSelectedCount" class="fw-bold"
                                                    style="color: #1e6091;"></span>
                                                <button type="button" id="btnAdvanceStatus" class="btn btn-sm rounded-4"
                                                    style="background: #d6eef8; color: #1e6091;">
                                                    <i class="fas fa-forward"></i> Avanzar estatus
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100"
                                                    id="table5">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th></th>
                                                            <th>Fecha</th>
                                                            <th>Recepcionista</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Estatus</th>
                                                            <th>Cuenta</th>
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="receptionModal" tabindex="-1" aria-labelledby="receptionModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
            <div class="modal-content">

                <div class="modal-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                        <div>
                            <div class="fw-bold fs-4 text-dark text-uppercase" id="receptionModalTitle">Nueva recepción</div>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="receptionForm" method="POST" action="{{ route('receptions.store') }}">
                    @csrf
                    <input type="hidden" id="reception_id" name="reception_id" value="">
                    <div class="modal-body">
                        @include('reception.form', ['showPetQuickCreate' => true])
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('modals')
        @include('reception.partials.pet-quick-create-modal')
        @include('reception.partials.account-statement-modal')
        @include('reception.partials.documents-modal')
        @include('reception.partials.transfer-modal')
        @include('reception.partials.transfers-tracking-modal')
    @endpush
@endsection

@push('scripts')
    <script src="{{ asset('js/receptions/transfers-tracking.js') }}" defer></script>
    <script src="{{ asset('js/receptions/index.js') }}" defer></script>
    <script src="{{ asset('js/receptions/modal.js') }}" defer></script>
    <script src="{{ asset('js/receptions/petQuickCreate.js') }}" defer></script>
    <script src="{{ asset('js/pets/breed-cascade.js') }}" defer></script>
    <script src="{{ asset('js/receptions/accountStatement.js') }}" defer></script>
    <script src="{{ asset('js/receptions/transfer.js') }}" defer></script>
    <script>
        // Usado por la columna Acciones de la tabla de Consultas (ver
        // receptions/index.js): mismo criterio de "por nombre, no
        // hardcodeado" (ver ReceptionController::index()).
        var ATTENTION_STATUS_EN_ESPERA_ID = {{ (int) $enEsperaAttentionStatusId }};

        window.cremationStatusNextMap = @json(
            \App\Models\CremationStatus::orderBy('id')->pluck('id')->mapWithKeys(function ($id, $index) {
                    $all = \App\Models\CremationStatus::orderBy('id')->get();
                    $next = $all->get($index + 1);
                    return [$id => $next?->name];
                }));
    </script>
@endpush
