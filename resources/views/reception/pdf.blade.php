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
</head>

<body class="{{ $isPdf ?? false ? 'pdf-mode' : '' }}">
    <input type="hidden" value="{{ route('hospital.pdf', $reception->id) }}" id="reception">

    @php
        /*
         * Helper de íconos: SVGs auto-contenidos en base64, para que DomPDF
         * los renderice como <img> (mismo mecanismo que ya usan para el
         * logo). {color} se reemplaza por el color que le pases a $icon().
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
             contacto (dirección / teléfono / correo) a la derecha --}}
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
                    <p class="clinic-sub">Autorización para hospitalización</p>
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

        {{-- Propietario + Mascota, lado a lado --}}
        <div class="section">
            <x-document.owner-pet-cards :pet="$reception->pet" :show-emergency-contact="true" />
        </div>

        {{-- Términos y condiciones --}}
        <div class="section">
            <div class="terms-label">Términos y condiciones</div>
            <div class="terms-box {{ $isPdf ?? false ? '' : 'scrollable' }}">
                <table class="terms-table">
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">1</span></td>
                        <td class="term-text">
                            Por medio del presente otorgo mi autorización para que la mascota anteriormente descrita
                            sea hospitalizada. Durante el tiempo que permanezca internada estoy consciente que tendrá
                            que ser rasurada para canalizar, toma de muestras, rasurado para evitar lesiones en piel
                            o estudio ultrasonográfico, en una o distintas áreas del cuerpo, así como la
                            administración de medicamentos, fluidos y pruebas adicionales (laboratorio e imagen)
                            necesarias con el fin de preservar su vida, dándole la atención médica de acuerdo a su
                            padecimiento. (Todos los estudios adicionales serán previamente informados a sus tutores
                            antes de realizarlos).
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">2</span></td>
                        <td class="term-text">
                            El costo del servicio de hospitalización por día es de
                            $
                            @if (!isset($isPdf) || !$isPdf)
                                {{-- Dato informativo que viene directo de Microsip (ver
                                     ReceptionController::hospital_authorization()): readonly
                                     para TODOS los usuarios, sin excepción. readonly (no
                                     disabled) para que el valor viaje en el submit del form,
                                     aunque el JS de todos modos lo manda aparte
                                     (ver hospital_auth.js: formData.append('total', total)). --}}
                                <input type="text" id="total" value="{{ $total ?? '' }}"
                                    class="form-control price-highlight"
                                    style="width: 110px; display:inline-block; border:none; background:transparent; font-size:9.5pt; padding:0;"
                                    readonly>
                            @else
                                <span class="price-highlight">{{ $total ?? '' }}</span>
                            @endif
                            . La hospitalización incluye la atención médica 24 hrs del día, insumos de hospital,
                            alimento y medicamentos de cabecera, importante mencionar que el alimento es de
                            <b>MANTENIMIENTO, EN CASO DE QUE SU MASCOTA REQUIERA UN ALIMENTO DE PRESCRIPCIÓN ESTE
                                TENDRÁ UN COSTO EXTRA.</b> El cubículo de tratamiento será de acuerdo al tamaño de la
                            mascota. Este precio no incluye alimentos de prescripción, medicamentos especiales,
                            estudios de laboratorio o imagen y procedimientos médicos/quirúrgicos extras.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">3</span></td>
                        <td class="term-text">
                            En caso de presentarse una urgencia, el medico tendrá la obligación de comunicarse con el
                            propietario, de no obtener respuesta se procederá a hacer lo que el paciente necesite
                            para mantener la vida de su mascota, dicho servicio extraordinario será considerado a
                            entera satisfacción del propietario.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">4</span></td>
                        <td class="term-text">
                            Así mismo acepto los riesgos que el uso de dichos procedimientos deriven y que son
                            dependientes de la respuesta biológica de cada organismo y que si por la gravedad de la
                            enfermedad falleciera no presentaré reclamación y pagaré todos los gastos generados por
                            el servicio durante el tiempo de hospitalización.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">5</span></td>
                        <td class="term-text">
                            Los pacientes no deberán ser ingresados con collar o correa al hospital. Se permitirá en
                            todo caso camas, juguetes o accesorios a los que la mascota este acostumbrada.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">6</span></td>
                        <td class="term-text">
                            Ninguna mascota podrá ingresar a hospital si llegara a presentar algún ectoparásito, en
                            tal caso se deberán de seguir las indicaciones del médico.
                        </td>
                    </tr>
                    <tr class="term-row" style="{{ $isPdf ?? false ? 'page-break-before: always;' : '' }}">
                        <td class="term-badge"><span class="term-badge-circle">7</span></td>
                        <td class="term-text">
                            El propietario será informado diariamente vía telefónica por el médico a cargo sobre la
                            evolución de su mascota. <b>Podrá visitarlo únicamente en un horario de 3:00pm a 5:00pm,
                                durante un tiempo no mayor a 15 min. Los días domingos y días festivos no hay
                                visitas.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">8</span></td>
                        <td class="term-text">
                            Los pacientes serán dados de alta en un horario de 10:00 am a 7:00 pm. El medico a cargo
                            les informara oportunamente el día y hora de salida de su mascota. Para tal efecto la
                            cuenta correspondiente deberá de estar liquidada en su totalidad. En caso de no ser
                            recogida en la fecha y hora indicada por el médico, pasará a nuestra área de pensión lo
                            cual implicara el costo de la misma, si después de tres días <b>el propietario no se
                                reporta</b>, la mascota será considerada abandonada y la empresa podrá disponer de
                            ella como mejor le convenga, <b>sin reclamo alguno.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">9</span></td>
                        <td class="term-text">
                            <b>Me comprometo a realizar los cuidados y dar la medicación que se me indiquen por
                                escrito.</b> Así como asistir en forma puntual a las revisiones. Los médicos y la
                            empresa no se hacen responsables de la evolución del paciente en caso de no seguir el
                            tratamiento.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">10</span></td>
                        <td class="term-text">
                            <b>No se aceptan niños menores de 16 años, o tendrá que estar un adulto presente, el uso
                                de cubrebocas en nuestras instalaciones es obligatorio.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">11</span></td>
                        <td class="term-text">
                            <b>Al momento de ingresar al paciente, se deberá de cubrir con un 50% del pago total de
                                la cuenta y en caso de Ser URGENCIA o que sea horario de urgencias (8:00pm - 8:00am)
                                se deberá cubrir el costo TOTAL de la cuenta.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">12</span></td>
                        <td class="term-text">
                            <b>EXISTE LA POSIBILIDAD QUE SU MASCOTA REQUIERA DE MEDICAMENTOS ESPECIALIZADOS DE
                                "COSTO EXTRA" EN CASO DE SER NECESARIO SU MEDICO TRATANTE SE COMUNICARA CON UD PARA
                                SU PREVIA AUTORIZACIÓN.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">13</span></td>
                        <td class="term-text">
                            <b>LOS TUTORES LEGALES DEL PACIENTE QUE QUEDA BAJO HOSPITALIZACIÓN Y/O ESTÉTICA CANINA
                                CONTARAN CON UN PLAZO NO MAYOR DE 96 HORAS (4 DÍAS) PARA RESPONDER POR CUALQUIER
                                MEDIO DE COMUNICACIÓN AL LLAMADO DE LA CLINICA. PASADO ESTE TIEMPO SE TOMARÁ COMO
                                ABANDONO DE LA MASCOTA Y LA CLINICA TENDRA LA DISPOSICION LEGAL DE LA MASCOTA Y LA
                                TOMA DE DECISIONES.</b>
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
                    {{-- @if (!isset($isPdf) || !$isPdf)
                        <td style="text-align:right;">
                            <button type="button" class="btnLimpiar btn-limpiar-sm" data-target="canvas">
                                {!! $icon('trash', 11, '#0455A0') !!}Limpiar firma</button>
                        </td>
                    @endif --}}
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
                                    {!! $icon('pencil', 12, '#fff') !!}Aceptar y firmar</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/receptions/hospital_auth.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
        const CAME_FROM_TRANSFER = @json($cameFromTransfer ?? false);
        // ?from=reception en la URL (ver ReceptionController::
        // hospital_authorization()) distingue si esta responsiva se abrió
        // desde el modal de Documentos de Recepción o desde el flujo propio
        // de Hospital, para decidir a dónde volver después de firmar (ver
        // hospital_auth.js).
        const FROM_RECEPTION = @json($fromReception ?? false);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
