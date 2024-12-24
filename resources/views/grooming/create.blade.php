@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Grooming
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/receptions/form.css') }}">
@endpush

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                RECEPCIÓN DE GROOMING
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('receptions.update', $reception->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('reception.form')

                        </form>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <H5 id="card_title" class="text-primary text-uppercase">
                            <span class="gravity-ui--scissors"></span> SERVICIOS
                        </H5>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="NewEntry()" role="form"
                            enctype="multipart/form-data" id="NewService">
                            @csrf

                            @include('grooming.form')

                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Notas</th>
                                                <th>Precio</th>
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
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <h5 id="total-price">Total  Final: $0.00</h5>
                    </div>
                    
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <button type="button" onclick="window.location.href='{{ route('grooming.sign', $reception->id) }}'" 
                            class="btn btn-primary">
                            Siguiente <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const type = {{ $reception->reception_type_id }};
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/groomings/create.js') }}" defer></script>
@endpush
