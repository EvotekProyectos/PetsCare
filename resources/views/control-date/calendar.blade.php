@extends('layouts.app')


{{-- @push('styles')
    <link rel="stylesheet" href="{{ asset('css/cubicles/view.css') }}">
@endpush --}}

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div style=" display: flex;flex-direction: column; align-items: center; justify-content: center; margin: 0; padding: 20px;"> 
                <div class="col-12">
                    <div class="card bg-primary-soft border-0 p-3">
                        <div class="card-header bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                    <h4 id="card_title" class="text-primary text-uppercase">
                                        <span class="lucide--calendar-check " style="font-size: 20px;"></span>
                                        CITAS CONFIRMADAS
                                    </h4>
                            </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover responsive w-100" id="table">
                                                <thead class="thead table-primary text-uppercase">
                                                    <tr>  
                                                        <th>Familia</th>
                                                        <th>Mascota</th>
                                                        <th>Tipo de cita</th>
                                                        <th>Fecha</th>
                                                        <th>M.V.Z</th>
                                                        <th>Estado</th>
                                                     <th>acciones</th> 
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 

                                        <div class="row" style="padding: 1%">
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="veterinarioFilter" class="form-label fw-bold">Filtrar citas</label>
                                                    <select id="veterinarioFilter" class="form-select" data-user="{{ auth()->user()->name }}" >
                                                        <option value=""> Todas las citas</option>
                                                        <option value="1"> Mis citas</option>
                                                    </select>
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
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/control_dates/calendar.js') }}" defer></script>

@endpush

