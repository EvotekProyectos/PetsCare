<!DOCTYPE html>
<html lang="es">

<head>
    @routes
    <style type="text/css">
        body {
            font-family: sans-serif;
            font-size: 11pt;
            margin: 0;
            background-color: #F3F6FA;
            color: #1F2A37;
        }

        /* DomPDF no soporta flexbox/grid: todo el layout usa tablas, mismo
           patrón que reception/pdf.blade.php. */
        table {
            border-collapse: collapse;
            font-family: sans-serif;
        }

        .card {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
        }

        .header-table td {
            padding: 18px 22px 14px;
            vertical-align: middle;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #0455A0;
        }

        .clinic-name {
            font-weight: 700;
            font-size: 12pt;
            color: #0455A0;
            margin: 0;
        }

        .clinic-data {
            font-size: 8.5pt;
            color: #6B7280;
            line-height: 1.5;
            margin-top: 2px;
        }

        .title-bar {
            width: 100%;
            background-color: #F8FAFC;
            text-align: center;
            padding: 12px 22px;
        }

        .title-bar .title {
            font-size: 11pt;
            font-weight: 700;
            color: #1F2A37;
            letter-spacing: .02em;
            margin: 0;
        }

        .title-bar .fecha {
            font-size: 8.5pt;
            color: #6B7280;
            margin-top: 2px;
        }

        .section {
            padding: 14px 22px 0;
        }

        .info-box {
            width: 100%;
            background-color: #F8FAFC;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 9pt;
            color: #4B5563;
            line-height: 1.6;
        }

        .pet-box {
            width: 100%;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 16px;
        }

        .pet-box .label {
            font-size: 8pt;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 8px;
        }

        .pet-box td {
            font-size: 9pt;
            color: #1F2A37;
            padding: 3px 0;
            width: 50%;
        }

        .terms-label {
            font-size: 8pt;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 8px;
        }

        .terms-box {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 9.5pt;
            color: #374151;
            line-height: 1.6;
            text-align: justify;
        }

        .signature-section {
            padding: 20px 22px 8px;
            text-align: center;
        }

        .signature-title {
            font-size: 9.5pt;
            font-weight: 600;
            color: #1F2A37;
            margin-bottom: 10px;
        }

        .signature-box {
            border: 2px dashed #BFDDF6;
            border-radius: 14px;
            background-color: #FAFBFC;
            height: 100px;
        }

        .signature-placeholder {
            color: #9CA3AF;
            font-size: 9pt;
        }

        .actions-table {
            width: 100%;
            padding: 14px 22px 22px;
        }

        .btn-limpiar,
        .btn-aceptar {
            display: block;
            text-align: center;
            padding: 12px 0;
            border-radius: 14px;
            font-size: 9.5pt;
            font-weight: 600;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-limpiar {
            background-color: #fff;
            color: #6B7280;
            border: 1px solid #D1D5DB;
            font-weight: 500;
        }

        .btn-aceptar {
            background-color: #0455A0;
            color: #fff;
        }

        .name-input {
            border: none;
            border-bottom: 1px solid #9CA3AF;
            background: transparent;
            font-size: 9.5pt;
            padding: 0 2px;
            width: 260px;
        }
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('cremation.entrega-cenizas.pdf', $reception->id) }}" id="reception">

    <div class="card">

        {{-- Header: logo + datos de la clínica --}}
        <table class="header-table">
            <tr>
                <td style="width: 76px;">
                    @if ($isPdf ?? false)
                        <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo"
                            style="height: 60px; width: 60px; border-radius: 8px;">
                    @else
                        <img src="{{ asset('img/logo-petscare.png') }}"
                            style="height: 60px; width: 60px; border-radius: 8px;">
                    @endif
                </td>
                <td>
                    <p class="clinic-name">Hospital Veterinario Pets Care</p>
                    <p class="clinic-data">
                        SMV160511UY0<br>
                        Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila<br>
                        844 485 1999 &middot; admpetscare@gmail.com
                    </p>
                </td>
            </tr>
        </table>

        {{-- Barra de título + fecha --}}
        <table class="title-bar">
            <tr>
                <td>
                    <p class="title">ENTREGA DE CENIZAS</p>
                    <p class="fecha">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>

        {{-- Datos del propietario --}}
        <div class="section">
            <table class="info-box">
                <tr>
                    <td>
                        <p style="margin:0;"><b>Propietario:</b> {{ $reception->family->name ?? '' }}</p>
                        <p style="margin:4px 0 0;"><b>Tel:</b> {{ $reception->family->phone ?? '' }}</p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Datos de la mascota --}}
        <div class="section">
            <div class="pet-box">
                <div class="label">Mascota</div>
                <table style="width: 100%;">
                    <tr>
                        <td>Nombre: {{ $reception->pet->name ?? '' }}</td>
                        <td>Especie: {{ $reception->pet->specie ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Detalle de la urna/placa --}}
        <div class="section">
            <div class="pet-box">
                <div class="label">Detalle de la urna/placa</div>
                <table style="width: 100%;">
                    <tr>
                        <td>Tipo de urna: {{ $cremation?->type_urn ?: '—' }}</td>
                        <td>Modelo de urna: {{ $cremation?->urn_model ?: '—' }}</td>
                    </tr>
                    @if (!empty($cremation?->text_placa))
                        <tr>
                            <td colspan="2">Mensaje de la placa: {{ $cremation->text_placa }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Texto de consentimiento --}}
        <div class="section">
            <div class="terms-label">Consentimiento de entrega</div>
            <div class="terms-box">
                <p style="margin: 0;">
                    Por medio de la presente, yo,
                    @if (!isset($isPdf) || !$isPdf)
                        <input type="text" id="name_family" name="name_family"
                            value="{{ $nameFamily ?? '' }}" class="name-input" placeholder="Nombre del propietario">
                    @else
                        <b>{{ $nameFamily ?? '' }}</b>
                    @endif
                    , hago constar que he recibido a mi entera satisfacción las cenizas de mi mascota
                    <b>{{ $reception->pet->name ?? '' }}</b>, mismas que me fueron entregadas por Hospital
                    Veterinario Pets Care una vez concluido el proceso de cremación. Confirmo que las cenizas
                    fueron entregadas en condiciones adecuadas, en el contenedor/urna correspondiente, y que no
                    tengo ninguna reclamación pendiente respecto al servicio prestado. Firmo de conformidad,
                    dando por concluido el servicio.
                </p>
            </div>
        </div>

        {{-- Firma --}}
        <div class="signature-section">
            <p class="signature-title">Firma del propietario</p>

            @if (isset($signatureDataUrl))
                <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
            @else
                <canvas id="canvas" class="signature-box" width="476" height="100"></canvas>
                <p class="signature-placeholder" style="margin-top: 6px;">Firme aquí con su dedo</p>
            @endif
        </div>

        {{-- Botones (solo en la vista interactiva, no en el PDF final) --}}
        @if (!isset($isPdf) || !$isPdf)
            <table class="actions-table">
                <tr>
                    <td style="width: 33%; padding-right: 8px;">
                        <button type="button" class="btnLimpiar btn-limpiar" data-target="canvas">Limpiar</button>
                    </td>
                    <td style="width: 67%;">
                        <form style="margin:0;">
                            <button type="submit" class="btnEnviar btn-aceptar">Aceptar y firmar</button>
                        </form>
                    </td>
                </tr>
            </table>
        @endif

    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/cremations/entrega-cenizas.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
