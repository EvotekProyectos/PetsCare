<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-size: 10pt;
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
        }

        .head {
            color: #3459A4;
            font-weight: 700;
            font-size: 12pt;
            font-family: sans-serif;
        }

        p {
            font-size: 10pt;
            font-family: sans-serif;
            margin: 0;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            text-align: center;
        }

        .header-table td {
            padding: 4px;
        }

        .header-wrapper {
            width: 100%;
            /* border-bottom: 2px solid #3459A4; */
            padding-bottom: 8px;
            margin-bottom: 0;
        }

        .header-inner {
            display: flex;
            /* align-items: center; */
            justify-content: center;
            /* gap: 12px; */
        }

        .header-logo {
            margin-bottom: 6px;
        }


        .header-logo img {
            height: 85px;
            /* display: block; */
        }

        .header-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .header-info .nombre {
            font-size: 14pt;
            font-weight: 700;
            color: #3459A4;
            letter-spacing: 0.3px;
            line-height: 1.2;
            font-family: sans-serif;
        }

        .header-info .rfc {
            font-size: 8.5pt;
            color: #555;
            font-family: sans-serif;
            margin-top: 2px;
        }

        .header-info .sep {
            border: none;
            border-top: 1px solid #c5cfe8;
            margin: 5px auto;
            width: 70%;
        }

        .header-info .datos {
            font-size: 8.5pt;
            color: #333;
            line-height: 1.6;
            font-family: sans-serif;
        }

        /* Divisor documento */
        .divider {
            border-top: 2px solid #3459A4;
            border-bottom: 2px solid #3459A4;
            margin-top: 10px;
            padding: 4px 0;
        }

        .divider p {
            font-size: 14pt;
            font-weight: 800;
            text-align: center;
            color: #3459A4;
            letter-spacing: 4px;
            font-family: sans-serif;
            margin: 0;
        }

        /* ── Folio / Fecha badge ── */
        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }

        .folio-box {
            border: 2px solid #3459A4;
            border-radius: 6px;
            padding: 6px 14px;
            text-align: center;
        }

        .folio-box .folio-label {
            font-size: 8pt;
            color: #666;
        }

        .folio-box .folio-num {
            font-size: 14pt;
            font-weight: bold;
            color: #3459A4;
        }

        .estatus-badge {
            border-radius: 6px;
            padding: 6px 16px;
            font-weight: bold;
            font-size: 11pt;
            color: #fff;
            background-color: #f0a500;
            /* Pendiente por default */
        }

        /* ── Secciones ── */
        .section-title {
            background-color: #3459A4;
            color: #fff;
            font-weight: bold;
            font-size: 10pt;
            padding: 4px 8px;
            margin-top: 12px;
            font-family: sans-serif;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .info-table td {
            padding: 5px 8px;
            font-size: 10pt;
            font-family: sans-serif;
            vertical-align: top;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-table td.label {
            font-weight: bold;
            width: 30%;
            color: #333;
        }

        .info-table td.value {
            color: #111;
        }

        /* ── Tabla de insumos ── */
        .insumo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 10pt;
            font-family: sans-serif;
        }

        .insumo-table th {
            background-color: #e8edf7;
            color: #3459A4;
            font-weight: bold;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #c5cfe8;
        }

        .insumo-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        /* ── Motivo ── */
        .motivo-box {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            min-height: 50px;
            font-size: 10pt;
            font-family: sans-serif;
            margin-top: 2px;
            color: #111;
        }


        .button {
            background-color:
                #0455a0;
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

        /* ── Firmas ── */
        .firmas-row {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            text-align: center;
        }

        .firma-block {
            width: 40%;
        }

        .firma-line {
            border-top: 0px solid #2b2b2b;
            margin-top: 50px;
            padding-top: 4px;
            font-size: 9pt;
            color: #444;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 16px;
            border-top: 1px solid #3459A4;
            text-align: center;
            padding-top: 4px;
            font-size: 8pt;
            color: #666;
            font-family: sans-serif;
        }
    </style>
</head>

<body>

    <div class="header-wrapper">
        @if ($isPdf ?? false)
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:100px; vertical-align:middle;">
                        <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height:85px;">
                    </td>
                    <td style="vertical-align:middle; text-align:center;">
                        <div
                            style="font-size:14pt; font-weight:700; color:#3459A4; letter-spacing:0.3px; line-height:1.2; font-family:sans-serif;">
                            Hospital Veterinario Pets Care
                        </div>
                        <div style="font-size:8.5pt; color:#555; font-family:sans-serif; margin-top:2px;">
                            SMV160511UY0
                        </div>
                        <hr style="border:none; border-top:1px solid #c5cfe8; margin:5px auto; width:70%;">
                        <div style="font-size:8.5pt; color:#333; line-height:1.6; font-family:sans-serif;">
                            Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila<br>
                            8444851999 &nbsp;&nbsp;|&nbsp;&nbsp; admpetscare@gmail.com
                        </div>
                    </td>
                </tr>
            </table>
        @else
            <div class="header-inner">
                <div class="header-logo">
                    <img src="{{ asset('img/logo-petscare.png') }}" alt="Logo">
                </div>
                <div class="header-info">
                    <div class="nombre">Hospital Veterinario Pets Care</div>
                    <div class="rfc">SMV160511UY0</div>
                    <hr class="sep">
                    <div class="datos">
                        Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila<br>
                        8444851999 &nbsp;&nbsp;|&nbsp;&nbsp; admpetscare@gmail.com
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="divider">
        <p>VALE DE ALMACÉN</p>
    </div>

    <div style="margin-top:10px;">
        @if ($isPdf ?? false)
            <table style="width:100%; border-collapse:collapse; margin-top:10px;">
                <tr>
                    <td style="vertical-align:middle;">
                        <div
                            style="border:2px solid #3459A4; border-radius:6px; padding:6px 14px; text-align:center; display:inline-block;">
                            <div style="font-size:8pt; color:#666;">Folio vale</div>
                            <div style="font-size:14pt; font-weight:bold; color:#3459A4;">{{ $voucher->folio }}</div>
                        </div>
                    </td>
                    <td style="text-align:right; vertical-align:middle;">
                        <p style="font-size:10pt; font-family:sans-serif; margin:0;">
                            <b>Saltillo, Coahuila a &nbsp;{{ $voucher->created_at->format('d/m/Y H:i') }}hrs</b>
                        </p>
                    </td>
                </tr>
            </table>
        @else
            <div class="meta-row">
                <div class="folio-box">
                    <div class="folio-label">Folio vale</div>
                    <div class="folio-num">{{ $voucher->folio }}</div>
                </div>
                <div style="text-align:right;">
                    <p><b>Saltillo, Coahuila a &nbsp;{{ $voucher->created_at->format('d/m/Y H:i') }}hrs</b></p>
                </div>
            </div>
        @endif
    </div>

    <div class="section-title">DATOS DEL SOLICITANTE</div>
    <table class="info-table">
        <tr>
            <td class="label">Médico solicitante:</td>
            <td class="value"> MVZ. {{ $voucher->vet->name }}</td>
            <td class="label">Servicio vinculado:</td>
            <td class="value" colspan="3">{{ $reception->receptionType->name }}</td>
        </tr>
    </table>

    <div class="section-title">DATOS DEL PACIENTE</div>
    <table class="info-table">
        <tr>
            <td class="label">Paciente (mascota):</td>
            <td class="value">{{ $reception->pet->name }}</td>
            <td class="label">Propietario:</td>
            <td class="value">{{ $reception->family->name }}</td>
        </tr>
    </table>

    <form id="formVale">
        <div class="section-title">DETALLE DEL INSUMO</div>
        <table class="insumo-table">
            <thead>
                <tr>
                    <th style="width:45%;">Medicamento / Insumo</th>
                    <th style="width:20%;">Cantidad Solicitada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voucher->voucherProducts as $item)
                    <tr>
                        <td>{{ $item->product->NOMBRE }}</td>

                        <td>
                            {{ $item->requested_quantity == floor($item->requested_quantity)
                                ? (int) $item->requested_quantity
                                : $item->requested_quantity }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="section-title">OBSERVACIONES DEL ALMACÉN <span
                style="font-weight:normal;font-size:8pt;">(opcional)</span></div>
        <div class="motivo-box">
            @if (!isset($isPdf) || !$isPdf)
                <textarea name="observaciones" rows="4" placeholder="Observaciones adicionales..." style="width: 100%;"></textarea>
            @else
                {{ $voucher->warehouse_observations ?? '—' }}
            @endif
        </div> --}}

        @if ($isPdf ?? false)
            <table style="width:100%; border-collapse:collapse; margin-top:40px;">
                <tr>
                    <td style="width:50%; text-align:center; vertical-align:bottom; padding: 0 20px;">
                        {{-- @if ($voucher->vet_signature)
                            <img src="{{ public_path('storage/' . $voucher->vet_signature) }}"
                                style="width:300px;height:120px;object-fit:contain;">
                        @endif --}}
                        <div class="firma-line">
                            <b>USUARIO SOLICITANTE</b><br>
                             {{ $voucher->vet->name }}
                        </div>
                    </td>
                    <td style="width:50%; text-align:center; vertical-align:bottom; padding: 0 20px;">
                        {{-- @if ($voucher->warehouse_signature)
                            <img src="{{ public_path('storage/' . $voucher->warehouse_signature) }}"
                                style="width:300px;height:120px;object-fit:contain;">
                        @endif --}}
                        <div class="firma-line">
                            <b>SURTIDO POR</b><br>
                            {{ $voucher->issuer->name }}
                        </div>
                    </td>
                </tr>
            </table>
        @else
            <div class="firmas-row">
                <div class="firma-block">
                    <img src="{{ asset('storage/' . $voucher->vet_signature) }}"
                        style="width:300px;height:120px;object-fit:contain;">
                    <div class="firma-line">
                        <b>MÉDICO SOLICITANTE</b><br>
                        MVZ. {{ $voucher->vet->name }}
                    </div>

                </div>


                <div class="firma-block">
                    @if (!isset($isPdf) || !$isPdf)
                        <canvas id="firmaSurtido" width="300" height="120"></canvas>
                    @else
                        <img src="{{ public_path('storage/' . $voucher->warehouse_signature) }}"
                            style="width:300px;height:120px;object-fit:contain;">
                    @endif
                    <div class="firma-line">
                        <b>SURTIDO POR</b><br>
                        {{ auth()->user()->name }}

                    </div>
                </div>
            </div>
        @endif

        <div style="margin-top: 20px; text-align: center;">
            @if (!isset($isPdf) || !$isPdf)
                <div style="display: flex; justify-content: center; gap: 20px;">

                    <div style="align-self: flex-start;">
                        <button type="button" class="btnLimpiar btn btn-lmx button" data-target="firmaSurtido"
                            style="width: 100px;">Limpiar</button>
                    </div>
                    <div>

                        <button class="btnEnviar btn btn-lmx button" style="width: 100px;">Aceptar</button>

                    </div>
                </div>
            @endif
        </div>
    </form>

    <div class="footer">
        Hospital Veterinario Pets Care &nbsp;|&nbsp; SMV160511UY0 &nbsp;|&nbsp;
        Blvd. Luis Donaldo Colosio 764, Saltillo, Coah. &nbsp;|&nbsp; 8444851999
    </div>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vouchers/issue.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const ISSUE_VOUCHER_URL = "{{ route('vouchers.issue', $voucher->id) }}";
    </script>
</body>

</html>
