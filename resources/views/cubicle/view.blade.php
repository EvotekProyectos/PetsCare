 @extends('layouts.app')

{{-- @section('template_title')
    {{ __('Create') }} Cremation
@endsection --}}

@section('content')

<style>
      .dog-house {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer; /* Pointer cursor for hover effect */
        }
        .dog-house p {
             /* Size of the icon */
            /* color: #0455A0; */
        }

        .legend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legend-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .legend-icon.available {
            background-color:#0455A0;
        }

        .legend-icon.occupied {
            background-color: #A5A5A5;
        }

        .tooltip {
        display: none;
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 10;
    }

    .tooltip::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.7) transparent transparent transparent;
    }

    #info-box {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    padding: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}


</style>

    <section class="container-fluid">
        <div class="row">
            <div style=" display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;"> 
                 <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="icon-park-outline--hotel" style="font-size: 20px;"></span>
                                DISPONIBILIDAD PENSIÓN
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN CHICA
                            </h5>
                        </div>

                       
                            <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                @foreach($cubicles as $cubicle)
                                    @if($cubicle->cubicle_type_id == 1)
                                        <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}" onclick="showCubicleInfo( '{{ $cubicle->name }}', '{{ $cubicle->state ? 'Ocupado' : 'Disponible' }}')"
                                            onmouseover="showInfo(event, '{{ $cubicle->name }}', '{{ $cubicle->state ? 'Ocupado' : 'Disponible' }}')" 
                                            onmouseout="hideInfo()">
                                            <i style="font-size: 30px; color: #0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                            <p style="font-size: 15px; margin-top: 10px;">{{ $cubicle->name }}</p>
                                        </div>
                                    @endif                
                                @endforeach
                            </div>
                        </div>
                        
                        
                        <div class="d-flex justify-content-between align-items-center" style="border-top: 30px">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN MEDIANA
                            </h5>
                        </div>

                        <div>
                                <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                @foreach($cubicles as $cubicle)
                                @if($cubicle->cubicle_type_id == 2)
                                <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}"
                                    onmouseover="showInfo(event, '{{ $cubicle->name }}', '{{ $cubicle->state ? 'Ocupado' : 'Disponible' }}')" 
                                     onmouseout="hideInfo()">
                                    <i style=" font-size: 30px; color=0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                    <p style="font-size: 15px"> {{ $cubicle->name }}</p>
                                </div>
                            @endif                
                                @endforeach
                            </div>
                        </div>


                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                PENSIÓN SUITE
                            </h5>
                        </div>

                        <div>
                           
                                <div style="display: grid; grid-template-columns: repeat(9, 1fr); gap: 5px; margin: 0 auto; max-width: 90%; padding: 10px;">
                                    @foreach($cubicles as $cubicle)
                                @if($cubicle->cubicle_type_id == 3)
                                <div class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}"
                                    onmouseover="showInfo(event, '{{ $cubicle->name }}', '{{ $cubicle->state ? 'Ocupado' : 'Disponible' }}')" 
                                     onmouseout="hideInfo()">
                                    <i style=" font-size: 30px; color=0455A0;" class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                    <p style="font-size: 15px"> {{ $cubicle->name }}</p>
                                </div>
                            @endif                
                                @endforeach
                            </div>
                        </div>
                        
                        <div id="info-box" style="display:none; position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <p><strong>Nombre:</strong> <span id="info-name"></span></p>
                            <p><strong>Estado:</strong> <span id="info-status" style="color:"></span></p>
                        </div>

                        
                   

                         {{-- <div class="grid-container">
                            @foreach($cubicles as $cubicle)
                                <div
                                    class="dog-house {{ $cubicle->state ? 'occupied' : 'available' }}"
                                    onmouseover="showCubicleInfo('{{ $cubicle->name }}', {{ $cubicle->state }})"
                                >
                                    <i class="game-icons--dog-house{{ $cubicle->state ? 'G' : '' }}"></i>
                                    <p>Cubículo {{ $cubicle->name }}</p>
                                </div>
                            @endforeach
                        </div>  --}}
                       
                        
                        {{-- <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                    PENSIÓN CHICA
                                </h5>
                            </div>  --}}

                      


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/cubicles/view.js') }}" defer></script>
@endpush 


