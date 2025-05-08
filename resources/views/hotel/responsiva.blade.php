<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    <style>
        :root {
  --primary-blue: #0455a0;
  --text-dark: #181c18;
  --secondary-grey: #696969;
  --border-grey: #776d6d;
}
        .data {
            font-family: sans-serif;
            font-size: 11pt;
            color: 
#181c18  ;
            font-weight: normal;
            margin: 0;
        }

        .fillable {
            font-family: sans-serif;
            font-size: 11pt;
            color: #696969;
            font-weight: normal;
            margin: 0;
        }

        .aclarations {
            font-family: sans-serif;
            font-size: 9pt;
            color: 
#112220;
            /* font-weight: lighter;  */
            padding-right: 15px;
            padding-left: 15px;
             line-height: 1.5;
        }

        .tableup {
            font-family: sans-serif;
            font-size: 10pt;
            color: #181c18;
            text-align: left;
            
        }

        .titles {
            font-family: sans-serif;
            font-size: 15pt;
            color: #0455a0  ;
            font-weight: bold;
        }

        .alarm {
            font-family: sans-serif;
            font-style: italic;
            font-size: 10pt;
            color: #973121;
            font-weight: semibold;
        }

        .total {
            font-family: sans-serif;
            font-style: italic;
            font-size: 12pt;
            color: #818080;
            font-weight: bold;
            margin: 0;
        }

        .table-bordered {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1%;
            border: 1px solid 
#96a0a6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid 
#96a0a6;
            padding: 5px;
        }

        .table-header {
    border-bottom: 1px solid #96a0a6;
    font-family: sans-serif;
    background-color: #ddf7ff;
    font-weight: bold;
    color: white;
    padding: 8px;
}

.button {
        background-color: 
#0455a0 ; 
        color: #fff; 
        border: none;  
        border-radius: 5px; 
        padding: 10px 20px; 
        font-size: 16px; 
        cursor: pointer; 
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); 
        transition: all 0.3s ease; 
    }

    .button:hover {
        background-color: #5577a5; 
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.3); 
    }

    .div-first {
  border: 1px solid #96a0a6;
  font-size: 13px;
  margin-top: 20px;
  width: 100%;
  font-family: sans-serif;
}

.table-100 {
  width: 100%;
  border-collapse: collapse;
}

.table-100-tr {
  background-color: #0455a0;
  color: white;
  /* border-bottom: 2px solid #96a0a6; */
}

.tr-basic{
    border-bottom: 1px solid #96a0a6;
}


    </style>
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
                    @if($isPdf ?? false)
                        <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px">
                        @else
                        <img src="{{ asset('img/logo-petscare.png') }}" style="height: 87px;">
                    @endif
                </td>
                
                <td style="text-align: center;">
                    <p style="font-family: sans-serif; font-weight: bold; font-size: 10pt; color:var(--primary-blue);">
                        Blvd. Luis Donaldo Colosio #764 <br>
                        Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                        equipomedicopetscare@gmail.com
                    </p>
                </td>
                <td style="text-align: center; border: 1px solid #0455a0;">
                    <p style="color: #0455a0; font-family: sans-serif;">FOLIO</p>
                    <hr style="color: #0455a0; width: 100%; margin-bottom: -7%; margin-top: -7%;">
                    <p
                        style="font-family: 'Times New Roman', Times, serif; font-weight: lighter; font-size: 12pt; color: #776d6d;">
                        {{ str_pad($hotel->folio, 4, '0', STR_PAD_LEFT) }}
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
                        <p class="titles">RESPONSIVA PENSIÓN</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div class="div-first" style="margin-top:-0.5%;">
        <table class="table-100">
            <thead>
                <tr  class="table-100-tr">
                    <th colspan="2" style="padding: 10px; text-align: left;">DATOS DE LA FAMILIA</th>
                    <th colspan="2" style="padding: 10px; border-left: 2px solid #96a0a6;  text-align: left">DATOS DE LA MASCOTA</th>
            </thead>
            <tr class="tr-basic">
                <th style="width: 15%; text-align: left;">
                    <p class="data">Familia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->name }} </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                    <p class="data">Nombre:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->name }} </p>
                </th>
            </tr>
            <tr class="tr-basic">
                <th style="width: 15%; text-align: left;">
                    <p class="data">Domicilio:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->address }} </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                    <p class="data">Especie:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->specie }} </p>
                </th>
            </tr>
            <tr class="tr-basic">
                <th style="width: 15%; text-align: left;">
                    <p class="data">Teléfono:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->phone }} </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                    <p class="data">Raza:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->raza }} </p>
                </th>
            </tr>
            <tr class="tr-basic">
                <th style="width: 15%; text-align: left;">
                    <p class="data">Contacto de emergencia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->contact_name }} </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                    <p class="data">Peso:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->weight }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Telefóno emergencia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->contact_number }} </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                    <p class="data">Sexo:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->genre->name }} </p>
                </th>
            </tr>
        </table>

    </div>
    

    
    <div class="div-first">
        <table class="table-100">
            <thead>
                <tr class="table-100-tr">
                    <th colspan="2" style="padding: 10px; text-align: left;">RECORD DE VACUNACION</th>
                    <th colspan="2" style="padding: 10px; border-left: 2px solid #96a0a6; text-align: left;">ALIMENTACION</th>
            </thead>
            <tr>
                <th class="tr-basic" style="width: 15%; text-align: left;">
                    <p class="data">Edad:</p>
                </th>
                <th  class="tr-basic" style="width: 35%; text-align: left; border-right: 1px solid #96a0a6;">
                    <p class="fillable">  @php
                        $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                        $now = \Carbon\Carbon::now();
                        $years = $birthday->diffInYears($now);
                        $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                        @endphp
                        {{ $years }} años, {{ $months }} meses </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Tipo de alimentación:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> 
                        {{ $hotel->food ?? 'N/A' }}
               </p>
                </th>
            </tr>
            <tr >
                <th style="width: 15%; text-align: left;">
                    <p class="data">Carnet:</p>
                </th>
                <th style="width: 35%; text-align: left;  border-right: 1px solid #96a0a6;">
                    <p class="fillable">  <a href="{{ route('certificate.imprimir', $reception->pet->id) }}" style="color: #0455a0 ; text-decoration: none; font-weight: bold; ">Sí</a> </p>
                </th>
                <th style="width: 15%; text-align: left;">
                </th>
                <th >
                    {{-- <p class="fillable">  <a href="{{ route('certificate.imprimir', $reception->pet->id) }}" style="color: #0455a0 ; text-decoration: none; font-weight: bold;  border-right: 1px solid #96a0a6;">Sí</a> </p> --}}
                </th>
            </tr>
        </table>

    </div>

    <div class="div-first">
        <table class="table-100">
            <thead>
                <tr class="table-100-tr">
                    <th colspan="2" style="padding: 10px;  text-align: left">PERTENENCIAS</th>
                    <th colspan="2" style="padding: 10px; border-left: 2px solid #96a0a6;  text-align: left">OBSERVACIONES</th>
            </thead>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Tipo de objetos:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">    
                        {{ $hotel->objects ?? 'N/A' }}
                  </p>
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6; border-bottom: 1px solid #96a0a6;">
                    <p class="data">Detalles:</p>
                </th>
                <th style="width: 30%; text-align: left; border-bottom: 1px solid #96a0a6;">
                    <p class="fillable">   
                        {{ $hotel->observations ?? 'N/A' }}
                  </p>
                </th>
            </tr>
            <tr>
                <th> 
                                </th>
                                <th> 
                                </th>
                                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6; border-bottom: 1px solid #96a0a6;">
                                    <p class="data">Fecha de entrada:</p>
                                </th>
                                <th style="width: 30%; text-align: left; border-bottom: 1px solid #96a0a6;">
                                    <p class="fillable">  {{ (new DateTime($reception->entry_date))->format('d-m-Y h:i') }} </p>
                                </th>
            </tr>
            <tr>
                <th> 
                </th>
                <th> 
                </th>
                <th style="width: 20%; text-align: left; border-left: 1px solid #96a0a6;">
                                    <p class="data">Fecha de salida:</p>
                                </th>
                                <th style="width: 30%; text-align: left;">
                                    <p class="fillable"> {{ (new DateTime($reception->exit_date))->format('d-m-Y h:i') }} </p>
                                </th>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 0%;">
        <table class="table-100" style="margin-top: 2%; margin-bottom: 0%;">
            <tr>
                <td style="width: 50%; text-align: right;">
                    <p>
                        <span class="data">Folio de Pago:</span> 
                        <span class="fillable">{{ $reception->payment->folio_odv }}</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
    

    <div style="margin-top:0%;">
        <table class="table-bordered" style="margin-top:0%;">
            <thead>
                <tr class="table-100-tr" style="font-weight: bold; ">
                    <th colspan="4" class="tableup" style="text-align: center; color:white; padding: 10px; font-size: 16px; ">SERVICIOS</th>
                </tr>
               
            </thead>
            <tr class="tr-basic">
                <th class="tableup" >Servicio</th>
                <th class="tableup" >Dias</th>
                <th class="tableup" >Precio por día</th>
                <th class="tableup" >Precio total</th>
            </tr>
            {{-- <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($hotels as $hotel)
                    @php
                       $price = ($hotel->servicie->PRECIO ?? 0) * ($hotel->number_days ?? 0);
                       $total += $price;
                    @endphp
                    <tr class="tr-basic">
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
            </tbody> --}}
            <tbody>
                @php
                    $total = 0;
                @endphp
            
                @if ($hotel) {{-- Verifica que $hotel no sea null antes de acceder a sus propiedades --}}
                    @php
                        $price = ($hotel->servicie->PRECIO ?? 0) * ($hotel->number_days ?? 0);
                        $total += $price;
                    @endphp
                    <tr class="tr-basic">
                        <td class="fillable">{{ $hotel->serv->NOMBRE ?? 'N/A' }}</td>
                        <td class="fillable" style="text-align: center">
                            {{ $hotel->number_days ?? '0' }}
                        </td>
                        <td class="fillable" style="text-align: right">
                            ${{ number_format(($hotel->servicie->PRECIO ?? 0), 2) }}
                        </td>
                        <td class="fillable" style="text-align: right">
                            ${{ number_format($price, 2) }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay registros disponibles</td>
                    </tr>
                @endif
            </tbody>
            
            <tfoot>
                <tr class="tr-basic">
                    <td style="text-align: right" colspan="3">
                        <p class="total">Total:</p>
                    </td>
                    <td style="text-align: right">
                        <p class="total">${{ number_format($total, 2) }}</p> 
                </tr>
            </tfoot>
        </table>
    </div>


    <div  style="border: 1px solid #96a0a6; margin-top: 1.5%; width: 100%; page-break-before: always;">
        <table class="table-100">
            <thead style=" border-bottom: 1px solid #96a0a6; ">
                <tr class="table-header" style=" border-bottom: 1px solid #96a0a6;">
                    <th colspan="2"  class="tr-basic" style="color:#0455a0; padding: 10px; font-size: 16px; ">NOTA</th>
                </tr>
            </thead>
            <tr  >
                <td style="text-align: justify; ">
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

    <div style="text-align: center;">
        <p style="margin-top: 40px;  font-family: sans-serif; color: #181c18 ;"><b>Firma del propietario:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100" style="border-bottom: 2px solid #181c18 ;"></canvas>
        @endif

    <div style="margin-top: 20px; text-align: center;">
            @if (!isset($isPdf) || !$isPdf)
                <div style="display: flex; justify-content: center; gap: 20px;">

                    <div style="align-self: flex-start;">
                        <button class="btnLimpiar btn btn-lmx button" data-target="canvas" style="width: 100px;">Limpiar</button>
                    </div>
                    <div>
                        <form>
                            <button class="btnEnviar btn btn-lmx button" style="width: 100px;">Aceptar</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div  style="border: 1px solid#96a0a6; margin-top: 1.5%; width: 100%">
        <table class="table-100">
            <thead style=" border-bottom: 1px solid #96a0a6;">
                <tr class="table-header" style=" border-bottom: 1px solid #96a0a6;">
                    <th colspan="2"  class="tr-basic" style="color:#0455a0; padding: 10px; font-size: 16px; ">NOTA IMPORTANTE</th>
                </tr>
            </thead>
            <tr>
                <td style="text-align: justify;  ">
                    <p class="aclarations" style="line-height: 1.5;">
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


  
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/formats/pension.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>


</html>
