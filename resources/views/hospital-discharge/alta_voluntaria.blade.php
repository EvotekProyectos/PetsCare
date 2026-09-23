<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @routes
    @if ($isPdf ?? false)
        <link rel="stylesheet" href="{{ public_path('css/documento-base.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/documento-base.css') }}">
    @endif
    <style>
        /* Solo lo específico de este documento (los dos campos capturables
           dentro de la declaración: nombre de quien firma y motivo). El
           resto del diseño viene de documento-base.css, igual que
           reception/pdf.blade.php (Autorización de Hospital), para que
           ambos documentos compartan la misma familia visual. */
        .name-input,
        .reason-input {
            display: block;
            width: 100%;
            margin-top: 6px;
            padding: 6px 8px;
            font-size: 9.5pt;
            font-family: sans-serif;
            color: #1F2A37;
            border: 1px solid #BFDDF6;
            border-radius: 8px;
            background-color: #FAFBFC;
        }

        .reason-input {
            height: 70px;
        }
    </style>
</head>

<body class="{{ $isPdf ?? false ? 'pdf-mode' : '' }}">
    <input type="hidden" value="{{ route('altaVoluntaria.pdf', $reception->id) }}" id="reception">

    @php
        /*
         * Mismo helper de íconos que reception/pdf.blade.php (Autorización de
         * Hospital): SVGs auto-contenidos en base64, para que DomPDF los
         * renderice como <img> igual que el logo.
         */
        $icons = [
            'pin' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 0C6.1 0 3 3.1 3 7c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 10 4.5a2.5 2.5 0 0 1 0 5z"/></svg>',
            'phone' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.1a1.5 1.5 0 0 1 1.5 1.2l.7 3.2a1.5 1.5 0 0 1-1.1 1.8l-.9.3a11.5 11.5 0 0 0 6.3 6.3l.3-.9a1.5 1.5 0 0 1 1.8-1.1l3.2.7A1.5 1.5 0 0 1 18 15.4v1.1a1.5 1.5 0 0 1-1.5 1.5H15A13 13 0 0 1 2 5V3.5z"/></svg>',
            'mail' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M3 4a2 2 0 0 0-2 2v1.2l8.4 4.2a1.25 1.25 0 0 0 1.2 0L19 7.2V6a2 2 0 0 0-2-2H3z"/><path d="M19 8.8l-7.8 3.9a2.75 2.75 0 0 1-2.5 0L1 8.8V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.8z"/></svg>',
            'pencil' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M13.5 2.5a1.75 1.75 0 0 0-2.5 0L6.7 6.8a2.75 2.75 0 0 0-.7 1.3l-.9 3.8a.75.75 0 0 0 .9.9l3.8-.9a2.75 2.75 0 0 0 1.3-.7l4.3-4.3a1.75 1.75 0 0 0 0-2.5l-1.9-1.9z"/><path d="M4.75 3.5h-.5A2.25 2.25 0 0 0 2 5.75v9.5A2.25 2.25 0 0 0 4.25 17.5h9.5A2.25 2.25 0 0 0 16 15.25v-4a.75.75 0 0 0-1.5 0v4a.75.75 0 0 1-.75.75h-9.5a.75.75 0 0 1-.75-.75v-9.5a.75.75 0 0 1 .75-.75h4a.75.75 0 0 0 0-1.5h-4z"/></svg>',
            'trash' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.44c-.8.08-1.58.18-2.36.3a.75.75 0 1 0 .23 1.48l.15-.02.84 10.52A2.75 2.75 0 0 0 7.6 19h4.8a2.75 2.75 0 0 0 2.74-2.53l.84-10.52.15.02a.75.75 0 0 0 .23-1.48 41 41 0 0 0-2.36-.3v-.44A2.75 2.75 0 0 0 11.25 1h-2.5zM10 4c.84 0 1.67.03 2.5.08V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.33C8.33 4.03 9.16 4 10 4z"/></svg>',
        ];

        $icon = function ($name, $size = 14, $color = '#0455A0') use ($icons) {
            $svg = str_replace('{color}', $color, $icons[$name]);
            return '<img src="data:image/svg+xml;base64,' .
                base64_encode($svg) .
                '" width="' .
                $size .
                '" height="' .
                $size .
                '" style="vertical-align:middle;margin-right:4px;">';
        };

        $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
        $years = $birthday->diffInYears(now());
        $months = $birthday->copy()->addYears($years)->diffInMonths(now());
    @endphp

    <div class="card">

        {{-- Header: logo + nombre de la clínica + fecha a la izquierda,
             contacto a la derecha — misma estructura que Autorización de
             Hospital (reception/pdf.blade.php). --}}
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
                <td class="header-left">
                    <p class="clinic-name">Hospital Veterinario Pets Care</p>
                    <p class="clinic-sub">Alta voluntaria</p>
                    <p class="clinic-meta">
                        Saltillo, Coahuila a
                        &middot; {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        &middot; {{ \Carbon\Carbon::now()->format('H:i') }}
                    </p>
                </td>
                <td class="header-contact">
                    <p>{!! $icon('pin', 11) !!}Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila</p>
                    <p>{!! $icon('phone', 11) !!}844 485 1999</p>
                    <p>{!! $icon('mail', 11) !!}admpetscare@gmail.com</p>
                </td>
            </tr>
        </table>

        {{-- Propietario + Mascota, lado a lado — mismo componente que usa
             Autorización de Hospital, la referencia de diseño de esta
             familia de documentos. --}}
        <div class="section">
            <x-document.owner-pet-cards :pet="$reception->pet" />
        </div>

        {{-- Declaración: mismo texto/campos de siempre (nombre de quien
             firma y motivo del alta), solo con el formato visual de
             Hospital. --}}
        <div class="section">
            <div class="terms-label">Declaración de alta voluntaria</div>
            <div class="terms-box">
                <p class="term-text" style="padding-left: 0;">
                    Yo:
                    @if (!isset($isPdf) || !$isPdf)
                        <input type="text" id="name_family" name="name_family" value="{{ $nameFamily ?? '' }}"
                            class="name-input">
                    @else
                        <span><strong>{{ $nameFamily ?? '' }}</strong></span>
                    @endif
                    , declaro que por mi propia voluntad decido llevarme de <b>ALTA VOLUNTARIA</b> al paciente de
                    nombre <b>{{ $reception->pet->name }}</b>, especie <b>{{ $reception->pet->specie }}</b>, edad
                    {{ $years }} años, {{ $months }} meses, del cual no acepto el tratamiento médico indicado.
                    Dejando exento de responsabilidades al Hospital Veterinario Pets Care y a los médicos encargados
                    del caso.
                </p>
                <p class="card-label" style="margin: 14px 0 0;">Motivo</p>
                @if (!isset($isPdf) || !$isPdf)
                    <textarea id="reason" name="reason" class="reason-input">{{ $reason ?? '' }}</textarea>
                @else
                    <p class="term-text" style="padding-left: 0;">{{ $reason ?? '' }}</p>
                @endif
            </div>
        </div>

        {{-- Firma --}}
        <div class="signature-section">
            <table class="signature-header-table">
                <tr>
                    <td style="text-align:left;">
                        <p class="signature-title">Nombre y firma</p>
                    </td>
                </tr>
            </table>

            @if (isset($signatureDataUrl))
                <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" class="signature-image">
            @else
                <canvas id="canvas" class="signature-box" width="476" height="100"></canvas>
                <p class="signature-placeholder" style="margin-top: 6px;">Firme aquí</p>
            @endif
        </div>

        {{-- Botones (solo en la vista interactiva, no en el PDF final) --}}
        @if (!isset($isPdf) || !$isPdf)
            <div class="actions-footer">
                <table class="actions-table">
                    <tr>
                        <td style="width: 48%; padding-right: 8px;">
                            <button type="button" class="btnLimpiar btn-limpiar" data-target="canvas">
                                {!! $icon('trash', 12, '#0455A0') !!}Limpiar</button>
                        </td>
                        <td style="width: 52%;">
                            <form style="margin:0;">
                                <button type="submit" class="btnEnviar btn-aceptar">
                                    {!! $icon('pencil', 12, '#fff') !!}Aceptar</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/hospitalizations/alta_voluntaria.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
</body>

</html>
