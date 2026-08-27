@extends('layouts.app')

@section('template_title')
    Cremation
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/index.css') }}">
@endpush

@section('cremations', 'active border-start border-3 border-primary')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

               <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 id="card_title" class="text-primary text-uppercase fw-bold mb-0">
                                <span class="emojione-monotone--funeral-urn" style="font-size: 20px;"></span>
                                     CREMACIONES
                            </h3>

                             {{-- <div class="float-right">
                                <a href="{{ route('cremations.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div> --}}
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
                                        <div class="tab-pane fade show active card-panel" id="tab-cremaciones"
                                            role="tabpanel" aria-labelledby="tab-cremaciones-tab">
                                            <div class="row g-2 mb-2 align-items-end">
                                                <div class="col">
                                                    <label for="filterCremacionFecha" class="form-label mb-1">Fecha</label>
                                                    <input type="date" id="filterCremacionFecha"
                                                        class="form-control form-control-sm" value="">
                                                </div>
                                                <div class="col">
                                                    <label for="filterCremacionEstado" class="form-label mb-1">Estado</label>
                                                    {{-- Cremation.status es un string plano, no un catálogo con id
                                                         (ver Attend()/updateStatus() en cremations/index.js) --}}
                                                    <select id="filterCremacionEstado" class="form-control form-control-sm">
                                                        <option value="">Todos</option>
                                                        <option value="En espera de realizar">En espera de realizar</option>
                                                        <option value="Listo para entregar">Listo para entregar</option>
                                                        <option value="Entregado">Entregado</option>
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
                                            <div class="table-responsive">
                                                <table class="table table-hover table-flat-rows responsive w-100" id="table">
                                                    <thead class="thead table-header-solid text-uppercase">
                                                        <tr>
                                                            <th>Fecha de registro</th>
                                                            <th>Recepcionista</th>
                                                            <th>Propietario</th>
                                                            <th>Mascota</th>
                                                            <th>Servicio</th>
                                                            <th>Tipo de urna</th>
                                                            {{-- <th>Observaciones</th> --}}
                                                            <th>C.M.</th>
                                                            <th>Estado</th>
                                                             <th>Acciones</th>
                                                           {{-- <th>Acciones</th> --}}
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
    <script src="{{asset('js/cremations/index.js')}}" defer></script>
@endpush
