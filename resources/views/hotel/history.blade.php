@extends('layouts.app')

@section('template_title')
    {{ $hotel->name ?? __('History') . ' ' . __('Hotel') }}
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="icon-park-outline--hotel" style="font-size: 20px; text-align:center;"></span> HOTEL
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES
                            </h5>
                        </div>
                        <div>
                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <p style="font-weight:bold; ">Fecha entrada: <span style="font-weight: normal"> {{ $reception->entry_date}} </span>
                                        </p>
                                    </td>
                                
                                    <td>
                                        <p style="font-weight:bold; ">Recepcionista: <span style="font-weight: normal">{{ $reception->receptionist->name}} </span>
                                        </p>
                                    </td>
                                   
                                    <td>
                                        <p style="font-weight:bold; ">
                                            Médico responsable: <span style="font-weight: normal"> {{ $reception->vet->name ?? 'Sin especificar'}} </span>
                                        </p>
                                    </td>
                                    
                                </tr>
                            </table>
                        </div>
                
                        <div
                            style="border:1px solid #c5e8f7; margin-top:0%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                            <div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td>
                                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                DATOS DE LA FAMILIA
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                
                        <div>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td>
                                        <p style="font-weight:bold;  margin: 0;"> Nombre:</p>
                
                                    </td>
                                    <td style="text-align: left; padding: 5px 10px;">
                                        <p style="font-weight:normal;  margin: 0;">
                                            {{ $reception->pet->family->name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p style="font-weight:bold;  margin: 0;"> Teléfono:</p>
                                    </td>
                                    <td style="padding: 5px 10px;">
                                        <p style="font-weight:normal;  margin: 0;">
                                            {{ $reception->pet->family->phone }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-weight:bold;  margin: 0;"> Contacto emergencia:</p>
                
                                    </td>
                                    <td style="text-align: left; padding: 5px 10px;">
                                        <p style="font-weight:nomal;  margin: 0;">
                                            {{ $reception->pet->family->contact_name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p style="font-weight:bold;  margin: 0;"> Teléfono emergencia:</p>
                                    </td>
                                    <td style="padding: 5px 10px;">
                                        <p style="font-weight:normal;  margin: 0;">
                                            {{ $reception->pet->family->contact_number }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                
                            <div
                                style="border:1px solid #c5e8f7; margin-top:2%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                                <div>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                    DATOS DE LA MASCOT
                                                </h5>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                
                            <div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Nombre:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{ $reception->pet->name }}
                                            </p>
                                            <td>
                                                <p style="font-weight:bold;  margin: 0;"> Raza:</p>
                                            </td>
                                            <td style="padding: 5px 10px;">
                                                <p style=" font-weight:normal; margin: 0;">
                                                    {{ $reception->pet->raza }}
                                                </p>
                                            </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Peso:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:bold; margin: 0;">
                                                {{ $reception->pet->weight }}
                                            </p>
                                        </td>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Sexo:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:bold; margin: 0;">
                                                {{ $reception->pet->genre->name }}
                                            </p>
                                        </td>
                                    </tr>
                
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Fecha de nacimiento:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:bold; margin: 0;">
                                                {{ $reception->pet->birthday}}
                                            </p>
                                        </td>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;">Cartilla virtual:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                <a href="{{ route('certificate.imprimir', $reception->pet->id) }}">SI</a>
                                            </p>
                                            
                                        </td>
                                    </tr>
                
                                </table>
                            </div>
                
                            <div
                                style="border:1px solid #c5e8f7; margin-top: 40px; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                                <div>
                                    <table style="width: 100%; ">
                                        <tr>
                                            <td>
                                                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                                    DAetalles del servicio
                                                </h5>
                                            </td>
                                            <td></td>
                                      
                
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    
                                   
                                   
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Tipo de servicio:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                @foreach ($hotels as $hotel)
                                                {{ $hotel->serv->NOMBRE ?? 0}}
                                                @endforeach
                                            </p>
                                            <td>
                                                <p style="font-weight:bold;  margin: 0;">Días:</p>
                                            </td>
                                            <td style="padding: 5px 10px; word-wrap: break-word; word-break: break-word; white-space: normal;">
                                                <p style=" font-weight:normal; margin: 0;">
                                                    @foreach ($hotels as $hotel)
                                                {{ $hotel->number_days ?? 'N/A' }} 
                                            @endforeach
                                                </p>
                                            </td>
                                            
                                    </tr>
                                    <tr>
                                       
                                            <td>
                                                <p style="font-weight:bold;  margin: 0;"> No. cubículo:</p>
                                            </td>
                                            <td style="padding: 5px 10px;">
                                                <p style=" font-weight:normal; margin: 0;">
                                                    @foreach ($hotels as $hotel)
                                                {{ $hotel->cubicle->name ?? 'N/A' }} 
                                            @endforeach
                                                </p>
                                            </td>
                                            <td>
                                                <p style="font-weight:bold;  margin: 0;"> No. Collar:</p>
                                            </td>
                                            <td style="padding: 5px 10px;">
                                                <p style=" font-weight:normal; margin: 0;">
                                                    {{$reception->num ?? 'Sin especificar'}}
                                                </p>
                                            </td>
                                            
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Alimentación:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                @foreach ($hotels as $hotel)
                                                {{ $hotel->food ?? 'N/A' }} 
                                            @endforeach
                                            </p>
                                          
                                    </tr>
                
                                    <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Objetos:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                @foreach ($hotels as $hotel)
                                                {{ $hotel->objects ?? 'N/A' }} 
                                            @endforeach
                                            </p>
                                        </td>
                                    </tr>
                                    
                                     <tr>
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Observaciones:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                @foreach ($hotels as $hotel)
                                                {{ $hotel->observations ?? 'N/A' }} 
                                            @endforeach
                                            </p>
                                    </tr>
                
                                    <tr>
                                       
                                        <td>
                                            <p style="font-weight:bold;  margin: 0;"> Fecha de salida:</p>
                                        </td>
                                        <td style="padding: 5px 10px;">
                                            <p style=" font-weight:normal; margin: 0;">
                                                {{$reception->exit_date ?? 'Sin especificar'}}
                                            </p>
                                        </td>
                                        
                                </tr>
                                
                                </div>
                
                         
                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- @push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pet_Id = {{ $reception->pet_id }};
        var Pic_id = {{ $reception->pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $reception->pet->file->route ?? '' }}";
        const Reception_Id = {{ $reception->id }};
    </script>

    <script src="{{ asset('js/groomings/show.js') }}" defer></script>
@endpush --}}
