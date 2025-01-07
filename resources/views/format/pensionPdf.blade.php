<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    <style>
        .data {
            font-family: sans-serif;
            font-size: 11pt;
            color: #bb4415;
            font-weight: normal;
            margin: 0;
        }

        .fillable {
            font-family: sans-serif;
            font-size: 11pt;
            color: #b3aeae;
            font-weight: normal;
            margin: 0;
        }

        .aclarations {
            font-family: sans-serif;
            font-size: 9pt;
            color: #9779b3;
            font-weight: lighter margin: 0;
        }

        .tableup {
            font-family: sans-serif;
            font-size: 12pt;
            color: #bb4415;
            font-weight: semibold;
        }

        .titles {
            font-family: sans-serif;
            font-size: 15pt;
            color: #bb4415;
            font-weight: bold;
        }

        .alarm {
            font-family: sans-serif;
            font-style: italic;
            font-size: 10pt;
            color: #bb4415;
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
            border: 1px solid #bb4415;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #bb4415;
            padding: 5px;
        }
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('pension.pdf', $reception->id) }}" id="reception">
    <div>
        <table style="width: 100%; ">
            <tr>
                <th style="width: 20%"> </th>
                <th style="width: 60%"></th>
                <th style="width: 20%"></th>
            </tr>
            <tr>
                <td style="align-items: center">
                    {{-- <img src="{{ public_path('img/logo-petscare.png') }}" style=" height: 87px;"> --}}
                </td>
                <td style="text-align: center;">
                    <p style="font-family: sans-serif; font-weight: bold; font-size: 10pt; color: #bb4415;">
                        Blvd. Luis Donaldo Colosio #764 <br>
                        Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                        equipomedicopetscare@gmail.com
                    </p>
                </td>
                <td style="text-align: center; border: 1px solid #bb4415;">
                    <p style="color: #bb4415; font-family: sans-serif;">FOLIO</p>
                    <hr style="color: #bb4415; width: 100%; margin-bottom: -7%; margin-top: -7%;">
                    <p
                        style="font-family: 'Times New Roman', Times, serif; font-weight: lighter; font-size: 12pt; color: #776d6d;">
                        {{-- {{ str_pad($reception->id, 4, '0', STR_PAD_LEFT) }} --}}
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top:-2%;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center; width: 100%; color: #bb4415;">
                        <p class="titles">PENSIÓN</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    
    <div style="border: 2px solid #bb4415; padding: 5px; width: 100%; box-sizing: border-box;">
        <div style="border: 2px solid #bb4415; padding: 10px; width: 100%; box-sizing: border-box;">
            <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Familia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">DATOS DE LA MASCOTA</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable">   </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Domicilio:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">{{ $reception->pet->family->address }}</p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Nombre:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable">{{ $reception->pet->name }}</p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Teléfono:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">{{ $reception->pet->family->phone }}</p>  </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Especie:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable">{{ $reception->pet->specie }} </p></th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">No. de Cubículo:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">               </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Raza:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->raza }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Fecha de entrada:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">{{ $reception->entry_date }} </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Peso:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable">{{ $reception->pet->weight }} </p>
                </th>
            </tr>

            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Fecha a recoger :</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">               </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Sexo:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->genre->name}} </p>
                </th>
            </tr>

        </table>
    </div>
</div>
<br>

        <div style="display: flex; justify-content: space-between; border: 2px solid #bb4415; padding: 0.5%; width: 100%; box-sizing: border-box;">
            <div style="width: 48%; border: 2px solid #bb4415; padding: 0.5%; box-sizing: border-box;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="width: 50%; text-align: left;">
                            <p class="data">RECORD DE VACUNACIÓN</p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Edad:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">No de Carnet:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Parvo 1:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">Parvo 2:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Corona:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">M.H.L:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Antirrábica:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                </table>
            </div>

            <div style="width: 48%; border: 2px solid #bb4415; padding: 0.5%; box-sizing: border-box;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Observaciones:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                   
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Temperatura:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <br>
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">M.V.Z:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        
        <div style="display: flex; justify-content: space-between; border: 2px solid #bb4415; padding: 0.5%; width: 100%; box-sizing: border-box;">
            <div style="width: 48%; border: 2px solid #bb4415; padding: 0.5%; box-sizing: border-box;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">ALIMENTACIÓN</p>
                        </th>
                       
                    </tr>
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">Tipo de alimento:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">Indicaciones:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        
                    </tr>
                </table>
            </div>

            <div style="width: 48%; border: 2px solid #bb4415; padding: 0.5%; box-sizing: border-box;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">PERTENENCIAS</p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Collar:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Otros:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 20%; text-align: left;">
                            <p class="data">Comedero:</p>
                        </th>
                        <th style="width: 30%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 15%; text-align: left;">
                            <p class="data">Cadena:</p>
                        </th>
                        <th style="width: 35%; text-align: left;">
                            <p class="fillable"></p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

    <div>
        <p class="titles">SERVICIOS</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th class="tableup" style="text-align: center">Servicio</th>
                    <th class="tableup" style="text-align: center">Dias</th>
                    <th class="tableup" style="text-align: center">Precio</th>
                </tr>
            </thead>
            <tbody>
                {{-- @php
                    $total = 0;
                @endphp
                @foreach ($groomings as $grooming)
                    @php
                        $price = $grooming->service->PRECIO ?? 0;
                        $total += $price;
                    @endphp
                    <tr>
                        <td class="fillable">{{ $grooming->serv->NOMBRE ?? 'N/A' }}</td>
                        <td class="fillable">{{ $grooming->notes ?? 'Sin notas' }}</td>
                        <td class="fillable" style="text-align: right">
                            ${{ number_format($grooming->service->PRECIO ?? 0, 2) }}</td>
                    </tr>
                @endforeach --}}
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: right" colspan="2" > <p class="total" >Total:</p></td>
                    {{-- <td style="text-align: left" >
                        <p class="total">${{ number_format($total, 2) }}</p>
                    </td> --}}
                </tr>
            </tfoot>
        </table>

    </div>

    

    <div  style="border: 1px solid #bb4415; margin-top: 1.5%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <td>  <p class="titles">NOTA:  </p></td>
                <td style="text-align: justify;  ">
                    <p class="aclarations" style="color: #bb4415;">
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
                <td style="width: 10%;"><button class="btnEnviar btn btn-lmx">Aceptar</button></td>
                <td style="width: 10%; text-align:left"> <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button></td>
            </tr>
        </table>
    </div>
    @endif

    <div  style="border: 1px solid #bb4415; margin-top: 1.5%; width: 100%">
        <p class="titles" style="margin-bottom: 0">NOTA IMPORTANTE </p>
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <td style="text-align: justify;  ">
                    <p class="aclarations" style="color: #bb4415;">
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
</body>


</html>
