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
                                                         <th>No</th> 
                                                        
                                                        <th>Mascota</th>
                                                        <th>Mascota</th>
                                                        <th>Servicio</th>
                                                        <th>Fecha de entrada</th>
                                                        <th>Fecha de salida</th>
                                                     <th>ACCIONES</th> 
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 

                                    <select id="veterinarioFilter">
                                        <option value="">Todos los veterinarios</option>
                                        <option value="1">Veterinario 1</option>
                                        <option value="2">Veterinario 2</option>
                                        <option value="3">Veterinario 3</option>
                                    </select>
                                    

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
<script src="{{ asset('js/control_dates/create.js') }}" defer></script>

@endpush

