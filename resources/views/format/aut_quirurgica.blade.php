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
    <input type="hidden" value="{{ route('surgery_authorization.pdf', $reception->id) }}" id="reception">
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">AUTORIZACIÓN DE PROCEDIMIENTOS 
                            ANESTÉSICOS Y QUIRÚRGICOS</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">
            <br>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>

            <tr>
                <td>
                    <p>El que suscribe: {{ $reception->family->name }} </p>
                </td>
                <td>
                    <p> Tel: {{ $reception->family->phone }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Domiclio: {{ $reception->family->address }}</p>
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
                        <li> Por medio de la presente autorizo la realización del procedimiento quirúrgico y/o anestésico
                            @if (!isset($isPdf) || !$isPdf)
                             <input type="text" id="procedure" value="{{ $procedure ?? '' }}" class="form-control">
                             @else
                                 <span>{{ $procedure ?? '' }}</span>
                             @endif
                            mismo que ha sido explicado por el médico, por lo que estoy consciente de los beneficios y riesgos 
                            que indica el mismo. El presupuesto de dicha intervención es de TOTAL $ 
                            @if (!isset($isPdf) || !$isPdf)
                            <input type="text" id="total"  value="{{ $total ?? '' }}" class="form-control">
                            @else
                                 <span>{{ $total ?? '' }}</span>
                             @endif
                             INCLUYE
                             @if (!isset($isPdf) || !$isPdf)
                             <input type="text" id="include" value="{{ $include ?? '' }}" class="form-control">
                             @else
                                 <span>{{ $include ?? '' }}</span>
                             @endif
                        </li>

                        <li>Si dentro del procedimiento quirúrgico se detecta alguna otra patología, seré notificado oportunamente 
                            para la autorización y modificación del costo de la cirugía. 
                        </li>
                        <li>
                            Si mi mascota ingresa el mismo día a procedimiento quirúrgico, <b>me hago responsable del ayuno de sólidos</b>
                            de 8 horas en adultos y de 6 horas en cachorros. </li>
                        <li>

                            Estoy consciente que el procedimiento quirúrgico y/o anestésico por sencillo o complicado que este sea,
                            precisa que mi mascota sea anestesiada, además de que reciba terapia pre-, trans-, y post- operatoria,
                            dependiendo de la idiosincrasia y naturaleza individual del paciente, razón por lo cual acepto los riesgos
                            por la aplicación de los medicamentos. Y que, si por alguna causa este falleciera, no presentaré reclamación 
                            posterior de ninguna índole.
                        </li>
                        <li>
                           Por políticas de la empresa el propietario tendrá que realizar un depósito del 50% del costo total (SOLO ELECTIVAS)
                           del procedimiento a realizar para llevarse a cabo, en CIRUGÍAS DE URGENCIA se cubrirá el COSTO TOTAL, el 100%.
                           El presupuesto cubre lo siguiente:
                            <ul>
                                <li>Un día de hospitalización postquirúrgico.</li>
                                <li>El procedimiento anestésico y quirúrgico.</li>
                                <li>Medicación que requiera durante el procedimiento.</li>
                                <li>Una revisión y retiro de sutras 10 días después o cuando el médico lo indique
                                    (En caso de requerir sedación, el costo será extra).</li>
                            </ul>
                        </li>
                        <li>
                           En caso de que el paciente presente alguna complicación y/o tenga que permanecer más tiempo hospitalizado los
                           gastos generados, estudios y hospitalización serán autorizados y pagados por el propietario ya que no están incluidos
                           en el presupuesto de la cirugía.
                        </li>
                        <li>
                            En el momento en que mi mascota me sea entregada deberá estar liquidada la cuenta total, 
                            me comprometo a realizar los cuidados postoperatorios que se me indiquen por escrito.
                             Así como asistir en forma puntual a las revisiones que se me indiquen después de la cirugía.
                        </li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">
            <ol start="8">

                <li>Los exámenes de laboratorio que indique el medico a cargo, son indispensables 
                    para efectuar el procedimiento anestésico y/o quirúrgico, mismos que tendrán un costo extra para el propietario. </li>

                <li>
                    Solo se dará información a una sola persona la cual firma la orden de autorización para evitar problemas de malentendidos.
                     La persona que autoriza deberá ser mayor de edad.
                </li>

                <li>
                    Hacemos de su conocimiento que, si su mascota no es recogida en la fecha indicada por el médico, pasará a nuestro servicio de pensión lo cual
                     implicara el costo extra de la misma, si después de 3 días no pasan a recoger al paciente sin previo aviso se considerara 
                    abandonado por lo que la empresa podrá disponer de ella a su conveniencia, sin reclamo posterior.
                </li>
            </ol>
        </table>
    </div>




    <div>
        <p style="margin-top: 2%;"><b>Firma y nombre:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100"></canvas>
        @endif
    </div>

    @if (!isset($isPdf) || !$isPdf)
        <div class="row mx-0">
            <div class="col-6">
                <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button>
            </div>
            <div class="col-6">
                <form>
                    <button class="btnEnviar btn btn-lmx">Aceptar</button>
                </form>
            </div>
        </div>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/formats/auth_surgery.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script> 

      <script>
        document.getElementById("current-date").innerText = new Date().toLocaleDateString();
    </script>
</body>

</html>
