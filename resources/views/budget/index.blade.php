@extends('layouts.app')

@section('template_title')
    Budget
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">

                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--baseline-price-change"></span> PRESUPUESTOS QUIRURGICOS
                            </h4>

                            <div class="float-right">
                                <a href="{{ route('budgets.create') }}" class="btn btn-primary btn-sm rounded-4"
                                    data-placement="left">
                                    <i class="fas fa-plus"></i> CREAR NUEVO PRESUPUESTO
                                </a>
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive w-100" id="table">
                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th>Folio</th>
                                        <th>Fecha</th>
                                        <th>Mascota</th>
                                        <th>Paquete</th>
                                        <th>Procedimiento</th>
                                        <th>Biometria Hematica</th>
                                        <th>Quimica Sanguinea</th>
                                        <th>Nodulectomia</th>
                                        <th>Histopatologia</th>
                                        <th>Radiografías</th>
                                        <th>Collar Isabelino</th>
                                        <th>Body de Cobre</th>
                                        <th>Otros</th>
                                        <th>Gran Total</th>
                                        <th>M.V.Z</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $budgets->links() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/budgets/index.js') }}" defer></script>
@endpush