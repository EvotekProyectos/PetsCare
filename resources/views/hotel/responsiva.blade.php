<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @routes
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #181c18;
            font-size: 11px;
        }

        .label {
            color: #0455a0;
            font-weight: bold;
            font-size: 11px;
        }

        .value {
            color: #696969;
            font-size: 11px;
        }

        .section-header {
            background-color: #0455a0;
            color: #ffffff;
            font-weight: bold;
            padding: 6px 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #e3e9ed;
        }

        .info-table td.no-border {
            border-bottom: none;
        }

        .titles {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #0455a0;
            letter-spacing: 0.06em;
            border-bottom: 2px solid #0455a0;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
        }

        .services-table thead th {
            background-color: #0455a0;
            color: #ffffff;
            padding: 6px 10px;
        }

        .services-table tbody td {
            padding: 6px 10px;
        }

        .services-table tfoot td {
            border-top: 2px solid #0455a0;
            font-weight: bold;
            color: #0455a0;
            padding: 6px 10px;
        }

        .note-box {
            border: 1px solid #e3e9ed;
            border-radius: 4px;
            padding: 10px 14px;
            margin-top: 14px;
        }

        .note-title {
            font-size: 11px;
            font-weight: bold;
            color: #0455a0;
            margin-bottom: 4px;
        }

        .alarm-text {
            font-size: 9px;
            color: #973121;
            text-align: justify;
            line-height: 1.5;
        }

        .info-text {
            font-size: 9px;
            color: #696969;
            text-align: justify;
            line-height: 1.5;
        }

        .folio-box {
            border: 1px solid #0455a0;
            border-radius: 4px;
            padding: 6px 10px;
            text-align: center;
        }

        .folio-label {
            font-size: 9px;
            color: #0455a0;
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        .folio-value {
            font-size: 14px;
            color: #0455a0;
            font-weight: bold;
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 22%; vertical-align: middle;">
                @if ($isPdf ?? false)
                    <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 70px">
                @else
                    <img src="{{ asset('img/logo-petscare.png') }}" style="height: 70px;">
                @endif
            </td>
            <td style="width: 56%; text-align: center; vertical-align: middle; font-size: 9px; color: #696969; line-height: 1.5;">
                Blvd. Luis Donaldo Colosio #764 <br>
                Tels. 844 485 1999, 844 485 1979, 844 412 9444 y 844 431 3838 <br>
                equipomedicopetscare@gmail.com
            </td>
            <td style="width: 22%; vertical-align: middle;">
                <div class="folio-box">
                    <div class="folio-label">FOLIO</div>
                    <div class="folio-value">{{ str_pad($hotel->folio, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="titles">RESPONSIVA PENSIÓN</div>

    <table class="info-table">
        <tr class="section-header">
            <td colspan="2">DATOS DE LA FAMILIA</td>
            <td colspan="2" style="border-left: 1px solid rgba(255,255,255,.3);">DATOS DE LA MASCOTA</td>
        </tr>
        <tr>
            <td class="label" style="width: 15%;">Familia:</td>
            <td class="value" style="width: 35%;">{{ $reception->pet->family->name }}</td>
            <td class="label" style="width: 15%; border-left: 1px solid #e3e9ed;">Nombre:</td>
            <td class="value" style="width: 35%;">{{ $reception->pet->name }}</td>
        </tr>
        <tr>
            <td class="label">Domicilio:</td>
            <td class="value">{{ $reception->pet->family->address }}</td>
            <td class="label" style="border-left: 1px solid #e3e9ed;">Especie:</td>
            <td class="value">{{ $reception->pet->specie }}</td>
        </tr>
        <tr>
            <td class="label">Teléfono:</td>
            <td class="value">{{ $reception->pet->family->phone }}</td>
            <td class="label" style="border-left: 1px solid #e3e9ed;">Raza:</td>
            <td class="value">{{ $reception->pet->raza }}</td>
        </tr>
        <tr>
            <td class="label">Contacto emerg.:</td>
            <td class="value">{{ $reception->pet->family->contact_name }}</td>
            <td class="label" style="border-left: 1px solid #e3e9ed;">Peso:</td>
            <td class="value">{{ $reception->pet->weight }}</td>
        </tr>
        <tr>
            <td class="label no-border">Tel. emergencia:</td>
            <td class="value no-border">{{ $reception->pet->family->contact_number }}</td>
            <td class="label no-border" style="border-left: 1px solid #e3e9ed;">Sexo:</td>
            <td class="value no-border">{{ $reception->pet->genre->name }}</td>
        </tr>
    </table>

    <table class="info-table">
        <tr class="section-header">
            <td colspan="2">VACUNACIÓN</td>
            <td colspan="2" style="border-left: 1px solid rgba(255,255,255,.3);">ALIMENTACIÓN</td>
        </tr>
        <tr>
            <td class="label" style="width: 15%;">Edad:</td>
            <td class="value" style="width: 35%;">
                @php
                    $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                    $years = $birthday->diffInYears(now());
                    $months = $birthday->copy()->addYears($years)->diffInMonths(now());
                @endphp
                {{ $years }} años, {{ $months }} meses
            </td>
            <td class="label" style="width: 15%; border-left: 1px solid #e3e9ed;">Tipo:</td>
            <td class="value" style="width: 35%;">{{ $hotel->food ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label no-border">Carnet:</td>
            <td class="value no-border">
                <a href="{{ route('certificate.imprimir', $reception->pet->id) }}" style="color: #0455a0; font-weight: bold; text-decoration: none;">Sí</a>
            </td>
            <td class="no-border" style="border-left: 1px solid #e3e9ed;"></td>
            <td class="no-border"></td>
        </tr>
    </table>

    <table class="info-table">
        <tr class="section-header">
            <td colspan="2">PERTENENCIAS</td>
            <td colspan="2" style="border-left: 1px solid rgba(255,255,255,.3);">OBSERVACIONES / FECHAS</td>
        </tr>
        <tr>
            <td class="label" style="width: 15%;">Objetos:</td>
            <td class="value" style="width: 35%;">{{ $hotel->objects ?? 'N/A' }}</td>
            <td class="label" style="width: 15%; border-left: 1px solid #e3e9ed;">Entrada:</td>
            <td class="value" style="width: 35%;">
                {{ $reception->entry_date ? \Carbon\Carbon::parse($reception->entry_date)->format('d-m-Y H:i') : '—' }}
            </td>
        </tr>
        <tr>
            <td class="label no-border">Detalles:</td>
            <td class="value no-border">{{ $hotel->observations ?? 'N/A' }}</td>
            <td class="label no-border" style="border-left: 1px solid #e3e9ed;">Salida:</td>
            <td class="value no-border">
                {{ $reception->exit_date ? \Carbon\Carbon::parse($reception->exit_date)->format('d-m-Y H:i') : '—' }}
            </td>
        </tr>
    </table>

    <div style="text-align: right; font-size: 10px; color: #696969; margin-bottom: 10px;">
        <span class="label">Folio de pago:</span> {{ $reception->payment->folio_odv ?? '—' }}
    </div>

    <table class="services-table">
        <thead>
            <tr>
                <th style="text-align: left;">Servicio</th>
                <th style="text-align: center;">Días</th>
                <th style="text-align: right;">Precio/día</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @if ($hotel)
                @php
                    $price = ($hotel->servicie->PRECIO ?? 0) * ($hotel->number_days ?? 0);
                    $total += $price;
                @endphp
                <tr>
                    <td>{{ $hotel->serv->NOMBRE ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $hotel->number_days ?? '0' }}</td>
                    <td style="text-align: right;">${{ number_format($hotel->servicie->PRECIO ?? 0, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($price, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="4" style="text-align: center;">No hay registros disponibles</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Total:</td>
                <td style="text-align: right;">${{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="note-box">
        <div class="note-title">NOTA</div>
        <p class="alarm-text">
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
            EL SERVICIO A REALIZAR A MI MASCOTA. <br><br>
            ESTOY DE ACUERDO Y ENTERADO DE DICHAS ACLARACIONES Y AUTORIZO A PETS CARE A REALIZAR DICHOS
            SERVICIOS A MI MASCOTA.
        </p>
    </div>

    <div style="page-break-before: always;"></div>

    <div style="text-align: center; margin-top: 20px;">
        <p class="label" style="margin-bottom: 6px;">Firma del propietario:</p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100" style="border-bottom: 2px solid #181c18;"></canvas>
        @endif

        @if (!isset($isPdf) || !$isPdf)
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 20px;">
                <button class="btnLimpiar btn btn-lmx" data-target="canvas" style="width: 100px;">Limpiar</button>
                <form>
                    <button class="btnEnviar btn btn-lmx" style="width: 100px;">Aceptar</button>
                </form>
            </div>
        @endif
    </div>

    <div class="note-box">
        <div class="note-title">NOTA IMPORTANTE</div>
        <p class="info-text">
            EN FUNCIÓN DE BRINDARLE UN MEJOR SERVICIO, LE ROGAMOS TOMAR EN CUENTA LO SIGUIENTE: <br>
            1.- LA CONFIRMACIÓN DE LA SALIDA DE SU MASCOTA SE HARA DE LUNES A SABADO DE 9:00 am A 1:00pm. <br>
            2.- LA ENTREGA SERA DE 12:00pm A 6:00pm DE LUNES A SABADO. <br>
            3.- EL BAÑO DE SU MASCOTA SERA OBLIGATORIO AL SALIR, EL COSTO DE ESTE BAÑO SE AGREGARA A SU
            CUENTA DE PENSION. <br>
            MUCHAS GRACIAS POR SU CONFIANZA.
        </p>
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