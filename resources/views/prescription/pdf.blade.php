<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Esta vista solo se renderiza vía Pdf::loadView() (ver
         PrescriptionController::imprimir()), nunca como página web, así que
         el link siempre usa public_path() (DomPDF necesita ruta local, no
         URL) — mismo mecanismo que reception/pdf.blade.php usa cuando
         $isPdf es true. Se carga primero la base compartida por todos los
         documentos y después los estilos propios de esta vista. --}}
    <link rel="stylesheet" href="{{ public_path('css/documento-base.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/formula-medica.css') }}">
</head>

<body class="pdf-mode">

    @php
        /*
         * Mismo helper de íconos que reception/pdf.blade.php (Autorización
         * de Hospital): SVGs auto-contenidos en base64, para que DomPDF los
         * renderice como <img>. Se mantiene el set de íconos original; solo
         * cambia el color por defecto que se les pasa desde cada llamada
         * ($icon($nombre, $tamaño, $color)) para usar la paleta nueva.
         */
        $icons = [
            'pin' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 0C6.1 0 3 3.1 3 7c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 10 4.5a2.5 2.5 0 0 1 0 5z"/></svg>',
            'phone' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.1a1.5 1.5 0 0 1 1.5 1.2l.7 3.2a1.5 1.5 0 0 1-1.1 1.8l-.9.3a11.5 11.5 0 0 0 6.3 6.3l.3-.9a1.5 1.5 0 0 1 1.8-1.1l3.2.7A1.5 1.5 0 0 1 18 15.4v1.1a1.5 1.5 0 0 1-1.5 1.5H15A13 13 0 0 1 2 5V3.5z"/></svg>',
            'mail' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M3 4a2 2 0 0 0-2 2v1.2l8.4 4.2a1.25 1.25 0 0 0 1.2 0L19 7.2V6a2 2 0 0 0-2-2H3z"/><path d="M19 8.8l-7.8 3.9a2.75 2.75 0 0 1-2.5 0L1 8.8V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.8z"/></svg>',
            'clock' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm.75-13a.75.75 0 0 0-1.5 0v5c0 .4.3.8.7.8h4a.75.75 0 0 0 0-1.5h-3.2V5z"/></svg>',
            'user' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3.5 14.5a1.2 1.2 0 0 0 .4 1.4A10 10 0 0 0 10 18c2.3 0 4.4-.8 6.1-2.1.4-.3.6-.9.4-1.4a7 7 0 0 0-13 0z"/></svg>',
            'paw' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><circle cx="6" cy="6" r="2"/><circle cx="14" cy="6" r="2"/><circle cx="3.3" cy="11" r="1.8"/><circle cx="16.7" cy="11" r="1.8"/><ellipse cx="10" cy="14.5" rx="5" ry="4"/></svg>',
            'calendar' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2zM4.75 7.5c-.7 0-1.25.6-1.25 1.25v6.5c0 .7.6 1.25 1.25 1.25h10.5c.7 0 1.25-.6 1.25-1.25v-6.5c0-.7-.6-1.25-1.25-1.25H4.75z"/></svg>',
            'scale' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><rect x="4" y="8" width="12" height="8" rx="2"/><rect x="7" y="4" width="6" height="4" rx="1"/></svg>',
            'doc' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M4 2.8C4 1.8 4.8 1 5.8 1h4.6c.5 0 .9.2 1.2.5l3.9 3.9c.3.3.5.7.5 1.2v10.6c0 1-.8 1.8-1.8 1.8H5.8C4.8 19 4 18.2 4 17.2V2.8z"/></svg>',
            'pill' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M4.2 15.8a4 4 0 0 1 0-5.66l6-6a4 4 0 1 1 5.66 5.66l-6 6a4 4 0 0 1-5.66 0zM9.4 5.3 5.3 9.4l5.3 5.3 4.1-4.1z"/></svg>',
            'eye' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{color}"><path d="M10 3.5C5.5 3.5 2.2 7 1 10c1.2 3 4.5 6.5 9 6.5s7.8-3.5 9-6.5c-1.2-3-4.5-6.5-9-6.5zM10 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>',
        ];

        $icon = function ($name, $size = 12, $color = '#603B7D') use ($icons) {
            $svg = str_replace('{color}', $color, $icons[$name]);
            return '<img class="fm-icon" src="data:image/svg+xml;base64,' .
                base64_encode($svg) .
                '" width="' .
                $size .
                '" height="' .
                $size .
                '">';
        };

        // Paleta Pets Care
        $primary = '#603B7D'; // morado — header/footer
        $accent = '#3FAFDB'; // azul cielo — íconos/línea/barra
        $white = '#FFFFFF';
    @endphp

    <div class="fm-page">

        {{-- Marca de agua decorativa --}}
                <div class="fm-watermark">
            {!! $icon('paw', 220, '#EDE5F5') !!}
        </div>

        {{-- Barra lateral de acento --}}
        <div class="fm-side-bar"></div>

        <div class="fm-content">

            {{-- Encabezado: logo + nombre de la clínica + folio/fecha a la
                 izquierda, contacto a la derecha. --}}
            <div class="fm-header">
                <table class="fm-header-table">
                    <tr>
                        <td style="width: 78px;">
                            <div class="fm-logo-wrap">
                                <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo">
                            </div>
                        </td>
                        <td>
                            <p class="fm-clinic-name">Hospital Veterinario Pets Care</p>
                            <p class="fm-clinic-sub">Fórmula médica</p>
                            <p class="fm-clinic-meta">
                                {!! $icon('clock', 11, $white) !!}
                                {{ now()->format('d/m/Y H:i') }}
                            </p>
                        </td>
                        <td class="fm-header-right" style="width: 210px;">
                            <div>Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila {!! $icon('pin', 10, $white) !!}</div>
                            <div>844 485 1999 {!! $icon('phone', 10, $white) !!}</div>
                            <div>admpetscare@gmail.com {!! $icon('mail', 10, $white) !!}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="fm-accent-stripe"></div>

            {{-- Familia + Mascota, lado a lado --}}
            <table class="fm-row2">
                <tr>
                    <td>
                        <div class="fm-card">
                            <p class="fm-card-label">{!! $icon('user', 12, $primary) !!}Datos de la familia</p>
                            <p class="fm-card-title">{{ $prescription->pet->family->name }}</p>
                            <p class="fm-card-line">Tel. {{ $prescription->pet->family->phone }}
                            </p>
                        </div>
                    </td>
                    <td>
                        <div class="fm-card">
                            <p class="fm-card-label">{!! $icon('paw', 12, $primary) !!}Datos de la mascota</p>
                            <p class="fm-card-title">{{ $prescription->pet->name }}</p>
                            <p class="fm-card-sub">{{ $prescription->pet->specie }} &middot;
                                {{ $prescription->pet->raza }}
                               {{ $prescription->pet->weight ? $prescription->pet->weight . ' kg' : '—' }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Registro + Próximo control, lado a lado --}}
            <table class="fm-row2">
                <tr>
                    <td>
                        <div class="fm-card">
                            <p class="fm-card-label">{!! $icon('doc', 12, $primary) !!}Registro</p>
                            <p class="fm-card-line">Fecha:
                                {{ \Carbon\Carbon::parse($prescription->date)->format('d/m/Y H:i:s') }}</p>
                            <p class="fm-card-line">Registra: {{ $prescription->vet->name }}</p>
                        </div>
                    </td>
                    <td>
                        <div class="fm-card">
                            <p class="fm-card-label">{!! $icon('calendar', 12, $primary) !!}Próximo control</p>
                            <p class="fm-card-line">Fecha:
                                {{ \Carbon\Carbon::parse($next?->day_next_check ?? $prescription?->day_next_check)->format('d/m/Y') }}

                                {{ $next?->time_next_check ?? ($prescription?->time_next_check ?? ' ') }}</p>
                            <p class="fm-card-line">Motivo:
                                {{ $next?->reason?->name ?? ($prescription?->reason?->name ?? 'Sin definir') }}</p>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Diagnóstico --}}
            <div class="fm-section">
                <p class="fm-section-label">Diagnóstico</p>
                <div class="fm-section-box">{{ $prescription->diagnosis }}</div>
            </div>

            {{-- Medicamentos --}}
            <div class="fm-section">
                <p class="fm-section-label">Medicamentos</p>
                <div class="fm-section-box">{{ $prescription->medicine }}</div>
            </div>

            {{-- Observaciones --}}
            <div class="fm-section" style="margin-bottom: 4px;">
                <p class="fm-section-label">Observaciones</p>
                <div class="fm-section-box">{{ $prescription->observations }}</div>
            </div>

        </div>

        {{-- Pie de página --}}
        {{-- <div class="fm-footer">
            <table class="fm-footer-table">
                <tr>
                    <td>
                        <p class="fm-footer-brand">HOSPITAL VETERINARIO PETS CARE</p>
                        <p class="fm-footer-left">{!! $icon('pin', 10, $white) !!}Blvd. Luis Donaldo Colosio 764, 25205
                            Saltillo, Coahuila</p>
                        <p class="fm-footer-left">{!! $icon('phone', 10, $white) !!}844 485 1999</p>
                    </td>
                    {{-- <td class="fm-footer-right" style="width: 220px;">
                        <p class="fm-footer-right">{!! $icon('calendar', 10, $white) !!}<strong>Días:</strong> Lun a Dom</p>
                        <p class="fm-footer-right">{!! $icon('clock', 10, $white) !!}<strong>Horario:</strong> 24 Horas
                        </p>
                    </td> 
                </tr>
            </table>
        </div> --}}

    </div>

</body>

</html>
