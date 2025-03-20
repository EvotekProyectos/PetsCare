 @extends('layouts.app')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cubicles/view.css') }}">
@endpush

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div style=" display: flex;flex-direction: column; align-items: center; justify-content: center; margin: 0; padding: 20px;"> 
                <div class="col-12">
                    <div class="card bg-primary-soft border-0 p-3">
                        <div class="card-header bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                    <h4 id="card_title" class="text-primary text-uppercase">
                                        <span class="icon-park-outline--hotel" style="font-size: 20px;"></span>
                                        DISPONIBILIDAD CUBÍCULOS
                                    </h4>
                            </div>
                            
                            {{-- Pestañas--}}
                            {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="map-tab" data-bs-toggle="tab" data-bs-target="#map" type="button" role="tab">Mapa</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="ocupados-tab" data-bs-toggle="tab" data-bs-target="#ocupados" type="button" role="tab">Calendario</button>
                                </li>
                            </ul> --}}

                            <div class="col d-flex justify-content-between align-items-center my-2">
                                <div class="col">
                                     <button class="btn btn-costum-services btn-sm text-uppercase rounded-5 shadow "
                                     onclick="window.location.href='{{ route('hotel.calendar') }}'">
                                    <span class="badge custom-badge-pill"><span
                                            class="tabler--calendar-time"></span></span> Calendario
                                </button>
                                </div>
                            </div>

                             {{-- Simbología --}}
                            {{-- <div class="tab-content p-3" id="myTabContent"> --}}
                                <div class="tab-pane fade show active" id="map" role="tabpanel">
                                
                                    <div class="legend">
                                        <div class="legend-item">
                                            <div class="legend-icon available"></div>
                                            <span>Disponible</span>
                                        </div>

                                        <div class="legend-item">
                                            <div class="legend-icon occupied"></div>
                                            <span>Ocupado</span>
                                        </div>

                                        <div class="legend-item">
                                            <div class="legend-icon expired"></div>
                                            <span>Vencido</span>
                                        </div>
                                    </div>

                                
                                
                                <div class="row card-body" >
                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h4 id="card_title" class=" text-uppercase" >
                                            PENSIÓN CHICA
                                        </h4>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                            PENSIÓN PISO C
                                        </h5>
                                    </div>

                                    <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                        @foreach($cubicles as $cubicle)
                                        @if ($cubicle->cubicle_type_id == 1 && Str::startsWith($cubicle->name, 'C'))
                                                @php
                                                    
                                                    $Hotel = $cubicle->hotels;
                                                    $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                                    $size = $cubicle->length . ' x ' . $cubicle->width;
                                                    $exitDate = $cubicle && $cubicle ? $cubicle->end_date : ' ';
                                                    $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                                    $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                                @endphp
                                    
                                                <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                                    onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                                    onmouseover="showInfo(event, '{{ $size }}', ' {{ $exitDate }}')" 
                                                    onmouseout="hideInfo()">
                                                    <i style="font-size: 25px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                                    <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                                </div>
                                            @endif                
                                        @endforeach
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                            PENSIÓN EXPO
                                        </h5>
                                    </div>

                            
                                    <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                        @foreach($cubicles as $cubicle)
                                        @if ($cubicle->cubicle_type_id == 1 && Str::startsWith($cubicle->name, 'D'))
                                            @php
                                                
                                                $Hotel = $cubicle->hotels;
                                                $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                                $size = $cubicle->length . ' x ' . $cubicle->width;
                                                $exitDate = $cubicle && $cubicle ? $cubicle->end_date : ' ';
                                                $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                                $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                            @endphp
                                
                                            <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                                onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                                onmouseover="showInfo(event, '{{ $size }}', ' {{ $exitDate }}')" 
                                                onmouseout="hideInfo()">
                                                <i style="font-size: 25px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                                <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                            </div>
                                        @endif                
                                    @endforeach
                                    </div>
                                
                                    
                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h4 id="card_title" class=" text-uppercase" >
                                            PENSIÓN MEDIANA
                                        </h4>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                            PENSIÓN PISO B
                                        </h5>
                                    </div>
            
                                    <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                        @foreach($cubicles as $cubicle)
                                            @if($cubicle->cubicle_type_id == 2)
                                                @php
                                                    
                                                    $Hotel = $cubicle->hotels;
                                                    $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                                    $size = $cubicle->length . ' x ' . $cubicle->width;
                                                    $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                                    $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                                    $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                                @endphp
                                    
                                                <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                                    onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                                    onmouseover="showInfo(event, '{{ $size }}', ' {{ $exitDate }}')" 
                                                    onmouseout="hideInfo()">
                                                    <i style="font-size: 25px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                                    <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                                </div>
                                            @endif                
                                        @endforeach
                                    </div>

                                    
                                
                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h4 id="card_title" class=" text-uppercase" >
                                            PENSIÓN SUITE
                                        </h4>
                                    </div> 

                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                            PENSIÓN PISO A
                                        </h5>
                                    </div>

                            
                                    <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                            @foreach($cubicles as $cubicle)
                                            @if($cubicle->cubicle_type_id == 3)
                                                @php
                                                    
                                                    $Hotel = $cubicle->hotels;
                                                    $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                                    $size = $cubicle->length . ' x ' . $cubicle->width;
                                                    $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                                    $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                                    $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                                @endphp
                                    
                                                <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                                    onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                                    onmouseover="showInfo(event, '{{ $size }}', ' {{ $exitDate }}')" 
                                                    onmouseout="hideInfo()">
                                                    <i style="font-size: 25px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                                    <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                                </div>
                                            @endif                
                                        @endforeach
                                    </div>
                            
                                
                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h4 id="card_title" class=" text-uppercase" >
                                            PENSIÓN GATOS
                                        </h4>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                                        <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                            PENSIÓN GATOS
                                        </h5>
                                    </div>

                            
                                    <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                        @foreach($cubicles as $cubicle)
                                        @if($cubicle->cubicle_type_id == 4)
                                            @php
                                                
                                                $Hotel = $cubicle->hotels;
                                                $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                                $size = $cubicle->length . ' x ' . $cubicle->width;
                                                $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                                $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                                $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                            @endphp
                                
                                            <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                                onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                                onmouseover="showInfo(event, '{{ $size }}', ' {{ $exitDate }}')" 
                                                onmouseout="hideInfo()">
                                                {{-- <i style="font-size: 25px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i> --}}
                                                {{-- <img src="{{ asset('img/catHouse.png') . ($cubicle->state ? 'G' : '') }}" alt="Casa de gato" style="width: 40px; color: #0455A0;"> --}}
                                                <img 
                                                    src="{{ asset('img/' . ($cubicle->state ? 'catHouseGray.png' : 'catHouse.png')) }}" 
                                                    alt="Casa de gato" 
                                                        style="width: 40px;">

                                                <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                            </div>
                                        @endif                
                                    @endforeach
                                    </div>
                                

                                
                                    
                                
                                
                            <div id="info-box">
                                </div>

                            </div>    
                            </div>

                            {{-- <div class="tab-pane fade" id="ocupados" role="tabpanel">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover responsive w-100" id="table">
                                            <thead class="thead table-primary text-uppercase">
                                                <tr>
                                                    {{-- <th>No</th> 
                                                    
                                                    <th>Mascota</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha de entrada</th>
                                                    <th>Fecha de salida</th>
                                                    {{-- <th>ACCIONES</th> 
                                                </tr>
                                            </thead>
            
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                
                                    <div class="row">
                                        <div class="col-12">
                                            <div id='calendar'></div>
                                        </div>
                                    </div>
                    
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/cubicles/view.js') }}" defer></script>

@endpush


