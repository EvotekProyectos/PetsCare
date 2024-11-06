@extends('layouts.app')

@section('template_title')
    Assignment
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0"">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> CONSULTAS
                            </h4>

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
@endsection

@push('scripts')
    <script src="{{asset('js/assignments/index.js')}}" defer></script>
@endpush
