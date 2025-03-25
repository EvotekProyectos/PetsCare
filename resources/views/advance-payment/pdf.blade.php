<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Anticipo</title>
    <style>
            body { 
                font-family: 'Courier New', monospace; 
                font-size: 12px; 
                text-align: center;
                width: 80mm;
            }
            .container { width: 100%; max-width: 60mm; margin: -2%; }
            .barcode img { width: 100%; max-width: 60mm; margin: 3px 3px; }
            .line { border-top: 1px dashed black;  }
            .bold { font-weight: bold; }s
            .qr { text-align: center; margin-top: 10px; }
        
    </style>
</head>
<body>
    <div class="container">
        <h3>SERVICIOS MEDICOS VETERINARIOS EL ANGEL</h3>
        <p>COLOSIO 764 FRACC. VALLE REAL 2 SECTOR 25209</p>
        <div class="line"></div>
        <p><strong>Fecha:</strong> {{ date($advancePayment->date) }}</p>
        <p><strong>Cliente:</strong> {{ $advancePayment->reception->family->name ?? 'No especificado' }}</p>
        <p><strong>Mascota:</strong> {{ $advancePayment->reception->pet->name ?? 'No especificado' }}</p>
        <p><strong>Emitido por:</strong> {{ $advancePayment->user->name ?? 'No especificado' }}</p>
        <div class="line"></div>
        
        <p class="bold">DETALLE DEL ANTICIPO</p>
        <p><strong>Concepto:</strong> {{ $advancePayment->concept ?? 'N/A' }}</p>
        <p><strong>Referencia:</strong> {{ $advancePayment->reference }}</p>
        <p><strong>Total:</strong> ${{ number_format($advancePayment->amount, 2) }}</p>
        
        {{-- <div class="line"></div> --}}
        
        {{-- <p class="bold">CÓDIGO DE BARRAS</p> --}}
        <div class="barcode">
            {!! DNS1D::getBarcodeHTML($advancePayment->reference, 'C128', 1.75, 50) !!}
        </div>

        {{-- <div class="line"></div> --}}
        
        <p>¡Gracias por su preferencia! </p>
        <p><em>Esto es una referencia para pagar en caja, no tiene validez alguna como comprobante de pago.</em></p>
    </div>

   
</body>
</html>


{{-- {!! DNS1D::getBarcodeHTML($advancePayment->reference, 'C128', 1, 50) !!} --}}