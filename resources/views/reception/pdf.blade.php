<!DOCTYPE html>
<html lang="en">

<head>
    <style type="text/css">
        @import url(https://themes.googleusercontent.com/fonts/css?kit=fOEonugfEEW2k3BWBOC73CXHfZMcH88HuPcErL5npACHpuVWaP-GHFPZzt35558q);

        .head {
            color: #646c9a;
            font-weight: 700;
            font-size: 12pt;
            font-weight: bold;
            font-family: "sans-serif;";
        }

        .body {
            font-size: 10pt;
            font-family: "sans-serif;";
            margin: 0;

        }

        .footer {
            text-align: right;
            padding-top: 20px;
            border-top: 2px solid #f1f1f1;
            font-size: 9pt;
            color: #888;
        }

        .footer p {
            margin: 0;
        }

        table {
            table-layout: fixed;
            font-family: sans-serif;
        }

        th,
        td {
            width: 100px;
            style=padding: 5px 10px;
            font-size: 12pt;
        }

        li {
            text-align: justify;
            font-size: 12pt;
            margin-top: 1%;
            font-family: sans-serif;
            ;
        }

        p {
            font-size: 12pt;
            font-family: sans-serif;
            margin: 0;
        }
    </style>
</head>

<body>
    <input type="text" style="display: none" value="{{ route('hospital.pdf', $reception->id)}}" id="reception">
    <div>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="align-items: center;">
                    {{-- <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px"> --}}

                </td>
                <td>
                    <p class="head">Hospital Veterinario Pets Care</p>
                    <p style="font-family: sans-serif; font-size: 10pt;">
                        SMV160511UY0 <br>
                        Blvd. Luis Donaldo Colosio 764, <br>
                        25205 Saltillo, Coahuila.<br>
                        8444851999 admpetscare@gmail.com
                    </p>
                </td>
                <td>

                    <p style="font-family: sans-serif; font-size: 10pt; text-transform: uppercase;">
                    </p>
                    <p class="head"></p>
                </td>
            </tr>
        </table>
    </div>

    <div style="border:1px solid #3459A4; margin-top:1%; border-left:none; border-right:none;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center; width: 20%">
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">AUTORIZACIÓN PARA
                            HOSPITALIZACIÓN</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">
                <p>Fecha: {{ $reception->entry_date }}</p>
            
            <tr>
                <td>
                    <p>El que suscribe: {{ $reception->pet->family->name }} </p>
                </td>
                <td>
                    <p> Tel: {{ $reception->pet->family->phone }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Domiclio: {{ $reception->pet->family->address }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>En caso de emergencia comunicarse con: {{ $reception->pet->family->contact_name }} </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Tel: {{ $reception->pet->family->contact_number }}</p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <p style="margin-top: 2%">Propietario de la mascota que a continuación se describe:</p>
        <table style="width: 100%; border-collapse:collapse; margin-top:1% margin-left:25%; margin-right:25%;">
            <tr>
                <td>
                    <p> Nombre:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{ $reception->pet->name }}
                    </p>
                <td>
                    <p> Especie</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{ $reception->pet->specie }}
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p> Raza:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{ $reception->pet->raza }}
                    </p>
                </td>
                <td>
                    <p> Edad:</p>
                </td>

                @php
                    $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                    $now = \Carbon\Carbon::now();

                    $years = $birthday->diffInYears($now);
                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                    $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                @endphp

                <td style="padding:  0.5px 10px;">
                    <p><span style="font-weight: normal">
                            {{ $years }} años, {{ $months }} meses
                        </span>
                    </p>
                </td>

            <tr>
                <td>
                    <p> Sexo:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{ $reception->pet->genre->name }}
                    </p>
                </td>
                <td>
                    <p> Peso:</p>
                </td>

                <td style="padding: 0.5px 10px;">
                    <p>
                        {{ $reception->pet->weight }}
                    </p>
                </td>
            </tr>
        </table>
    </div>


    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top:2%;">
            <tr>
                <td>
                    <ol>
                        <li> Por medio del presente otorgo mi autorización para que la mascota anteriormente descrita
                            sea
                            hospitalizada. Durante el tiempo que permanezca internada estoy consciente que tendrá que
                            ser
                            rasurada para canalizar, toma de muestras, rasurado para evitar lesiones en piel o estudio
                            ultrasonográfico, en una o distintas áreas del cuerpo, así como la administración de
                            medicamentos,
                            fluidos y pruebas adicionales (laboratorio e imagen) necesarias con el fin de preservar su
                            vida,
                            dándole la atención médica de acuerdo a su padecimiento. (Todos los estudios adicionales
                            serán
                            previamente informados a sus tutores antes de realizarlos).
                        </li>
                        <li>El costo del servicio de <b>hospitalización por día es de $ .</b> La hospitalización incluye
                            la
                            atención médica 24 hrs. Del día, insumos de hospital, alimento y medicamentos de cabecera,
                            importante mencionar que el alimento es de <b>MANTENIMIENTO, EN CASO DE QUE SU MASCOTA
                                REQUIERA UN ALIMENTO DE PRESCRIPCIÓN ESTE TENDRÁ UN COSTO EXTRA.</b> El cubículo de
                            tratamiento sera de acuerdo al tamaño de la mascota. Este precio no incluye alimentos de
                            prescripción, medicamentos especiales, estudios de laboratorio o imagen y procedimientos
                            médicos/quirúrgicos extras.
                        </li>
                        <li>
                            En caso de presentarse una urgencia, el medico tendrá la obligación de comunicarse con el
                            propietario, de no obtener respuesta se procederá a hacer lo que el paciente necesite para
                            mantener la vida de su mascota, dicho servicio extraordinario será considerado a entera
                            satisfacción del propietario. </li>
                        <li>

                            Así mismo acepto los riesgos que el uso de dichos procedimientos deriven y que son
                            dependientes de la respuesta biológica de cada organismo y que si por la gravedad de la
                            enfermedad falleciera no presentaré reclamación y pagaré todos los gastos generados por el
                            servicio durante el tiempo de
                            hospitalización.
                        </li>
                        <li>
                            Los pacientes no deberán ser ingresados con collar o correa al hospital. Se permitirá en
                            todo caso
                            camas, juguetes o accesorios a los que la mascota este acostumbrada.
                        </li>
                        <li>
                            Ninguna mascota podrá ingresar a hospital si llegara a presentar algún ectoparásito, en tal
                            caso se
                            deberán de seguir las indicaciones del médico.
                        </li>
                        <li>
                            El propietario será informado diariamente vía telefónica por el médico a cargo sobre la
                            evolución de su mascota. <b>Podrá visitarlo únicamente en un horario de 3:00pm a 5:00pm,
                                durante un tiempo no mayor a 15 min. Los días domingos y días festivos no hay
                                visitas.</b>
                        </li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">
            <ol start="8">

                <li>Los pacientes serán dados de alta en un horario de 10:00 am a 7:00 pm. El medico a cargo les
                    informara oportunamente el día y hora de salida de su mascota. Para tal efecto la cuenta
                    correspondiente deberá de estar liquidada en su totalidad. En caso de no ser recogida en la
                    fecha y hora indicada por el médico, pasará a nuestra área de pensión lo cual implicara el
                    costo de la misma, si después de tres días <b>el propietario no se reporta</b>, la mascota será
                    considerada abandonada y la empresa podrá disponer de ella como mejor le convenga, <b>sin
                        reclamo alguno.</b> </li>

                <li>
                    <b>Me comprometo a realizar los cuidados y dar la medicación que se me indiquen por escrito.</b>
                    Así como asistir en forma puntual a las revisiones. Los médicos y la empresa no se hacen
                    responsables de la evolución del paciente en caso de no seguir el tratamiento.

                </li>

                <li>
                    <b>No se aceptan niños menores de 16 años, o tendrá que estar un adulto presente, el uso de
                        cubrebocas en nuestras instalaciones es obligatorio.</b>
                </li>

                <li>
                    <b>Al momento de ingresar al paciente, se deberá de cubrir con un 50% del pago total de la
                        cuenta y
                        en caso de Ser URGENCIA o que sea horario de urgencias (8:00pm - 8:00am) se deberá cubrir el
                        costo TOTAL de la cuenta.</b>
                </li>

                <li>
                    <b>EXISTE LA POSIBILIDAD QUE SU MASCOTA REQUIERA DE MEDICAMENTOS ESPECIALIZADOS DE
                        "COSTO EXTRA" EN CASO DE SER NECESARIO SU MEDICO TRATANTE SE COMUNICARA CON UD
                        PARA SU PREVIA AUTORIZACIÓN.</b>
                </li>

                <li>
                    <b> LOS TUTORES LEGALES DEL PACIENTE QUE QUEDA BAJO HOSPITALIZACIÓN Y/O ESTÉTICA CANINA
                        CONTARAN CON UN PLAZO NO MAYOR DE 96 HORAS (4 DÍAS) PARA RESPONDER POR CUALQUIER
                        MEDIO DE COMUNICACIÓN AL LLAMADO DE LA CLINICA. PASADO ESTE TIEMPO SE TOMARÁ COMO
                        ABANDONO DE LA MASCOTA Y LA CLINICA TENDRA LA DISPOSICION LEGAL DE LA MASCOTA Y LA
                        TOMA DE DECISIONES. </b>
                </li>
            </ol>
        </table>
    </div>



    <div>
        <p style="margin-top: 2%;">
            <b>Firma y nombre:</b>
        </p>
        @if (isset($signature))
            <img src="{{ $signature }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @endif
    </div>


    <div class="row mx-0 t>
        <div class="col-6">
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100"></canvas>
        </div>
        <table>
            <tr>
                <td>
                    <div class="col-6">
                        <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button>
                    </div>
                </td>

                <td>
                    <form class="col-6">
                        <button class="btnEnviar btn btn-lmx">Enviar</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>

   
    <script src="{{ asset('js/jquery.min.js') }}" ></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="{{ asset('js/receptions/hospital_auth.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}"; 
    </script>
</body>

</html>
