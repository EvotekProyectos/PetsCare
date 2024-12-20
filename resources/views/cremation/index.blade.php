@extends('layouts.app')

@section('template_title')
    Cremation
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

               <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="emojione-monotone--funeral-urn" style="font-size: 20px;"></span>
                                     CREMACIONES
                            </h4>

                             {{-- <div class="float-right">
                                <a href="{{ route('cremations.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div> --}}
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover responsive w-100" id="table">
                                        <thead class="thead table-primary text-uppercase">
                                            <tr>
                                                <th>Fecha de registro</th>
                                                <th>M.V.Z</th>
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
@endsection

@push('scripts')
    <script src="{{asset('js/cremations/index.js')}}" defer></script>
@endpush
