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
        /*
         * Estilos propios de Presupuesto: lo único que documento-base.css
         * (compartido con reception/pdf.blade.php) no cubre todavía —
         * tabla de conceptos, badge de estatus y firma en dos columnas
         * (médico + propietario, a diferencia de la firma única de
         * Aut Hospital). Mismos tokens de color que documento-base.css
         * (#0455A0, #F5F9FD, #E5E7EB, #6B7280) para que se sienta parte
         * del mismo documento, no un estilo aparte.
         */
        .clinic-folio {
            font-size: 8pt;
            color: #9CA3AF;
            margin: 2px 0 0;
        }

        .status-badge {
            display: inline-block;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            padding: 3px 10px;
            border-radius: 20px;
            background-color: #E7F7EE;
            color: #1A7F4E;
            margin-top: 6px;
        }

        .budget-items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .budget-items-table thead th {
            background-color: #0455A0;
            color: #fff;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: .02em;
            padding: 8px 10px;
            text-align: left;
        }

        .budget-items-table tbody td {
            padding: 8px 10px;
            font-size: 9.5pt;
            color: #374151;
            border-bottom: 1px solid #E5E7EB;
            vertical-align: top;
        }

        .item-type-badge {
            display: inline-block;
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #0455A0;
            background-color: #EAF2FB;
            border: 1px solid #BFDDF6;
            border-radius: 6px;
            padding: 1px 6px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .item-name {
            font-weight: 600;
            color: #1F2A37;
        }

        .item-notes {
            color: #6B7280;
        }

        .item-price {
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
            color: #1F2A37;
        }

        .budget-items-table tfoot .total-row td {
            border-top: 2px solid #0455A0;
            border-bottom: none;
            padding-top: 10px;
            font-size: 11pt;
            font-weight: 700;
            color: #0455A0;
        }

        .note-text {
            font-size: 8.5pt;
            color: #6B7280;
            font-style: italic;
            line-height: 1.5;
            margin: 6px 0 0;
        }

        /* Firma en dos columnas: reutiliza .card-cell/.signature-box de
           documento-base.css, solo acota el ancho máximo de cada mitad. */
        .signature-cell .signature-box,
        .signature-cell .signature-image {
            max-width: 100%;
        }

        .signature-cell-label {
            font-size: 8.5pt;
            font-weight: 700;
            color: #1F2A37;
            text-align: center;
            margin: 0 0 8px;
        }
    </style>
</head>

<body class="{{ ($isPdf ?? false) ? 'pdf-mode' : '' }}">

    @php
        /*
         * Mismo helper de íconos que reception/pdf.blade.php (Aut Hospital):
         * SVGs auto-contenidos en base64 para que DomPDF los renderice como
         * <img>. {color} se reemplaza por el color que le pases a $icon().
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

        $folio = str_pad($budget->id, 4, '0', STR_PAD_LEFT);
        $signed = isset($signatureDataUrl) || isset($signatureDataUrl2);

        // Mismas 3 categorías reales de budget_details (service_id / lab_id /
        // img_id son columnas mutuamente excluyentes, ver BudgetDetail) — se
        // aplanan en una sola tabla con una etiqueta de tipo por renglón, en
        // vez de repetir un <thead> por categoría como antes.
        $rows = collect();
        foreach ($details->whereNotNull('service_id') as $d) {
            $rows->push(['type' => 'Servicio', 'name' => $d->serv->NOMBRE ?? 'N/A', 'notes' => $d->notes, 'price' => $d->price]);
        }
        foreach ($details->whereNotNull('lab_id') as $d) {
            $rows->push(['type' => 'Laboratorio', 'name' => $d->lab->NOMBRE ?? 'N/A', 'notes' => $d->notes, 'price' => $d->price]);
        }
        foreach ($details->whereNotNull('img_id') as $d) {
            $rows->push(['type' => 'Imagen', 'name' => $d->img->NOMBRE ?? 'N/A', 'notes' => $d->notes, 'price' => $d->price]);
        }
    @endphp

    <div class="card">

        {{-- Header: logo + nombre de la clínica + folio/fecha/médico a la
             izquierda, contacto a la derecha — mismo patrón que Aut Hospital. --}}
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
                    <p class="clinic-sub">Presupuesto de servicios</p>
                    <p class="clinic-meta">{!! $icon('doc', 12, '#0455A0') !!}Folio {{ $folio }} &middot;
                        {{ \Carbon\Carbon::parse($budget->date)->format('d/m/Y') }}</p>
                    <p class="clinic-folio">{!! $icon('user', 11, '#9CA3AF') !!}Médico responsable:
                        {{ $budget->vet->name ?? 'N/A' }}</p>
                </td>
                <td class="header-contact">
                    <p>{!! $icon('pin', 11) !!}Blvd. Luis Donaldo Colosio 764, 25205 Saltillo, Coahuila</p>
                    <p>{!! $icon('phone', 11) !!}844 485 1999</p>
                    <p>{!! $icon('mail', 11) !!}admpetscare@gmail.com</p>
                    @if ($signed)
                        <span class="status-badge">Firmado</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Propietario + Mascota, lado a lado --}}
        <div class="section">
            <x-document.owner-pet-cards :pet="$budget->pet" :show-email="true" :show-physic-description="true" />
        </div>

        {{-- Tabla de conceptos presupuestados --}}
        <div class="section">
            <div class="terms-label">{!! $icon('doc', 14, '#0455A0') !!}Servicios presupuestados</div>
            <table class="budget-items-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Concepto</th>
                        <th style="width: 25%;">Notas</th>
                        <th style="width: 20%; text-align: right;">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>
                                <span class="item-type-badge">{{ $row['type'] }}</span>
                                <span class="item-name">{{ $row['name'] }}</span>
                            </td>
                            <td class="item-notes">{{ $row['notes'] ?? '—' }}</td>
                            <td class="item-price">${{ number_format($row['price'] ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="item-notes" style="text-align: center;">Sin conceptos
                                registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">GRAN TOTAL</td>
                        <td class="item-price">${{ number_format($budget->total ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Avisos --}}
        <div class="section">
            <p class="note-text">El día de cirugía indicado deberá traer su hoja de presupuesto o perderá el costo
                indicado de su cirugía o la cita de cirugía de su mascota.</p>
            <p class="note-text">Si tienes dudas, pregúntale al médico a cargo de tu mascota, con gusto las
                resolverá, o puedes marcar a los teléfonos del hospital.</p>
        </div>

        {{-- Firma: médico + propietario, lado a lado (a diferencia de Aut
             Hospital, que solo firma el propietario). --}}
        <div class="signature-section">
            <table class="signature-header-table">
                <tr>
                    <td style="text-align:left;">
                        <p class="signature-title">{!! $icon('pencil', 14, '#0455A0') !!}Firmas de autorización</p>
                    </td>
                </tr>
            </table>
            <table class="cards-table">
                <tr>
                    <td class="card-cell signature-cell" style="padding-right: 8px;">
                        <p class="signature-cell-label">Médico</p>
                        @if (isset($signatureDataUrl))
                            <img src="{{ $signatureDataUrl }}" alt="Firma del médico" class="signature-image">
                        @else
                            <canvas id="canvas" class="signature-box" width="230" height="100"></canvas>
                            <p class="signature-placeholder" style="margin-top: 6px;">Firme aquí</p>
                            @if (!isset($isPdf) || !$isPdf)
                                <p style="text-align:center; margin-top: 6px;">
                                    <button type="button" class="btnLimpiar btn-limpiar-sm" data-target="canvas">
                                        {!! $icon('trash', 11, '#0455A0') !!}Limpiar</button>
                                </p>
                            @endif
                        @endif
                    </td>
                    <td class="card-cell signature-cell" style="padding-left: 8px;">
                        <p class="signature-cell-label">Propietario</p>
                        @if (isset($signatureDataUrl2))
                            <img src="{{ $signatureDataUrl2 }}" alt="Firma del propietario" class="signature-image">
                        @else
                            <canvas id="canvas2" class="signature-box" width="230" height="100"></canvas>
                            <p class="signature-placeholder" style="margin-top: 6px;">Firme aquí</p>
                            @if (!isset($isPdf) || !$isPdf)
                                <p style="text-align:center; margin-top: 6px;">
                                    <button type="button" class="btnLimpiar btn-limpiar-sm" data-target="canvas2">
                                        {!! $icon('trash', 11, '#0455A0') !!}Limpiar</button>
                                </p>
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- Botón de aceptar (solo en la vista interactiva, no en el PDF final) --}}
        @if (!isset($isPdf) || !$isPdf)
            <div class="actions-footer">
                <form style="margin:0;">
                    <button type="submit" class="btnEnviar btn-aceptar">
                        {!! $icon('pencil', 12, '#fff') !!}Aceptar y firmar</button>
                </form>
            </div>
        @endif

    </div>

    <div style="page-break-before: always;"></div>

    {{-- Recomendaciones para cirugía: mismo componente de cláusulas
         numeradas (.terms-box/.term-row/.term-badge-circle) que usa
         Aut Hospital para sus términos y condiciones. --}}
    <div class="card" style="margin-top: 16px;">
        <div class="section" style="padding-top: 18px;">
            <div class="terms-label" style="text-align: center;">Recomendaciones para cirugía</div>
            <p class="note-text" style="text-align: center; margin-bottom: 10px;">Si tu mascota va a entrar a
                cirugía es muy importante que tomes en cuenta los siguientes puntos.</p>
            <div class="terms-box">
                <table class="terms-table">
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">1</span></td>
                        <td class="term-text">
                            El presupuesto de la cirugía y estudios pre anestésicos (de acuerdo con la edad) tienen
                            una <b>vigencia de 10 días.</b> En caso de que pase la fecha señalada, nuevamente se
                            tendrá que valorar clínicamente para cotizar la cirugía.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">2</span></td>
                        <td class="term-text">
                            El costo de la cirugía solo incluye medicamentos administrados durante el procedimiento
                            quirúrgico/anestésico. <b>Los medicamentos recetados y accesorios para llevar el
                                tratamiento a casa tendrán un COSTO EXTRA.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">3</span></td>
                        <td class="term-text">
                            Las cirugías se <b>PROGRAMAN</b> mínimo con <b>TRES DÍAS de anticipación.</b> Si
                            <b>NO</b> acudes el día y hora indicado, se <b>REAGENDARÁ</b> de acuerdo con la
                            disponibilidad del quirófano.
                        </td>
                    </tr>
                    <tr class="term-row" style="{{ $isPdf ?? false ? 'page-break-before: always;' : '' }}">
                        <td class="term-badge"><span class="term-badge-circle">4</span></td>
                        <td class="term-text">
                            El día de la cirugía, el paciente deberá acudir en <b>AYUNO</b> mínimo de <b>8 horas</b>
                            en <b>ADULTOS</b> y de <b>4 en CACHORROS</b>, retirando el agua 4 horas antes. <b>No
                                aplica para animales exóticos.</b>
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">5</span></td>
                        <td class="term-text">
                            Los pacientes se <b>ingresan</b> al hospital en un horario de <b>8:00 am a 10:00 am</b>,
                            o bien respetando el horario que el médico indique, leyendo y llenando debidamente la
                            hoja de autorización de cirugía; si tienes dudas pregunta al médico a cargo.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">6</span></td>
                        <td class="term-text">
                            En <b>CIRUGÍAS AMBULATORIAS</b> el paciente <b>se da de ALTA antes de las 6:00 pm</b>; si
                            permanece después de ese horario, se generarán cargos extras.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">7</span></td>
                        <td class="term-text">
                            Se puede bañar al paciente un día antes de la cirugía, <b>no ese mismo día.</b> Después
                            de la cirugía el médico informará en cuánto tiempo se puede bañar.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">8</span></td>
                        <td class="term-text">
                            En caso de requerir una esterilización para hembra, se podrá hacer solamente si no está
                            en su periodo de celo; en caso contrario tendrá que dejar pasar 1 mes después de dicho
                            periodo para realizar el procedimiento de cirugía (felinos no aplica).
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">9</span></td>
                        <td class="term-text">
                            <b>IMPORTANTE.</b> El orden de los procedimientos quirúrgicos <b>DEPENDERÁ</b> de la
                            <b>COMPLEJIDAD Y GRAVEDAD</b> del mismo procedimiento, por ende, no hay un horario
                            específico de ingreso al quirófano, eso será en razón del criterio del jefe del área. En
                            este mismo sentido, cabe aclarar que los procedimientos electivos (profilaxis dental,
                            castración, OSH/OVH) no son procedimientos de urgencia, por lo cual son los últimos en
                            realizarse.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">10</span></td>
                        <td class="term-text">
                            En el caso de cesáreas, la recuperación del útero se llevará siempre y cuando el útero
                            esté viable y no comprometa la vida del paciente; en caso contrario el cirujano tiene la
                            autorización para retirar el órgano. Ante todo, la vida del paciente está sobre toda
                            posibilidad de reproducción.
                        </td>
                    </tr>
                    <tr class="term-row">
                        <td class="term-badge"><span class="term-badge-circle">11</span></td>
                        <td class="term-text">
                            <b>Indicaciones a tener en cuenta después de la cirugía:</b> no se debe lamer la herida
                            (usar collar isabelino las 24 horas del día); cumplir con la administración de los
                            medicamentos recetados; realizar mínimo una limpieza de la herida al día con antiséptico
                            (MICRODACYN o VETERIBAC) y gasas, salvo indicación contraria del médico; permanecer en un
                            lugar limpio y seco, evitando salir a la calle, mínimo hasta el día de su revisión; en
                            cirugías ortopédicas o mayores, seguir las indicaciones específicas de la receta; y
                            asistir puntualmente a sus seguimientos post quirúrgicos.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/budgets/pdf.js') }}" defer></script>
    <script>
        const BUDGET_ID = "{{ $budget->id }}";
        const RECEPTION_ID = "{{ $budget->reception_id }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
