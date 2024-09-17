@extends('layouts.app')

@section('template_title')
    Schedule
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="display: flex; justify-content: space-between; align-items: center;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class="text-primary text-uppercase">
                                    <i class="fa fa-calendar-check"></i> HORARIOS
                                </h4>

                                <div class="float-right">
                                    <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm rounded-4">
                                        <i class="fas fa-plus"></i> CREAR NUEVO HORARIO
                                    </a>
                                </div>
                            </div>
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Inicia</th>
                                                <th>Termina</th>
                                                <th>Turno</th>
                                                <th>Personal</th>
                                                <th>Area</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/schedules/index.js') }}" defer></script>
@endpush
