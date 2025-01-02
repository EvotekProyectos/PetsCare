<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    <style>
        .data {
            font-family: sans-serif;
            font-size: 11pt;
            color: #6b4392;
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
            color: #6b4392;
            font-weight: semibold;
        }

        .titles {
            font-family: sans-serif;
            font-size: 15pt;
            color: #6b4392;
            font-weight: bold;
        }

        .alarm {
            font-family: sans-serif;
            font-style: italic;
            font-size: 10pt;
            color: #6b4392;
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
            border: 1px solid #6b4392;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #6b4392;
            padding: 5px;
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
                <td style="align-items: center">
                    <img src="{{ public_path('img/logo-petscare.png') }}" style=" height: 87px;">
                </td>
                <td style="text-align: center;">
                    <p style="font-family: sans-serif; font-weight: bold; font-size: 10pt; color: #6b4392;">
                        Blvd. Luis Donaldo Colosio #764 <br>
                        Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                        equipomedicopetscare@gmail.com
                    </p>
                </td>
                <td style="text-align: center; border: 1px solid #472964;">
                    <p style="color: #6b4392; font-family: sans-serif;">FOLIO</p>
                    <hr style="color: #472964; width: 100%; margin-bottom: -7%; margin-top: -7%;">
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
                    <th style="text-align: center; width: 100%; color: #6b4392;">
                        <p class="titles">ESTETICA</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div style="border: 1px solid #472964; margin-top: .5%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Familia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Domicilio:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->address }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Correo:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->email }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Telefono:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->phone }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Mascota:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Especie:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->specie }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Raza:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->raza }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Sexo:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->genre->name }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Descripción:</p>
                </th>
                <th colspan="3" style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->physic_descrip }} </p>
                </th>

            </tr>
        </table>

    </div>

    <div>
        <p class="titles">SERVICIOS A REALIZAR:</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th class="tableup" style="text-align: center">Servicio</th>
                    <th class="tableup" style="text-align: center">Notas</th>
                    <th class="tableup" style="text-align: center">Precio</th>
                </tr>
            </thead>
            <tbody>
                @php
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
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: right" colspan="2" > <p class="total" >Total:</p></td>
                    <td style="text-align: left" >
                        <p class="total">${{ number_format($total, 2) }}</p>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top: 3%;">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Llegada:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ (new DateTime($reception->entry_date))->format('d-m-Y h:i') }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Entrega:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ (new DateTime($reception->exit_date))->format('d-m-Y h:i') }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Folio de Pago:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->payment->folio_odv }} </p>
                </th>
            </tr>
        </table>
    </div>

    <div  style="border: 1px solid #472964; margin-top: 1.5%; width: 100%">
        <p class="titles">ACLARACIONES</p>
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        LA INCUBACIÓN DE AGENTES VIRALES O BACTERIANAS QUE PROVOCAN ENFERMEDADES CANINAS Y FELINAS, ES
                        DE APROXIMADAMENTE 15 (QUINCE) DIAS, POR LO QUE AL RECIBIR A SU MASCOTA PARA CUALQUIER SERVICIO,
                        PETS CARE NO SE HACE RESPONSABLE SI EN ESE MOMENTO DICHA MASCOTA VIENE INFECTADA: AUN Y CUANDO
                        NO SE APRECIE NINGÚN SINTOMA CLÍNICO APARENTE.
                        EN ALGUNOS CASOS, LAS MASCOTAS SE PRESENTAN NERVIOSAS, DE EDAD AVANZADA O INCLUSIVE ENFERMAS,
                        AUNQUE USTED LO DESCONOZCA; LO CUAL PUEDE PRODUCIR COMPLICACIONES AL MOMENTO DE REALIZAR
                        NUESTROS SERVICIOS Y EN CASOS SEVEROS DE ESTRÉS, HASTA LA MUERTE DEL PACIENTE
                        ALGUNAS MASCOTAS SE PRESENTAN EN CONDICIONES DE HIGIENE MUY MALAS, CON NUDOS, PARASITOS
                        EXTERNOS, ETC. POR LO QUE AL EFECTUAR EL SERVICIO DE RAPADO TOTAL, LA NAVAJA DE LA MAQUINA
                        PODRÍA CAUSAR ALGUNAS LESIONES, LAS CUALES NO REPRESENTAN NINGÚN PELIGRO PARA SU MASCOTA, LA
                        CUAL SANARA EN UN PERIODO NO MAYOR A 72 HORAS.
                        EN FUNCIÓN DE BRINDARLE UN MEJOR SERVICIO, LE PEDIMOS SER PUNTUAL AL MOMENTO DE RECOGER A SU
                        MASCOTA, EN FECHA Y HORA. SI POR ALGÚN MOTIVO, NO PUDIERA HACERLO EL MISMO DÍA, OCASIONARA UN
                        CARGO EXTRA POR PENSIÓN. AUTORIZO EL APARTADO DE SERVICIOS A REALIZAR EN DONDE SE ESPECIFICA
                        EL SERVICIO A REALIZAR A MI MASCOTA
                        EN CASO DE ABANDONO DE LA MASCOTA, POR PARTE DE SU PROPIETARIO, LA EMPRESA TENDRA EL DERECHO DE
                        DECIDIR PONERLO EN ADOPCIÓN
                    </p>
                </td>
            </tr>
        </table>
        <div style="text-align: justify;">
            <p class="alarm">ESTOY DE ACUERDO Y ENTERADO DE DICHAS ACLARACIONES Y AUTORIZO
                A PETS CARE A REALIZAR DICHOS SERVICIOS A MI MASCOTA</p>
        </div>
    </div>
    <div>
        <p class="alarm" style="margin-top: 2%;"><b>Firma del propietario:</b></p>

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
                <td style="width: 10%;"><form> <button class="btnEnviar btn btn-lmx">Aceptar</button> </form></td>
                <td style="width: 10%; text-align:left"> <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button></td>
            </tr>
        </table>
    </div>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/groomings/pdf.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
</body>


</html>
