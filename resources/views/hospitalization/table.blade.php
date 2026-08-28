@extends('layouts.app')

@section('template_title')
    HOSPITALIZACIONES
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
@endpush

@section('hospitalizations', 'active border-start border-3 border-primary')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                                <i class="fas fa-briefcase-medical"></i> HOSPITAL
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
                                        <div class="tab-pane fade show active card-panel" id="tab-hospitalizaciones"
                                            role="tabpanel" aria-labelledby="tab-hospitalizaciones-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterConsultaFecha"
                                                        class="form-label mb-1">Fecha</label>
                                                    <input type="date" id="filterConsultaFecha"
                                                        class="form-control form-control-sm"
                                                        value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="filterConsultaEstado"
                                                        class="form-label mb-1">Estado</label>
                                                    <select id="filterConsultaEstado"
                                                        class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($hospitalizationStatuses as $hospitalizationStatus)
                                                            <option value="{{ $hospitalizationStatus->id }}">
                                                                {{ $hospitalizationStatus->name }}</option>
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
                                                            <th>Ingreso </th>
                                                            <th>Tipo</th>
                                                            <th>Familia</th>
                                                            <th>Mascota</th>
                                                            <th>Area</th>
                                                            <th>M.V.Z</th>
                                                            <th>Acciones</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @csrf
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
        var HOSPITALIZATION_STATUS_HOSPITALIZADO_ID = {{ (int) $hospitalizadoStatusId }};
    </script>
    <script src="{{ asset('js/assignments/hospital.js') }}" defer></script>
@endpush
