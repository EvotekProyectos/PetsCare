<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @routes
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #2c2c2a;
            font-size: 11px;
        }

        .label {
            color: #5b3e8c;
            font-weight: bold;
            font-size: 11px;
        }

        .value {
            color: #2c2c2a;
            font-size: 11px;
        }

        .section-title {
            color: #5b3e8c;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .info-box {
            border: 1px solid #e3d9f2;
            border-radius: 4px;
            padding: 10px 14px;
        }

        .info-table td {
            padding: 3px 0;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
        }

        .services-table thead th {
            background-color: #5b3e8c;
            color: #ffffff;
            padding: 6px 10px;
        }

        .services-table tbody td {
            padding: 6px 10px;
        }

        .services-table tbody tr.alt {
            background-color: #f7f4fb;
        }

        .services-table tfoot td {
            border-top: 2px solid #5b3e8c;
            font-weight: bold;
            color: #5b3e8c;
            padding: 6px 10px;
        }

        .aclarations-text {
            font-size: 9px;
            color: #6b7a86;
            text-align: justify;
            line-height: 1.5;
        }

        .folio-box {
            border: 1px solid #5b3e8c;
            border-radius: 4px;
            padding: 6px 10px;
            text-align: center;
        }

        .folio-label {
            font-size: 9px;
            color: #5b3e8c;
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        .folio-value {
            font-size: 14px;
            color: #5b3e8c;
            font-weight: bold;
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; margin-bottom: 14px;">
        <tr>
            <td style="width: 22%; vertical-align: middle;">
                <img src="{{ public_path('img/logo-petscare.png') }}" style="height: 70px;">
            </td>
            <td style="width: 56%; text-align: center; vertical-align: middle; font-size: 9px; color: #6b7a86; line-height: 1.5;">
                Blvd. Luis Donaldo Colosio #764 <br>
                Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                equipomedicopetscare@gmail.com
            </td>
            <td style="width: 22%; vertical-align: middle;">
                <div class="folio-box">
                    <div class="folio-label">FOLIO</div>
                    <div class="folio-value">{{ str_pad($general->folio, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-size: 16px; font-weight: bold; color: #5b3e8c; letter-spacing: 0.08em; border-bottom: 2px solid #5b3e8c; padding-bottom: 8px; margin-bottom: 12px;">
        ESTÉTICA
    </div>

    <div class="info-box" style="margin-bottom: 14px;">
        <table class="info-table" style="width: 100%; border-collapse: collapse;">
            <tr>
                <td class="label" style="width: 15%;">Familia:</td>
                <td class="value" style="width: 35%;">{{ $reception->pet->family->name }}</td>
                <td class="label" style="width: 15%;">Domicilio:</td>
                <td class="value" style="width: 35%;">{{ $reception->pet->family->address }}</td>
            </tr>
            <tr>
                <td class="label">Correo:</td>
                <td class="value">{{ $reception->pet->family->email }}</td>
                <td class="label">Teléfono:</td>
                <td class="value">{{ $reception->pet->family->phone }}</td>
            </tr>
            <tr>
                <td class="label">Mascota:</td>
                <td class="value">{{ $reception->pet->name }}</td>
                <td class="label">Especie:</td>
                <td class="value">{{ $reception->pet->specie }}</td>
            </tr>
            <tr>
                <td class="label">Raza:</td>
                <td class="value">{{ $reception->pet->raza }}</td>
                <td class="label">Sexo:</td>
                <td class="value">{{ $reception->pet->genre->name }}</td>
            </tr>
            <tr>
                <td class="label">Descripción:</td>
                <td class="value" colspan="3">{{ $reception->pet->physic_descrip }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">SERVICIOS A REALIZAR</div>
    <table class="services-table">
        <thead>
            <tr>
                <th style="text-align: left;">Servicio</th>
                <th style="text-align: right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($groomings as $index => $grooming)
                @php
                    $price = $grooming->service->PRECIO ?? 0;
                    $total += $price;
                @endphp
                <tr class="{{ $index % 2 == 0 ? 'alt' : '' }}">
                    <td>{{ $grooming->serv->NOMBRE ?? 'N/A' }}</td>
                    <td style="text-align: right;">${{ number_format($price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: right;">Total:</td>
                <td style="text-align: right;">${{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="info-box" style="margin: 14px 0;">
        <div class="section-title" style="margin-bottom: 4px;">INSTRUCCIONES</div>
        <p class="aclarations-text" style="font-style: italic;">{{ $general->instructions }}</p>
    </div>

    <table style="width: 100%; margin-bottom: 14px;">
        <tr>
            <td class="label" style="width: 15%;">Llegada:</td>
            <td class="value" style="width: 35%;">
                {{ $reception->entry_date ? \Carbon\Carbon::parse($reception->entry_date)->format('d-m-Y H:i') : '—' }}
            </td>
            <td class="label" style="width: 20%;">Entrega:</td>
            <td class="value" style="width: 30%;">
                {{ $reception->exit_date ? \Carbon\Carbon::parse($reception->exit_date)->format('d-m-Y H:i') : '—' }}
            </td>
        </tr>
        <tr>
            <td class="label">Servicio:</td>
            <td class="value">{{ $general->delivery_service == 1 ? 'Domicilio' : 'En tienda' }}</td>
            <td class="label">Próximo servicio:</td>
            <td class="value">{{ $general->next_service }}</td>
        </tr>
        <tr>
            <td class="label">Folio de Pago:</td>
            <td class="value">{{ $reception->payment->folio_odv ?? '—' }}</td>
        </tr>
    </table>

    <div style="page-break-before: always;"></div>

    <div class="info-box" style="margin-bottom: 14px;">
        <div class="section-title" style="margin-bottom: 4px;">ACLARACIONES</div>
        <p class="aclarations-text">
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
        <p class="label" style="text-align: justify; margin-top: 8px;">
            ESTOY DE ACUERDO Y ENTERADO DE DICHAS ACLARACIONES Y AUTORIZO
            A PETS CARE A REALIZAR DICHOS SERVICIOS A MI MASCOTA
        </p>
    </div>

    <div>
        <p class="label" style="margin-top: 8px;">Firma del propietario:</p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="300" height="100"></canvas>
        @endif
    </div>

    @if (!isset($isPdf) || !$isPdf)
        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 10%;">
                        <form> <button class="btnEnviar btn btn-lmx">Aceptar</button> </form>
                    </td>
                    <td style="width: 10%; text-align:left">
                        <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/groomings/pdf.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
        const SERVICE_TYPE = "{{ $general->delivery_service }}";
        const CRITIC_STATUS = "{{ $general->critic_status }}";
    </script>
</body>

</html>