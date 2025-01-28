@extends('layouts.app')

@section('template_title')
    Reception
@endsection
@section('receptions', 'active border-start border-3 border-primary') 
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ph--call-bell-fill"></span> RECEPCIONES
                            </h4>

                            <div class="float-right">
                                <a href="{{ route('receptions.create') }}" class="btn btn-primary btn-sm rounded-4">
                                    <i class="fas fa-plus"></i> NUEVA RECEPCION
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
                        <div class="row">
                            <div class="col-12">

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class="text-uppercase d-flex align-items-center" style="color: #BEBEBE; font-size: 20px;">
                                        CONSULTAS
                                        <span class="maki--doctorGrey" style="font-size:18px; margin-left: 3px;"></span>
                                    </h5>
                                </div>
                                
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>M.V.Z</th>
                                                <th>Familia</th>
                                                <th>Mascota</th>
                                                <th>Motivo</th>
                                                <th>Consultorio</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class="text-uppercase d-flex align-items-center" style="color: #BEBEBE; font-size: 20px;">
                                        HOSPITALIZACIONES
                                        <span class="mdi--hospital"  style="font-size:20px; margin-left: 3px; margin-bottom:2px;"></span>
                                    </h5>
                                </div>
                                

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table2">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>M.V.Z</th>
                                                <th>Familia</th>
                                                <th>Mascota</th>
                                                <th>Admisión</th>
                                                <th>Area</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                        GROOMING
                                        <span class="humbleicons--scissors" style="margin-top: 1%" ></span>
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table3">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Colaborador</th>
                                                <th>Familia</th>
                                                <th>Mascota</th>
                                                <th>FECHA DE SALIDA</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                        HOTEL/PENSIÓN
                                        <span class="icon-park-outline--hotelG"  style="margin-top: 30%" ></span>
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table4">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha ingreso</th>
                                                <th>M.V.Z</th>
                                                <th>Familia</th>
                                                <th>Mascota</th>
                                                <th>Fecha de salida</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                        CREMACIONES
                                        <span class="emojione-monotone--funeral-urnGrey"></span>
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table5">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Recepcionista</th>
                                                <th>Familia</th>
                                                <th>Mascota</th>
                                                {{-- <th>Fecha de entrega</th> --}}
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
    <script src="{{asset('js/receptions/index.js')}}" defer></script>
@endpush
