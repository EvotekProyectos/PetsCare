@extends('layouts.app')

@section('template_title')
    ESTADOS DE CUENTA
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
@endpush

@section('accounts', 'active border-start border-3 border-primary')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                               <i class="fas fa-dollar-sign"></i> ESTADOS DE CUENTA
                            </h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <div class="reception-card">
                                    <div class="tab-content p-3" id="accountsTabsContent">

                                        <div class="tab-pane fade show active card-panel" id="tab-accounts"
                                            role="tabpanel" aria-labelledby="tab-accounts-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label for="filterAccountEstatus" class="form-label mb-1">Estatus</label>
                                                    <select id="filterAccountEstatus" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="OPEN">Abierta</option>
                                                        <option value="CLOSED">Cerrada</option>
                                                        <option value="PAID">Pagada</option>
                                                        <option value="CANCELLED">Cancelada</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="filterAccountFechaDesde" class="form-label mb-1">Desde</label>
                                                    <input type="date" id="filterAccountFechaDesde"
                                                        class="form-control form-control-sm" value="">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="filterAccountFechaHasta" class="form-label mb-1">Hasta</label>
                                                    <input type="date" id="filterAccountFechaHasta"
                                                        class="form-control form-control-sm" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="filterAccountSearch" class="form-label mb-1">Buscar</label>
                                                    <input type="text" id="filterAccountSearch"
                                                        class="form-control form-control-sm"
                                                        placeholder="Mascota o familia">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" id="btnClearAccountFilters"
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
        </div>
    </div>

    @push('modals')
        @include('account.partials.detail-modal')
    @endpush
@endsection

@push('scripts')
    <script src="{{ asset('js/accounts/table.js') }}" defer></script>
    <script src="{{ asset('js/accounts/index.js') }}" defer></script>
@endpush
