<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #222;
        }
        .header-table, .info-table, .items-table, .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .section-title {
            font-weight: bold;
            font-size: 10px;
            color: #1e6091;
            background-color: #eaf7fb;
            padding: 4px 8px;
            margin-bottom: 6px;
        }
        .info-table td {
            padding: 3px 0;
            font-size: 11px;
            width: 33%;
        }
        .items-table th {
            text-align: left;
            padding: 6px 8px;
            background-color: #1e6091;
            color: #ffffff;
            font-size: 11px;
        }
        .items-table td {
            padding: 6px 8px;
            font-size: 11px;
        }
        .items-table tr.alt {
            background-color: #f7fbfd;
        }
        .totals-table {
            width: 220px;
            margin-left: auto;
            font-size: 12px;
        }
        .totals-table td {
            padding: 3px 8px;
        }
        .totals-table .label {
            color: #6b7a86;
        }
        .totals-table .total-row {
            border-top: 2px solid #1e6091;
            font-weight: bold;
            color: #1e6091;
        }
        .footer-table {
            border-top: 1px solid #e3e9ed;
            padding-top: 8px;
            font-size: 9px;
            color: #9aa5ac;
            margin-top: 20px;
        }
        .footer-table td {
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <table class="header-table" style="border-bottom: 2px solid #1e6091; padding-bottom: 12px; margin-bottom: 16px;">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <img src="{{ public_path('img/logo-petscare.png') }}" style="height: 87px;">
            </td>
            <td style="width: 50%; text-align: right; font-size: 10px; color: #6b7a86; vertical-align: middle;">
                <div style="font-weight: bold; color: #1e6091; font-size: 12px; margin-bottom: 4px;">Estado de cuenta</div>
                {{-- dirección/teléfono de la clínica --}}
            </td>
        </tr>
    </table>

    <div class="section-title">DATOS DE LA RECEPCIÓN</div>
    <table class="info-table">
        <tr>
            <td><strong>N° recepción:</strong> {{ str_pad($reception->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td><strong>Mascota:</strong> {{ $reception->pet?->name ?? '' }}</td>
            <td><strong>Tipo:</strong> {{ $reception->receptionType?->name ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha ingreso:</strong> {{ \Carbon\Carbon::parse($reception->entry_date)->format('d/m/Y') }}</td>
            <td><strong>Fecha alta:</strong> {{ $reception->exit_date ? \Carbon\Carbon::parse($reception->exit_date)->format('d/m/Y') : '—' }}</td>
            <td><strong>M.V.Z.:</strong> {{ $reception->vet?->name ?? '' }}</td>
        </tr>
    </table>

    <div style="margin-top: 14px;"></div>

    <div class="section-title">DATOS DEL PROPIETARIO</div>
    <table class="info-table">
        <tr>
            <td><strong>Familia:</strong> {{ $reception->family ? str_pad($reception->family->id, 4, '0', STR_PAD_LEFT) . '-' . $reception->family->name : '' }}</td>
            <td><strong>Teléfono:</strong> {{ $reception->family?->phone ?? '' }}</td>
            <td><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div style="margin-top: 16px;"></div>

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
        <tr>
            <td class="label">Subtotal</td>
            <td style="text-align: right;">${{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <td class="label">IVA</td>
            <td style="text-align: right;">$0.00</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td style="text-align: right;">${{ number_format($total, 2) }}</td>
        </tr>
    </table>

    <table class="footer-table">
        <tr>
            <td style="text-align: left;">Generado: {{ now()->format('d/m/Y H:i') }}</td>
            <td style="text-align: center;">Usuario: {{ auth()->user()->name }}</td>
            <td style="text-align: right;">Página 1/1</td>
        </tr>
    </table>

</body>
</html>
