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

        /* DomPDF no soporta flexbox/grid: todo el layout usa tablas,
           igual que el documento original, solo con colores/espaciado
           nuevos aplicados encima. */
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

        /* Solo en pantalla interactiva: caja con altura fija y scroll.
           En el PDF, el texto fluye normal a través de las páginas. */
        .terms-box.scrollable {
            height: 220px;
            overflow-y: auto;
        }

        .terms-box li {
            margin-bottom: 10px;
        }

        .terms-box li:last-child {
            margin-bottom: 0;
        }

        .price-highlight {
            color: #0455A0;
            font-weight: 700;
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
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('hospital.pdf', $reception->id) }}" id="reception">

    <div class="card">

        {{-- Header: logo + datos de la clínica (se conserva la estructura
             original de logo a la izquierda + info a la derecha) --}}
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
                    <p class="title">AUTORIZACIÓN PARA HOSPITALIZACIÓN</p>
                    <p class="fecha">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>

        {{-- Datos de quien suscribe --}}
        <div class="section">
            <table class="info-box">
                <tr>
                    <td>
                        <p style="margin:0;"><b>El que suscribe:</b> {{ $reception->pet->family->name }}</p>
                        <p style="margin:4px 0 0;"><b>Tel:</b> {{ $reception->pet->family->phone }}</p>
                        <p style="margin:4px 0 0;"><b>Domicilio:</b> {{ $reception->pet->family->address }}</p>
                        <p style="margin:4px 0 0;"><b>En caso de emergencia comunicarse con:</b>
                            {{ $reception->pet->family->contact_name }}</p>
                        <p style="margin:4px 0 0;"><b>Tel de emergencia:</b>
                            {{ $reception->pet->family->contact_number }}</p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Datos de la mascota --}}
        <div class="section">
            <div class="pet-box">
                <div class="label">Mascota</div>
                @php
                    $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                    $now = \Carbon\Carbon::now();
                    $years = $birthday->diffInYears($now);
                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                @endphp
                <table style="width: 100%;">
                    <tr>
                        <td>Nombre: {{ $reception->pet->name }}</td>
                        <td>Especie: {{ $reception->pet->specie }}</td>
                    </tr>
                    <tr>
                        <td>Raza: {{ $reception->pet->raza ?: '—' }}</td>
                        <td>Edad: {{ $years }} años, {{ $months }} meses</td>
                    </tr>
                    <tr>
                        <td>Sexo: {{ $reception->pet->genre->name }}</td>
                        <td>Peso: {{ $reception->pet->weight ?: '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Términos y condiciones --}}
        <div class="section">
            <div class="terms-label">Términos y condiciones</div>
            <div class="terms-box {{ $isPdf ?? false ? '' : 'scrollable' }}">
                <ol style="margin: 0; padding-left: 18px;">
                    <li>
                        Por medio del presente otorgo mi autorización para que la mascota anteriormente descrita sea
                        hospitalizada. Durante el tiempo que permanezca internada estoy consciente que tendrá que ser
                        rasurada para canalizar, toma de muestras, rasurado para evitar lesiones en piel o estudio
                        ultrasonográfico, en una o distintas áreas del cuerpo, así como la administración de
                        medicamentos, fluidos y pruebas adicionales (laboratorio e imagen) necesarias con el fin de
                        preservar su vida, dándole la atención médica de acuerdo a su padecimiento. (Todos los
                        estudios adicionales serán previamente informados a sus tutores antes de realizarlos).
                    </li>
                    <li>
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
                        . La hospitalización incluye la atención médica 24 hrs del día, insumos de hospital, alimento
                        y medicamentos de cabecera, importante mencionar que el alimento es de
                        <b>MANTENIMIENTO, EN CASO DE QUE SU MASCOTA REQUIERA UN ALIMENTO DE PRESCRIPCIÓN ESTE TENDRÁ
                            UN COSTO EXTRA.</b> El cubículo de tratamiento será de acuerdo al tamaño de la mascota.
                        Este precio no incluye alimentos de prescripción, medicamentos especiales, estudios de
                        laboratorio o imagen y procedimientos médicos/quirúrgicos extras.
                    </li>
                    <li>
                        En caso de presentarse una urgencia, el medico tendrá la obligación de comunicarse con el
                        propietario, de no obtener respuesta se procederá a hacer lo que el paciente necesite para
                        mantener la vida de su mascota, dicho servicio extraordinario será considerado a entera
                        satisfacción del propietario.
                    </li>
                    <li>
                        Así mismo acepto los riesgos que el uso de dichos procedimientos deriven y que son
                        dependientes de la respuesta biológica de cada organismo y que si por la gravedad de la
                        enfermedad falleciera no presentaré reclamación y pagaré todos los gastos generados por el
                        servicio durante el tiempo de hospitalización.
                    </li>
                    <li>
                        Los pacientes no deberán ser ingresados con collar o correa al hospital. Se permitirá en todo
                        caso camas, juguetes o accesorios a los que la mascota este acostumbrada.
                    </li>
                    <li>
                        Ninguna mascota podrá ingresar a hospital si llegara a presentar algún ectoparásito, en tal
                        caso se deberán de seguir las indicaciones del médico.
                    </li>
                    <li style="{{ $isPdf ?? false ? 'page-break-before: always;' : '' }}">
                        El propietario será informado diariamente vía telefónica por el médico a cargo sobre la
                        evolución de su mascota. <b>Podrá visitarlo únicamente en un horario de 3:00pm a 5:00pm,
                            durante un tiempo no mayor a 15 min. Los días domingos y días festivos no hay
                            visitas.</b>
                    </li>
                    <li>
                        Los pacientes serán dados de alta en un horario de 10:00 am a 7:00 pm. El medico a cargo les
                        informara oportunamente el día y hora de salida de su mascota. Para tal efecto la cuenta
                        correspondiente deberá de estar liquidada en su totalidad. En caso de no ser recogida en la
                        fecha y hora indicada por el médico, pasará a nuestra área de pensión lo cual implicara el
                        costo de la misma, si después de tres días <b>el propietario no se reporta</b>, la mascota
                        será considerada abandonada y la empresa podrá disponer de ella como mejor le convenga,
                        <b>sin reclamo alguno.</b>
                    </li>
                    <li>
                        <b>Me comprometo a realizar los cuidados y dar la medicación que se me indiquen por
                            escrito.</b> Así como asistir en forma puntual a las revisiones. Los médicos y la empresa
                        no se hacen responsables de la evolución del paciente en caso de no seguir el tratamiento.
                    </li>
                    <li>
                        <b>No se aceptan niños menores de 16 años, o tendrá que estar un adulto presente, el uso de
                            cubrebocas en nuestras instalaciones es obligatorio.</b>
                    </li>
                    <li>
                        <b>Al momento de ingresar al paciente, se deberá de cubrir con un 50% del pago total de la
                            cuenta y en caso de Ser URGENCIA o que sea horario de urgencias (8:00pm - 8:00am) se
                            deberá cubrir el costo TOTAL de la cuenta.</b>
                    </li>
                    <li>
                        <b>EXISTE LA POSIBILIDAD QUE SU MASCOTA REQUIERA DE MEDICAMENTOS ESPECIALIZADOS DE
                            "COSTO EXTRA" EN CASO DE SER NECESARIO SU MEDICO TRATANTE SE COMUNICARA CON UD PARA
                            SU PREVIA AUTORIZACIÓN.</b>
                    </li>
                    <li>
                        <b>LOS TUTORES LEGALES DEL PACIENTE QUE QUEDA BAJO HOSPITALIZACIÓN Y/O ESTÉTICA CANINA
                            CONTARAN CON UN PLAZO NO MAYOR DE 96 HORAS (4 DÍAS) PARA RESPONDER POR CUALQUIER MEDIO DE
                            COMUNICACIÓN AL LLAMADO DE LA CLINICA. PASADO ESTE TIEMPO SE TOMARÁ COMO ABANDONO DE LA
                            MASCOTA Y LA CLINICA TENDRA LA DISPOSICION LEGAL DE LA MASCOTA Y LA TOMA DE
                            DECISIONES.</b>
                    </li>
                </ol>
                @if (!($isPdf ?? false))
                    <p style="margin:6px 0 0; color:#9CA3AF; text-align:center; font-size: 8.5pt;">Desliza para leer el
                        resto de los términos &darr;</p>
                @endif
            </div>
        </div>

        {{-- Firma --}}
        <div class="signature-section">
            <p class="signature-title">Firma de autorización</p>

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
    <script src="{{ asset('js/receptions/hospital_auth.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
        const CAME_FROM_TRANSFER = @json($cameFromTransfer ?? false);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
