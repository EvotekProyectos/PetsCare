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
        {{-- Buscador del select de procedimiento (ver #procedure más abajo).
             Solo en la vista interactiva: en el PDF ese campo ya se
             reemplaza por texto plano (@if ($isPdf ?? false) ... @else
             <span>...), así que select2 no aporta nada ahí y se evita
             cargarlo para DomPDF. --}}
        <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    @endif
    <style>
        /* Solo lo específico de este documento (campos embebidos en el
           término 1: procedimiento/total/incluye). El resto del diseño
           viene de documento-base.css, igual que reception/pdf.blade.php
           (Autorización de Hospital), para que ambos documentos compartan
           la misma familia visual. */
        .procedure-select,
        .include-input {
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

        .include-input {
            height: 44px;
        }

        .total-input {
            width: 90px;
            border: none;
            background: transparent;
            font-size: 9.5pt;
            padding: 0;
        }

        .term-text ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .term-text ul li {
            font-size: 9.5pt;
            line-height: 1.6;
            text-align: left;
        }

        /* Select2 sobre #procedure: mismo look (borde azul claro, esquinas
           redondeadas) que .procedure-select/.include-input, para que el
           buscador se sienta parte del documento y no un widget aparte. */
        .select2-container--default .select2-selection--single {
            height: auto;
            padding: 6px 8px;
            border: 1px solid #BFDDF6;
            border-radius: 8px;
            background-color: #FAFBFC;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0;
            font-size: 9.5pt;
            font-family: sans-serif;
            color: #1F2A37;
            line-height: normal;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
        }

        .select2-container {
            display: block;
            width: 100% !important;
            margin-top: 6px;
        }
    </style>
</head>

<body class="{{ $isPdf ?? false ? 'pdf-mode' : '' }}">
    <input type="hidden" value="{{ route('surgery_authorization.pdf', $reception->id) }}" id="reception">

    @php
        /*
         * Mismo helper de íconos que reception/pdf.blade.php (Autorización de
         * Hospital): SVGs auto-contenidos en base64, para que DomPDF los
         * renderice como <img> igual que el logo. No se duplica el archivo de
         * íconos en sí, solo el helper (documento-base.css es la hoja
         * compartida; el PHP de cada vista sigue siendo propio de cada una).
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
                    <p class="clinic-sub">Autorización de procedimientos anestésicos y quirúrgicos</p>
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

        {{-- Términos y condiciones: mismos 10 puntos, mismo texto, mismos
             campos capturables (procedimiento/total/incluye del punto 1) —
             solo con el formato de tabla numerada que ya usa Hospital. El
             salto de página antes del punto 8 conserva el mismo corte que
             ya tenía el documento anterior (ahí terminaba el primer <ol> y
             empezaba <ol start="8">). --}}
        <div class="section">
            <div class="terms-label">Términos y condiciones</div>
            <div class="terms-box {{ $isPdf ?? false ? '' : 'scrollable' }}">
                <table class="terms-table">
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">1</span></td>
                        <td class="term-text">
                            Por medio de la presente autorizo la realización del procedimiento quirúrgico y/o
                            anestésico:
                            @if (!isset($isPdf) || !$isPdf)
                                <select id="procedure" name="procedure" class="procedure-select">
                                    <option value="">Selecciona el procedimiento a realizar</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->ARTICULO_ID }}"
                                            data-nombre="{{ $product->NOMBRE }}" data-price="{{ $product->PRECIO }}"
                                            {{ old('procedure', $procedure ?? '') == $product->ARTICULO_ID ? 'selected' : '' }}>
                                            {{ $product->NOMBRE }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <span><strong>{{ $procedure ?? '' }}</strong></span>
                            @endif
                            mismo que ha sido explicado por el médico, por lo que estoy consciente de los beneficios y
                            riesgos que indica el mismo. El presupuesto de dicha intervención es de
                            <b>TOTAL $
                                @if (!isset($isPdf) || !$isPdf)
                                    <input type="text" id="total" name="total" value="{{ $total ?? '' }}"
                                        class="total-input" readonly>
                                @else
                                    <span class="price-highlight">{{ $total ?? '' }}</span>
                                @endif
                            </b>
                            <br><b>INCLUYE</b>
                            @if (!isset($isPdf) || !$isPdf)
                                <input type="text" id="include" name="include" value="{{ $include ?? '' }}"
                                    class="include-input">
                            @else
                                <span>{{ $include ?? '' }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">2</span></td>
                        <td class="term-text">
                            Si dentro del procedimiento quirúrgico se detecta alguna otra patología, seré notificado
                            oportunamente para la autorización y modificación del costo de la cirugía.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">3</span></td>
                        <td class="term-text">
                            Si mi mascota ingresa el mismo día a procedimiento quirúrgico, <b>me hago responsable del
                                ayuno de sólidos</b> de 8 horas en adultos y de 6 horas en cachorros.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">4</span></td>
                        <td class="term-text">
                            Estoy consciente que el procedimiento quirúrgico y/o anestésico por sencillo o complicado
                            que este sea, precisa que mi mascota sea anestesiada, además de que reciba terapia pre-,
                            trans-, y post-operatoria, dependiendo de la idiosincrasia y naturaleza individual del
                            paciente, razón por lo cual acepto los riesgos por la aplicación de los medicamentos. Y
                            que, si por alguna causa este falleciera, no presentaré reclamación posterior de ninguna
                            índole.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">5</span></td>
                        <td class="term-text">
                            Por políticas de la empresa el propietario tendrá que realizar un depósito del 50% del
                            costo total (SOLO ELECTIVAS) del procedimiento a realizar para llevarse a cabo, en
                            CIRUGÍAS DE URGENCIA se cubrirá el COSTO TOTAL, el 100%. El presupuesto cubre lo
                            siguiente:
                            <ul>
                                <li>Un día de hospitalización postquirúrgico.</li>
                                <li>El procedimiento anestésico y quirúrgico.</li>
                                <li>Medicación que requiera durante el procedimiento.</li>
                                <li>Una revisión y retiro de sutras 10 días después o cuando el médico lo indique (En
                                    caso de requerir sedación, el costo será extra).</li>
                            </ul>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">6</span></td>
                        <td class="term-text">
                            En caso de que el paciente presente alguna complicación y/o tenga que permanecer más
                            tiempo hospitalizado los gastos generados, estudios y hospitalización serán autorizados y
                            pagados por el propietario ya que no están incluidos en el presupuesto de la cirugía.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">7</span></td>
                        <td class="term-text">
                            En el momento en que mi mascota me sea entregada deberá estar liquidada la cuenta total,
                            me comprometo a realizar los cuidados postoperatorios que se me indiquen por escrito. Así
                            como asistir en forma puntual a las revisiones que se me indiquen después de la cirugía.
                        </td>
                    </tr>
                    <tr class="term-row" style="{{ $isPdf ?? false ? 'page-break-before: always;' : '' }}">
                        <td class="term-badge"><span class="term-badge-circle">8</span></td>
                        <td class="term-text">
                            Los exámenes de laboratorio que indique el medico a cargo, son indispensables para
                            efectuar el procedimiento anestésico y/o quirúrgico, mismos que tendrán un costo extra
                            para el propietario.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">9</span></td>
                        <td class="term-text">
                            Solo se dará información a una sola persona la cual firma la orden de autorización para
                            evitar problemas de malentendidos. La persona que autoriza deberá ser mayor de edad.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">10</span></td>
                        <td class="term-text">
                            Hacemos de su conocimiento que, si su mascota no es recogida en la fecha indicada por el
                            médico, pasará a nuestro servicio de pensión lo cual implicara el costo extra de la
                            misma, si después de 3 días no pasan a recoger al paciente sin previo aviso se considerara
                            abandonado por lo que la empresa podrá disponer de ella a su conveniencia, sin reclamo
                            posterior.
                        </td>
                    </tr>
                </table>
            </div>
            @if (!($isPdf ?? false))
                <p class="terms-hint">Desliza para leer el resto de los términos &darr;</p>
            @endif
        </div>

        {{-- Firma --}}
        <div class="signature-section">
            <table class="signature-header-table">
                <tr>
                    <td style="text-align:left;">
                        <p class="signature-title">Firma de autorización</p>
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
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @if (!($isPdf ?? false))
        <script src="{{ asset('js/select2.min.js') }}"></script>
    @endif
    <script src="{{ asset('js/formats/auth_surgery.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
        const CAME_FROM_TRANSFER = @json($cameFromTransfer ?? false);
        // ?from=reception en la URL (ver SurgeryController::
        // surgery_authorization()) distingue si esta responsiva se abrió
        // desde el modal de Documentos de Recepción o desde el flujo propio
        // de Hospital, para decidir a dónde volver después de firmar (ver
        // auth_surgery.js) — mismo criterio que reception/pdf.blade.php.
        const FROM_RECEPTION = @json($fromReception ?? false);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
