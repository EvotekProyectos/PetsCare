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
                    </div>
 
                    {{-- <div class="legend">
                        <div class="legend-item">
                            <div class="legend-icon available"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-icon occupied"></div>
                            <span>Ocupado</span>
                        </div>
                    </div> --}}

                    <div class="row card-body" >
                        <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN CHICA
                            </h5>
                        </div>

                       
                            <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                @foreach($cubicles as $cubicle)
                                @if($cubicle->cubicle_type_id == 1)
                                    @php
                                        
                                        $Hotel = $cubicle->hotels;
                                        $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                        $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                        $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                        $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                    @endphp
                        
                                    <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                        onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                        onmouseover="showInfo(event, '{{ $state }}', ' {{ $exitDate }}')" 
                                        onmouseout="hideInfo()">
                                        <i style="font-size: 30px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                        <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                    </div>
                                @endif                
                            @endforeach
                            </div>
                            
                            
                            
                        {{-- </div> --}}
                        
                        
                        <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN MEDIANA
                            </h5>
                        </div>

                       
                                <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                    @foreach($cubicles as $cubicle)
                                    @if($cubicle->cubicle_type_id == 2)
                                        @php
                                            
                                            $Hotel = $cubicle->hotels;
                                            $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                            $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                            $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                            $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                        @endphp
                            
                                        <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                            onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                            onmouseover="showInfo(event, '{{ $state }}', ' {{ $exitDate }}')" 
                                            onmouseout="hideInfo()">
                                            <i style="font-size: 30px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                            <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                        </div>
                                    @endif                
                                @endforeach
                            </div>
                       


                        <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN SUITE
                            </h5>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                            @foreach($cubicles as $cubicle)
                                @if($cubicle->cubicle_type_id == 3)
                                    @php
                                        
                                        $Hotel = $cubicle->hotels;
                                        $state = $cubicle->state ? 'Ocupado' : 'Disponible';
                                        $exitDate = $Hotel && $Hotel->reception ? $Hotel->reception->exit_date : ' ';
                                        $pet = $Hotel && $Hotel->reception ? $Hotel->reception->pet->name : 'N/A';
                                        $collar = $Hotel && $Hotel->reception ? $Hotel->reception->num : 'N/A';
                                    @endphp
                        
                                    <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" 
                                        onclick="showCubicleInfo('{{ $state }}', '{{ $exitDate }}', '{{ $pet }}','{{ $collar }}')"
                                        onmouseover="showInfo(event, '{{ $state }}', ' {{ $exitDate }}')" 
                                        onmouseout="hideInfo()">
                                        <i style="font-size: 30px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                        <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                    </div>
                                @endif                
                            @endforeach
                        </div>


                        
                        
                       <div id="info-box">
                        </div>


            
                    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/cubicles/view.js') }}" defer></script>
@endpush 


