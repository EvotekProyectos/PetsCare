@extends('layouts.app')

@section('template_title')
    Log
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="fas fa-user"></i> Bitácora
                            </h4>
                        </div>
                    </div>
                    {{-- @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive w-100" id="table">
                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/logs/index.js') }}" defer></script>
@endpush