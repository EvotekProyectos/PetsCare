<!DOCTYPE html>
<html lang="en">
   
<head>
    @routes
    <style type="text/css">
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

        .button {
            background-color: #3459A4; 
            color: #fff; 
            border: none;  
            border-radius: 5px; 
            padding: 10px 20px; 
            font-size: 16px; 
            cursor: pointer; 
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); 
            transition: all 0.3s ease; 
        }

        .button:hover {
            background-color: #3e6ac0; 
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.3); 
        }
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('hospital.pdf', $reception->id) }}" id="reception">
    <div>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="align-items: center;">
                    @if($isPdf ?? false)
                    <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px">
                    @else
                    <img src="{{ asset('img/logo-petscare.png') }}" style="height: 87px;">
                    @endif
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
        <p style="text-align:right;  margin-top:10px;">
            <b>Fecha:</b> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

     <div style="margin-left: 3%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td>
                    <p>El que suscribe: {{ $reception->pet->family->name }} </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p> Tel: {{ $reception->pet->family->phone }}</p>
                </td>
            </tr>

            <tr>
                <td>
                    <p>Domicilio: {{ $reception->pet->family->address }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>En caso de emergencia comunicarse con: {{ $reception->pet->family->contact_name }} </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Tel de emergencia: {{ $reception->pet->family->contact_number }}</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="margin-left: 3%;">
        <p style="width: 100%; text-align: justify; margin-top: 2%; margin-bottom: 1%;">
           <b> Propietario de la mascota que a continuación se describe:</p></b>
        <table style="width: 100%; border-collapse:collapse; margin-top:1% margin-left:25%; margin-right:25%;">
            <tr >
                <td>
                    <p>
                        Nombre: {{ $reception->pet->name }}
                    </p>
                </td>
                   
                <td >
                    <p> Especie: {{ $reception->pet->specie }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        Raza: {{ $reception->pet->raza }}
                    </p>
                </td>
                @php 
                $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                $now = \Carbon\Carbon::now();
                $years = $birthday->diffInYears($now);
                $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                @endphp
                <td>
                    <p> Edad: {{ $years }} años, {{ $months }} meses</p>
                </td>
            <tr>
                <td>
                    <p>
                        Sexo: {{ $reception->pet->genre->name }}
                    </p>
                </td>
                <td>
                    <p> Peso: {{ $reception->pet->weight }}</p>
                </td>
            </tr>
        </table>
    </div>


    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top:1%; text-align: justify;">
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
                        <li>El costo del servicio de <b>hospitalización por día es de $ 
                            @if (!isset($isPdf) || !$isPdf)
                            <input type="text" id="total"  value="{{ $total ?? '' }}" class="form-control" style="width: 300px;">
                            @else
                                 <span>{{ $total ?? '' }}</span>
                             @endif
                            .</b> La hospitalización incluye
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
                        <li style="page-break-before: always;">
                            El propietario será informado diariamente vía telefónica por el médico a cargo sobre la
                            evolución de su mascota. <b>Podrá visitarlo únicamente en un horario de 3:00pm a 5:00pm,
                                durante un tiempo no mayor a 15 min. Los días domingos y días festivos no hay
                                visitas.</b>
                        </li>
                  

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




    <div style="text-align: center;">
        <p style="margin-top: 30px;"><b>AUTORIZO:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100" style="border-bottom: 2px solid #2b2b2b;"></canvas>
        @endif
    
        <div style="margin-top: 20px; text-align: center;">
            @if (!isset($isPdf) || !$isPdf)
                <div style="display: flex; justify-content: center; gap: 20px;">

                    <div style="align-self: flex-start;">
                        <button class="btnLimpiar btn btn-lmx button" data-target="canvas" style="width: 100px;">Limpiar</button>
                    </div>
                    <div>
                        <form>
                            <button class="btnEnviar btn btn-lmx button" style="width: 100px;">Aceptar</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/receptions/hospital_auth.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
