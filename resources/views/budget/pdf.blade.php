<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    <style>
        .data {
            font-family: sans-serif;
            font-size: 11pt;
            color: #4faacf;
            font-weight: normal;
            margin: 0;
        }

        .fillable {
            font-family: sans-serif;
            font-size: 11pt;
            color: #b3aeae;
            font-weight: normal;
            margin: 0;
        }

        .aclarations {
            font-family: sans-serif;
            font-size: 10pt;
            color: #64b3d3;
            font-weight: lighter margin: 0;
            margin: 5px 0
        }

        .tableup {
            font-family: sans-serif;
            font-size: 12pt;
            color: #4faacf;
            font-weight: semibold;
        }

        .titles {
            font-family: sans-serif;
            font-size: 15pt;
            color: #4faacf;
            font-weight: bold;
        }

        .alarm {
            font-family: sans-serif;
            font-style: italic;
            font-size: 10pt;
            color: #4faacf;
            font-weight: semibold;
        }

        .total {
            font-family: sans-serif;
            font-style: italic;
            font-size: 12pt;
            color: #818080;
            font-weight: bold;
            margin: 0;
        }

        .table-bordered {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1%;
            border: 1px solid #4faacf;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #4faacf;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div>
        <table style="width: 100%; ">
            <tr>
                <th style="width: 20%"> </th>
                <th style="width: 60%"></th>
                <th style="width: 20%"></th>
            </tr>
            <tr>
                <td style="align-items: center">
                    <img src="{{ public_path('img/logo-petscare.png') }}" style=" height: 87px;">
                </td>
                <td style="text-align: center;">
                    <p class="titles">PRESUPUESTO QUIRÚRGICO <br>
                        <span style="font-family: sans-serif; font-weight: bold; font-size: 9pt; color: #4faacf;">PARA
                            NOSOTROS LA SALUD DE TU MASCOTA ES NUESTRA PRIORIDAD </span>
                    </p>
                </td>
                <td style="text-align: center; border: 1px solid #2ca0ce;">
                    <p style="color: #4faacf; font-family: sans-serif;">FOLIO</p>
                    <hr style="color: #2ca0ce; width: 100%; margin-bottom: -7%; margin-top: -7%;">
                    <p
                        style="font-family: 'Times New Roman', Times, serif; font-weight: lighter; font-size: 12pt; color: #776d6d;">
                        {{ str_pad($budget->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
    {{-- <div style="margin-top:-2%;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center; width: 100%; color: #4faacf;">
                        <p class="titles">ESTETICA</p>
                    </th>
                </tr>
            </table>
        </div>
    </div> --}}

    <div style="border: 1px solid #2ca0ce; margin-top: .5%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Familia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->family->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Domicilio:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->family->address }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Correo:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->family->email }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Telefono:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->family->phone }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Mascota:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Especie:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->specie }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Raza:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->raza }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Sexo:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->genre->name }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Descripción:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable">{{ $budget->pet->physic_descrip }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Peso:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $budget->pet->weight }} </p>
                </th>

            </tr>
        </table>

    </div>

    <div>
        <p class="titles">Servicios Presupuestados en la fecha {{ $budget->date }}</p>
        <table class="table-bordered">
            @php
                $filteredSERV = $details->whereNotNull('service_id');
            @endphp
            @if ($filteredSERV->isNotEmpty())
                <thead>
                    <tr>
                        <th class="tableup" style="text-align: center">Servicio Médico</th>
                        <th class="tableup" style="text-align: center">Notas</th>
                        <th class="tableup" style="text-align: center">Precio</th>
                    </tr>
                </thead>
                @foreach ($details->whereNotNull('service_id') as $detail)
                    <tbody>
                        <tr>
                            <td class="fillable">
                                {{ $detail->serv->NOMBRE ?? 'N/A' }}
                            </td>
                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                            <td class="fillable" style="text-align: right">
                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                @endforeach
            @endif
            @php
                $filteredLAB = $details->whereNotNull('lab_id');
            @endphp
            @if ($filteredLAB->isNotEmpty())
                <thead>
                    <tr>
                        <th class="tableup" style="text-align: center">Laboratorio</th>
                        <th class="tableup" style="text-align: center">Notas</th>
                        <th class="tableup" style="text-align: center">Precio</th>
                    </tr>
                </thead>
                @foreach ($details->whereNotNull('lab_id') as $detail)
                    <tbody>
                        <tr>
                            <td class="fillable">
                                {{ $detail->lab->NOMBRE ?? 'N/A' }}
                            </td>
                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                            <td class="fillable" style="text-align: right">
                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                        </tr>

                    </tbody>
                @endforeach
            @endif
            @php
                $filteredIMG = $details->whereNotNull('img_id');
            @endphp
            @if ($filteredIMG->isNotEmpty())
                <thead>
                    <tr>
                        <th class="tableup" style="text-align: center">Imageneología</th>
                        <th class="tableup" style="text-align: center">Notas</th>
                        <th class="tableup" style="text-align: center">Precio</th>
                    </tr>
                </thead>
                @foreach ($details->whereNotNull('img_id') as $detail)
                    <tbody>
                        <tr>
                            <td class="fillable">
                                {{ $detail->img->NOMBRE ?? 'N/A' }}
                            </td>
                            <td class="fillable">{{ $detail->notes ?? 'Sin notas' }}</td>
                            <td class="fillable" style="text-align: right">
                                ${{ number_format($detail->price ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                @endforeach
            @endif
            <tfoot>
                <tr>
                    <td style="text-align: right" colspan="2">
                        <p class="total">GRAN TOTAL:</p>
                    </td>
                    <td style="text-align: left">
                        <p class="total">${{ number_format($budget->total) }}</p>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

    <div>
        <p class="alarm">EL DÍA DE CIRUGIA INDICADO DEBERA TARER SU HOJA DE PRESUPUESTO O PERDERA EL COSTO INDICADO DE
            SU CIRUGIA O LA CITA DE CIRUGIA DE SU MASCOTA
        </p>
    </div>
    <div>
        <p class="alarm">Si tienes dudas, pregúntale al médico a cargo de tu mascota, con gusto las resolverá
            o puedes marcar a los teléfonos de hospital
        </p>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%;">
                    <p class="alarm" style="margin-top: 2%;"><b>Firma Médico:</b></p>
                </td>
                <td style="width: 50%;">
                    <p class="alarm" style="margin-top: 2%;"><b>Firma Propietario:</b></p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p class="alarm" style="margin-top: 2%;">
                        @if (isset($signatureDataUrl))
                            <img src="{{ $signatureDataUrl }}" alt="Firma del medico"
                                style="width: 200px; height: 100px;">
                        @else
                            <canvas id="canvas" class="border border-dark p-0" style="border: 2px solid #4faacf;"
                                width="300" height="100"></canvas>
                        @endif
                </td>
                <td style="width: 50%;">
                    @if (isset($signatureDataUrl2))
                        <img src="{{ $signatureDataUrl2 }}" alt="Firma del propietario"
                            style="width: 200px; height: 100px;">
                    @else
                        <canvas id="canvas2" class="border border-dark p-0" style="border: 2px solid #4faacf;"
                            width="300" height="100"></canvas>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    @if (!isset($isPdf) || !$isPdf)
                        <button class="btnLimpiar btn btn-lmx" data-target="canvas">Limpiar</button>
                    @endif
                </td>
                <td style="width: 50%;">
                    @if (!isset($isPdf) || !$isPdf)
                        <button class="btnLimpiar btn btn-lmx" data-target="canvas2">Limpiar</button>
                    @endif
                </td>
            </tr>
        </table>
        @if (!isset($isPdf) || !$isPdf)
            <div>
                <form> <button class="btnEnviar btn btn-lmx">Aceptar</button> </form>
            </div>
        @endif
    </div>
    {{-- <div>
        <p class="alarm" style="margin-top: 2%;"><b>Firmas:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" style="background-color: #4faacf" width="300"
                height="100"></canvas>
        @endif
    </div>

    @if (!isset($isPdf) || !$isPdf)
        <div>
            <table style="width: 100%; border-collapse: collapse; ">
                <tr>
                    <td style="width: 10%;">
                        <form> <button class="btnEnviar btn btn-lmx">Aceptar</button> </form>
                    </td>
                    <td style="width: 10%; text-align:left"> <button class="btnLimpiar btn btn-lmx"
                            data-target="canvas">Limpiar</button></td>
                </tr>
            </table>
        </div>
    @endif --}}
    <div style="page-break-before: always;"></div>
    <div>
        <p class="titles" style="text-align: center">RECOMENDACIONES PARA CIRUGIA</p>
        <p class="alarm">Si tu mascota va a entrar a cirugia
            es muy importante que tomes en cuenta los siguientes puntos
        </p>
        <table style="width: 100%; border-collapse: collapse; ">
            <tr style="margin: 0; ">
                <td>
                    <p class="aclarations">
                        El presupuesto de la cirugía y estudios pre anestésicos (de acuerdo con la edad) tienen una
                        <span style="font-weight: bold"> vigencia de 10 días.</span>
                        En caso de que pase la fecha señalada, nuevamente se tendrá que valorar
                        clínicamente para cotizar la cirugía.
                    </p>
                </td>
            </tr>
            <tr style="margin: 0; ">
                <td>
                    <p class="aclarations">
                        El costo de la cirugia solo incluye medicamentos administrados durante el procedimiento
                        quirúrgico/anestésico. <span style="font-weight: bold"> Los medicamentos recetados y accesorios
                            para llevar el tratamiento a casa tendrán un COSTO EXTRA. </span> <br>
                    </p>
                </td>
            </tr>
            <tr style="margin: 0; ">
                <td style="text-align: justify;padding: 5px 0; ">
                    <p class="aclarations">
                        Las cirugías se <span style="font-weight: bold"> PROGRAMAN </span> mínimo con
                        <span style="font-weight: bold"> TRES DÍAS de anticipación. </span> SI <span
                            style="font-weight: bold">NO </span> acudes el día y hora
                        indicado, se <span style="font-weight: bold"> REAGENDARÁ </span> de la disponibilidad del
                        quirófano. <br>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        El día de la cirugia, el paciente deberá acudir en <span style="font-weight: bold"> AYUNO
                        </span> mínimo de
                        <span style="font-weight: bold">8 horas </span> en <span style="font-weight: bold">ADULTOS
                        </span> de
                        <span style="font-weight: bold"> 4 en CACHORROS,</span> retirando el agua 4 horas antes.
                        <span style="font-weight: bold">No aplica para animales exóticos. </span> <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        Los pacientes se<span style="font-weight: bold"> ingresan </span> al hospital en un horario de
                        <span style="font-weight: bold"> 8:00 am a 10:00 am. </span> O bien respetando el
                        horario que le medico indique, leyendo y llenando debidamente la hoja de autorización de
                        cirugía, si tienes dudas pregunta al médico a cargo. <br>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        En <span style="font-weight: bold"> CIRUGÍAS AMBULATORIAS </span> el paciente
                        <span style="font-weight: bold">se da de ALTA antes de las 6:00 pm, </span> si permanece
                        después de ese horario, se generarán cargos extras. <br>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        Se puede bañar al paciente un día antes de la cirugía, <span style="font-weight: bold"> no ese
                            mismo día </span>. Después de la cirugía el médico informará en cuanto tiempo se puede
                        bañar.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        En caso de requerir una esterilización para hembra, se podrá hacer solamente si no está en su
                        periodo de celo,
                        en caso contrario tendrá que dejar pasar 1 mes después de dicho periodo para realizar el
                        procedimiento de cirugía (felinos no aplica).
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        <span style="font-weight: bold">IMPORTANTE. </span> El orden de los procedimientos quirúrgicos
                        <span style="font-weight: bold"> DEPENDERA </span> la <span
                            style="font-weight: bold">COMPLEJIDAD Y GRAVEDAD </span>
                        del mismo procedimiento, por ende, no hay un horario especifico
                        de ingreso al quirófano, eso será en razón del criterio del JEFE del área. En este mismo
                        sentido, cabe aclarar que los procedimientos ELECTIVOS (Profilaxis dental,
                        Castración, OSH/OVH) No son procedimientos de urgencia, por lo cual son los últimos en
                        realizarse.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        En el caso de CESAREAS, la recuperación del útero se llevará siempre y cuando el útero este
                        viable,
                        y no comprometa la vida del paciente, en caso contrario el cirujano tiene la autorización para
                        retirar el órgano. Ante todo, la vida del paciente,
                        esta sobre toda posibilidad de reproducción.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations"> <span style="font-weight: bold; text-decoration: underline;">
                            INDICACIONES QUE DEBES TENER EN CUENTA DESPUES DE LA CIRUGIA </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: justify; ">
                    <p class="aclarations">
                        Ten en cuenta que <span style="font-weight: bold"> después de la cirugía tu mascota necesitará
                            ciertos cuidados, </span> los más
                        importantes son: <br>

                        • No se debe lamer la herida, para ello debes colocar un collar isabelino las 24 horas del dia
                        (por las noches o cuando se quedan solos es cuando más aprovechan para lamerse). Si se lamen
                        corren el riesgo de irritarse la piel, quitarse los puntos e infectarse, por lo que se tendrá
                        que volver a anestesiar para recolocarios, generando costos extras. <br>

                        • Los medicamentos recetados dependen del tipo de cirugía que se realizó y deberás cumplir con
                        la administración de ellos, ya que evitarás dolor e infección en la herida. <br>

                        • Se debe realizar mínimo una limpieza de la herida al dia, con antiséptico como MICRODACYN O
                        VETERIBAC y gasas, para evitar acumulación de pelos, polvo y costras. En caso de no necesitar
                        limpieza el medico lo indicara al momento de la entrega del paciente. <br>

                        • Debe permanecer en un lugar limpio y seco, evitando salir a la caile, minimo hasta el día de
                        su revisión. <br>

                        • En caso de que su cirugía haya sido ortopédica o una cirugía mayor las indicaciones
                        especificas
                        vendrán en su receta, deberán seguirlas como el medico lo solicita <br>

                        • ASISTIR PUNTUALMENTE A SUS SEGUIMIENTOS POST QUIRURGICOS.
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/budgets/pdf.js') }}" defer></script>
    <script>
        const BUDGET_ID = "{{ $budget->id }}";
        const RECEPTION_ID = "{{ $budget->reception_id }}";
    </script>
</body>


</html>
