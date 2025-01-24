<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/logo-petscare.png') }}" type="image/gif" sizes="16x16">
    @routes
    <link rel="stylesheet" href="{{ asset('css/pension/responsiva.css') }}">
</head>

<body> 
    <div>
        <table style="width: 100%; ">
            <tr>
                <th style="width: 20%"> </th>
                <th style="width: 60%"></th>
                <th style="width: 20%"></th>
            </tr>
            <tr>
                <td style="align-items: center;">
                    <img src="{{ asset('img/logo-petscare.png') }}" style="height: 87px;">
                    {{-- @if($isPdf ?? false)
                    <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px">
                @endif --}}
                </td>
                
                <td style="text-align: center;">
                    <p style="font-family:  'Nunito'; font-weight: bold; font-size: 10pt; color: #9c4133;">
                        Blvd. Luis Donaldo Colosio #764 <br>
                        Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                        equipomedicopetscare@gmail.com
                    </p>
                </td>
                <td style="text-align: center; border: 1px solid #9c4133;">
                    <p style="color: #9c4133; font-family:  'Nunito';">FOLIO</p>
                    <hr style="color: #9c4133; width: 100%; margin-bottom: -7%; margin-top: -7%;">
                    <p
                        style="font-family: 'Times New Roman', Times, serif; font-weight: lighter; font-size: 12pt; color: #776d6d;">
                        {{ str_pad($reception->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top:-2%;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center; width: 100%;">
                        <p class="titles" style="color: #9c4133;">RESPONSIVA PENSIÓN</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div class="first">
        <div class="second">
            <table class="basic-table">
                <thead>
                    <tr class="table-header">
                        <th colspan="2">DATOS DE LA FAMILIA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Familia:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->name }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Domicilio:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->address }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Teléfono:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->phone }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Contacto de emergencia:</td>
                        <td class="table-body-cell">{{ $reception->entry_date }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Teléfono de emergencia:</td>
                        <td class="table-body-cell">{{ $reception->exit_date }}</td>
                    </tr>
                </tbody>
                
            </table>
        </div>
    
        <div class="second">
            <table class="basic-table">
                <thead>
                    <tr class="table-header">
                        <th colspan="2">DATOS DE LA MASCOTA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Familia:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->name }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Domicilio:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->address }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Teléfono:</td>
                        <td class="table-body-cell">{{ $reception->pet->family->phone }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Contacto de emergencia:</td>
                        <td class="table-body-cell">{{ $reception->entry_date }}</td>
                    </tr>
                    <tr class="table-body-row">
                        <td class="table-body-cell text-bold">Teléfono de emergencia:</td>
                        <td class="table-body-cell">{{ $reception->exit_date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    
    </div>
    

    <div class="first">
        <div class="second">
            <table class="basic-table">
            <thead>
                <tr class="table-header">
                     <th colspan="2" class="table-body-cell text-bold">RECORD DE VACUNACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-body-row">
                    <td class="table-body-cell text-bold"width: 40%;">Edad:</td>
                    <td class="table-body-cell">
                        @php
                        $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                        $now = \Carbon\Carbon::now();
                        $years = $birthday->diffInYears($now);
                        $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                        @endphp
                        {{ $years }} años, {{ $months }} meses
                    </td>
                </tr>
                <tr>
                    <td class="table-body-cell text-bold">Carnet:</td>
                    <td class="table-body-cell">
                        <a href="{{ route('certificate.imprimir', $reception->pet->id) }}" style="color: #9c4133; text-decoration: none; font-weight: bold;">Sí</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="second">
        <table class="basic-table">
            <thead>
                <tr class="table-header">
                    <th colspan="2" >ALIMENTACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-body-row">
                    <td class="table-body-cell text-bold"width: 40%;">Tipo de alimentación:</td>
                    <td class="table-body-cell">
                        @foreach ($hotels as $hotel)
                            {{ $hotel->food ?? 'N/A' }}
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="first">
    <div class="second">
        <table class="basic-table">
            <thead>
                <tr class="table-header">
                    <th colspan="2">PERTENENCIAS</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-body-row">
                    <td class="table-body-cell text-bold"width: 40%;">Tipo de objetos:</td>
                    <td class="table-body-cell">
                        @foreach ($hotels as $hotel)
                            {{ $hotel->objects ?? 'N/A' }}
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="second">
        <table class="basic-table">
            <thead>
                <tr class="table-header">
                    <th colspan="2">OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-body-row">
                    <td class="table-body-cell text-bold"width: 40%;">Detalles:</td>
                    <td class="table-body-cell">
                        @foreach ($hotels as $hotel)
                            {{ $hotel->observations ?? 'N/A' }}
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<div>
    <table>
        <tr>
            <th style="width: 10%; text-align: right;">
                <p class="data">Folio de Pago: {{ $reception->payment->folio_odv }} </p>
            </th>
        </tr>
    </table>
</div>

    <div>
        <table class="table-bordered">
            <thead>
                <tr class="table-header">
                    <th colspan="4" class="tableup" style="text-align: center">SERVICIOS</th>
                </tr>
            </thead>
            <tr >
                <th class="tableup" style="text-align: center">Servicio</th>
                <th class="tableup" style="text-align: center">Dias</th>
                <th class="tableup" style="text-align: center">Precio</th>
                <th class="tableup" style="text-align: center">Precio total</th>
            </tr>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($hotels as $hotel)
                    @php
                       $price = ($hotel->servicie->PRECIO ?? 0) * ($hotel->number_days ?? 0);
                       $total += $price;
                    @endphp
                    <tr>
                        <td class="fillable">{{ $hotel->serv->NOMBRE ?? 'N/A' }}</td>
                        <td class="fillable" style="text-align: center">
                            {{ $hotel->number_days ?? '0' }}</td>
                        <td class="fillable" style="text-align: right">
                            ${{ number_format(($hotel->servicie->PRECIO ?? 0), 2) }}
                            </td>
                        <td class="fillable" style="text-align: right">
                        ${{ number_format(($hotel->number_days ?? 0) *($hotel->servicie->PRECIO ?? 0), 2) }}
                        </td>
                          
                    </tr>
                @endforeach
            </tbody>
          
            <tfoot>
                <tr>
                    <td style="text-align: right" colspan="3">
                        <p class="total">Total:</p>
                    </td>
                    <td style="text-align: right">
                        <p class="total">${{ number_format($total, 2) }}</p> 
                </tr>
            </tfoot>
        </table>

    </div>


    <div  style="border: 1px solid #9c4133; margin-top: 1.5%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <thead>
                <tr class="table-header">
                    <th colspan="2">NOTA</th>
                </tr>
            </thead>
                <td style="text-align: justify;  ">
                    <p class="aclarations" >
                         LA INCUBACIÓN DE AGENTES VIRALES O BACTERIANAS QUE PROVOCAN ENFERMEDADES CANINAS Y FELINAS, ES
                        DE APROXIMADAMENTE 15 (QUINCE) DIAS, POR LO QUE AL RECIBIR A SU MASCOTA PARA CUALQUIER SERVICIO,
                        PETS CARE NO SE HACE RESPONSABLE SI EN ESE MOMENTO DICHA MASCOTA VIENE INFECTADA: AUN Y CUANDO
                        NO SE APRECIE NINGÚN SINTOMA CLÍNICO APARENTE. <br>
                        EN ALGUNOS CASOS, LAS MASCOTAS SE PRESENTAN NERVIOSAS, DE EDAD AVANZADA O INCLUSIVE ENFERMAS,
                        AUNQUE USTED LO DESCONOZCA; LO CUAL PUEDE PRODUCIR COMPLICACIONES AL MOMENTO DE REALIZAR
                        NUESTROS SERVICIOS Y EN CASOS SEVEROS DE ESTRÉS, HASTA LA MUERTE DEL PACIENTE. <br>
                        ALGUNAS MASCOTAS SE PRESENTAN EN CONDICIONES DE HIGIENE MUY MALAS, CON NUDOS, PARASITOS
                        EXTERNOS, ETC. POR LO QUE AL EFECTUAR EL SERVICIO DE RAPADO TOTAL, LA NAVAJA DE LA MAQUINA
                        PODRÍA CAUSAR ALGUNAS LESIONES, LAS CUALES NO REPRESENTAN NINGÚN PELIGRO PARA SU MASCOTA, LA
                        CUAL SANARA EN UN PERIODO NO MAYOR A 72 HORAS. <br>
                        EN FUNCIÓN DE BRINDARLE UN MEJOR SERVICIO, LE PEDIMOS SER PUNTUAL AL MOMENTO DE RECOGER A SU
                        MASCOTA, EN FECHA Y HORA. SI POR ALGÚN MOTIVO, NO PUDIERA HACERLO EL MISMO DÍA, OCASIONARA UN
                        CARGO EXTRA POR PENSIÓN. AUTORIZO EL APARTADO DE INSTRUCCIONES ESPECIALES EN DONDE SE ESPECIFICA
                        EL SERVICIO A REALIZAR A MI MASCOTA. <br>
                        <br>
                       ESTOY DE ACUERDO Y ENTERADO DE DICHAS ACLARACIONES Y AUTORIZO A PETS CARE A REALIZAR DICHOS SERVICIOS A MI MASCOTA.
                    </p>
                </td>
            </tr>
        </table>
    </div>



    <div>
        <p class="alarm" style="margin-top: 2%;"><b>FIRMA DEL PROPIETARIO:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="300" height="100"></canvas>
        @endif
    </div>

       @if (!isset($isPdf) || !$isPdf)
    <div>
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <td style="width: 10%;"><form> <button  class="btnEnviar btn btn-lmx">Aceptar</button> </form></td>
                <td style="width: 10%; text-align:left"> <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button></td>
            </tr>
        </table>
    </div>
    @endif

    <div  style="border: 1px solid #9c4133; margin-top: 1.5%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <thead>
                <tr class="table-header">
                    <th colspan="2">NOTA IMPORTANTE</th>
                </tr>
            </thead>
            <tr>
                <td style="text-align: justify;  ">
                    <p class="aclarations">
                       EN FUNCIÓN DE BRINDARLE UN MEJOR SERVICIO, LE ROGAMOS TOMAR EN CUENTA LO SIGUIENTE: <br>
                       1.- LA CONFIRMACIÓN DE LA SALIDA DE SU MASCOTYA SE HARA DE LUNES A SABADO DE 9:00 am A 1:00pm. <br>
                       2.- LA ENTREGA SERA DE 12:00pm A 6:00pm DE LUNES A SABADO. <br>
                       3.- EL BAÑO DE SU MASCOTA SERA OBLIGATORIO AL SALIR, EL COSTO DE ESTE BAÑO SE AGREGARA A SU CUENTA DE PENSION
                      <br> MUCHAS GRACIAS POR SU CONFIANZA.
                    </p>
                </td>
            </tr>
        </table>
    </div>


    <input type="hidden" value="{{ route('hotel.pdf', $reception->id) }}" id="reception">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/formats/pension.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
   
</body>


</html>
