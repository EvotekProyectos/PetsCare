<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    {{-- Este documento SOLO se genera como PDF (Pdf::loadView(...)->stream(),
         ver AppointmentController/RedSheetController/GroomingController/
         HotelController/CremationController::accountStatementPdf()) — nunca
         se sirve como vista web interactiva, así que siempre usa
         public_path(), sin el patrón dual isPdf que sí necesitan las
         responsivas firmables (ver reception/pdf.blade.php). --}}
    <link rel="stylesheet" href="{{ public_path('css/documento-base.css') }}">
    <style>
        /* Solo lo específico de Estado de Cuenta -tabla de conceptos y
           totales-, sin tocar: mismo diseño de siempre (header sólido azul,
           filas alternas, total remarcado), la única diferencia real es que
           ahora vive dentro de la tarjeta/header de documento-base.css en
           vez de flotar solo en la página. */
        .items-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-title {
            font-weight: bold;
            font-size: 10pt;
            color: #0455A0;
            background-color: #EAF2FB;
            padding: 4px 8px;
            margin-bottom: 6px;
            border-radius: 6px;
        }

        .items-table th {
            text-align: left;
            padding: 6px 8px;
            background-color: #0455A0;
            color: #ffffff;
            font-size: 9pt;
        }

        .items-table td {
            padding: 6px 8px;
            font-size: 9pt;
        }

        .items-table tr.alt {
            background-color: #F5F9FD;
        }

        .totals-table {
            width: 220px;
            margin-left: auto;
            font-size: 10pt;
        }

        .totals-table td {
            padding: 3px 8px;
        }

        .totals-table .total-row {
            border-top: 2px solid #0455A0;
            font-weight: bold;
            color: #0455A0;
        }

        .footer-table {
            width: 100%;
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            font-size: 8pt;
            color: #9CA3AF;
            margin-top: 16px;
        }
    </style>
</head>

<body class="pdf-mode">

    @php
        /*
         * Mismo helper de íconos que reception/pdf.blade.php (Autorización de
         * Hospital, la referencia de diseño de este documento): SVGs
         * auto-contenidos en base64 para que DomPDF los renderice como <img>.
         */
        $icons = [
            'pin' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 0C6.1 0 3 3.1 3 7c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 10 4.5a2.5 2.5 0 0 1 0 5z"/></svg>',
            'phone' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.1a1.5 1.5 0 0 1 1.5 1.2l.7 3.2a1.5 1.5 0 0 1-1.1 1.8l-.9.3a11.5 11.5 0 0 0 6.3 6.3l.3-.9a1.5 1.5 0 0 1 1.8-1.1l3.2.7A1.5 1.5 0 0 1 18 15.4v1.1a1.5 1.5 0 0 1-1.5 1.5H15A13 13 0 0 1 2 5V3.5z"/></svg>',
            'mail' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M3 4a2 2 0 0 0-2 2v1.2l8.4 4.2a1.25 1.25 0 0 0 1.2 0L19 7.2V6a2 2 0 0 0-2-2H3z"/><path d="M19 8.8l-7.8 3.9a2.75 2.75 0 0 1-2.5 0L1 8.8V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.8z"/></svg>',
        ];

        $icon = function ($name, $size = 11, $color = '#4B5563') use ($icons) {
            $svg = str_replace('{color}', $color, $icons[$name]);
            return '<img src="data:image/svg+xml;base64,' .
                base64_encode($svg) .
                '" width="' .
                $size .
                '" height="' .
                $size .
                '" style="vertical-align:middle;margin-right:4px;">';
        };
    @endphp

    <div class="card">

        {{-- Header: mismo layout que Autorización de Hospital (logo +
             nombre de clínica a la izquierda, contacto a la derecha). --}}
        <table class="header-table">
            <tr>
                <td style="width: 76px;">
                    <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo"
                        style="height: 60px; width: 60px; border-radius: 8px;">
                </td>
                <td class="header-left">
                    <p class="clinic-name">Hospital Veterinario Pets Care</p>
                    <p class="clinic-sub">Estado de cuenta</p>
                    <p class="clinic-meta">{{ now()->format('d/m/Y') }} &middot; {{ now()->format('H:i') }}</p>
                </td>
                <td class="header-contact">
                    <p>{!! $icon('pin') !!}Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila</p>
                    <p>{!! $icon('phone') !!}844 485 1999</p>
                    <p>{!! $icon('mail') !!}admpetscare@gmail.com</p>
                </td>
            </tr>
        </table>

        {{-- Datos de recepción + Datos del propietario, lado a lado — misma
             tarjeta (.cards-table/.card-box) que Autorización de Hospital,
             con los campos que ya mostraba el Estado de Cuenta (ninguno se
             quitó, solo se reacomodó). --}}
        <div class="section">
            <table class="cards-table">
                <tr>
                    <td class="card-cell" style="padding-right: 8px;">
                        <div class="card-box">
                            <div class="card-label">Datos de recepción</div>
                            <p class="owner-line">N° recepción: {{ str_pad($reception->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="owner-line">Mascota: {{ $reception->pet?->name ?? '' }}</p>
                            <p class="owner-line">Tipo: {{ $reception->receptionType?->name ?? '' }}</p>
                            <p class="owner-line">Fecha ingreso:
                                {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}</p>
                            <p class="owner-line">Fecha alta:
                                {{ $reception->exit_date ? \Carbon\Carbon::parse($reception->exit_date)->format('d/m/Y') : '—' }}
                            </p>
                            <p class="owner-line">M.V.Z.: {{ $reception->vet?->name ?? '' }}</p>
                        </div>
                    </td>
                    <td class="card-cell" style="padding-left: 8px;">
                        <div class="card-box">
                            <div class="card-label">Datos del propietario</div>
                            <p class="owner-name">
                                {{ $reception->family ? str_pad($reception->family->id, 4, '0', STR_PAD_LEFT) . ' - ' . $reception->family->name : '' }}
                            </p>
                            <p class="owner-line">{!! $icon('phone') !!}{{ $reception->family?->phone ?? '' }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Tabla de conceptos y Total: mismo diseño de siempre, solo sin
             Subtotal ni IVA (a petición explícita, presentación únicamente
             — $total sigue siendo el mismo cálculo real del backend). --}}
        <div class="section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th style="text-align: center;">Cant.</th>
                        <th style="text-align: right;">Precio unit.</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr class="{{ $index % 2 == 0 ? 'alt' : '' }}">
                            <td>{{ $item['product_id'] }}</td>
                            <td>{{ $item['description'] }}</td>
                            <td style="text-align: center;">{{ $item['quantity'] }}</td>
                            <td style="text-align: right;">${{ number_format($item['unit_price'], 2) }}</td>
                            <td style="text-align: right;">${{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 8px;"></div>

            <table class="totals-table">
                <tr class="total-row">
                    <td>Total</td>
                    <td style="text-align: right;">${{ number_format($total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if (!empty($advance_payments) && count($advance_payments))
            {{-- Informativo: no se resta del Total de arriba (depende de que
                 el anticipo esté "pagado" -status=1-, flujo aún no
                 implementado). --}}
            <div class="section">
                <div class="section-title">ANTICIPOS REGISTRADOS</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th style="text-align: right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($advance_payments as $index => $advancePayment)
                            <tr class="{{ $index % 2 == 0 ? 'alt' : '' }}">
                                <td>{{ $advancePayment['reference'] }}</td>
                                <td>{{ $advancePayment['concept'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($advancePayment['date'])->format('d/m/Y') }}</td>
                                <td style="text-align: right;">${{ number_format($advancePayment['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 8px;"></div>

                <table class="totals-table">
                    <tr class="total-row">
                        <td>Total anticipado</td>
                        <td style="text-align: right;">${{ number_format($advance_payments_total, 2) }}</td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="section" style="padding-bottom: 14px;">
            <table class="footer-table">
                <tr>
                    <td style="text-align: left;">Generado: {{ now()->format('d/m/Y H:i') }}</td>
                    <td style="text-align: center;">Usuario: {{ auth()->user()->name }}</td>
                    <td style="text-align: right;">Página 1/1</td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
