@extends('layouts.app')

@section('template_title')
    Control Date
@endsection

@section('control-dates', 'active border-start border-3 border-primary')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary-soft border-0 p-3">
                <div class="card-header bg-transparent border-0">
                    <div class="display: flex; justify-content: space-between; align-items: center;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <i class="tabler--calendar-time "></i> próximas citas
                            </h4>

                         <div class="float-right">
                            <a href="{{ route('dates.calendar') }}" class="btn btn-primary btn-sm rounded-4">
                                <i class="lucide--calendar-check"></i> CITAS CONFIRMADAS
                            </a>
                            <a href="{{ route('control-dates.create') }}" class="btn btn-primary btn-sm rounded-4">
                                <i class="fas fa-plus"></i> AGENDAR NUEVA CITA
                            </a>
                               {{-- <form action="{{ route('whatsapp.enviar') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Enviar mensajes de WhatsApp para confirmar citas?')">
        @csrf
        <button type="submit" class="btn btn-success btn-sm rounded-4">
            <i class="fas fa-paper-plane"></i> Enviar WhatsApp
        </button>
    </form> --}}
                                    <a href="{{ route('citas.exportar') }}" class="btn btn-success">
                                       <i class="vscode-icons--file-type-excel2"></i> Exportar
                                    </a>

                                
    
                          </div>
 
                    </div>

                    {{-- <div class="col d-flex justify-content-between align-items-center my-2">
                        <div class="col">
                             <button class="btn btn-costum-services btn-sm text-uppercase rounded-5 shadow "
                             onclick="window.location.href='{{ route('hotel.calendar') }}'">
                            <span class="badge custom-badge-pill"><span
                                    class="tabler--calendar-time"></span></span> Calendario
                        </button>
                        </div>
                    </div> --}}
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
                                                    <th>Familia</th>
                                                    <th>Mascota</th>
                                                    <th>tipo de cita</th>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Agendada por</th>
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
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/control_dates/create.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush
