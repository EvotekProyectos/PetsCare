@extends('layouts.app')

@section('template_title')
    CONSULTAS
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
@endpush

@section('assignments', 'active border-start border-3 border-primary')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                                <i class="fas fa-user-md"></i> consultas
                            </h3>

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
                                    <div class="tab-content p-3" id="receptionTabsContent">

                                        <div class="tab-pane fade show active card-panel" id="tab-consultas" role="tabpanel"
                                            aria-labelledby="tab-consultas-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label for="filterConsultaFecha" class="form-label mb-1 fw-normal">
                                                        Fecha
                                                    </label>
                                                    <input type="date" id="filterConsultaFecha"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="filterConsultaEstado" class="form-label mb-1 fw-normal">
                                                        Estado
                                                    </label>
                                                    <select id="filterConsultaEstado" class="form-control form-control-sm">
                                                        <option value="">Todos</option>

                                                        @foreach ($attentionStatuses as $attentionStatus)
                                                            <option value="{{ $attentionStatus->id }}">
                                                                {{ $attentionStatus->name }}
                                                            </option>
                                                        @endforeach
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
                                                            <th>Tipo</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Motivo</th>
                                                            <th>Consultorio</th>
                                                            <th>Estado</th>
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
@endsection

@push('scripts')
    <script>
        // Usados por la columna Acciones (Attend()/render de botones en
        // assignments/index.js) para distinguir En espera vs En consulta sin
        // hardcodear el id — mismo criterio.
        var ATTENTION_STATUS_EN_ESPERA_ID = {{ (int) $enEsperaStatusId }};
        var ATTENTION_STATUS_EN_CONSULTA_ID = {{ (int) $enConsultaStatusId }};
    </script>
    <script src="{{ asset('js/assignments/index.js') }}" defer></script>
@endpush
