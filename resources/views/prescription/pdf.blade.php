<html>

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
        }

        th,
        td {
            width: 100px;
        }
    </style>
</head>

<body>
    <div>
        <div>
            <table style="width: 100%; text-align: center;">
                <tr>
                    <td style="align-items: center;">
                        <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px">

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

                        <p style="font-family: sans-serif; font-size: 10pt; text-transform: uppercase;">Fórmula médica
                        </p>
                        <p class="head">No. {{ $prescription->id }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="border:1px solid #3459A4; margin-top:1%; border-left:none; border-right:none;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <th style="text-align: center; width: 20%">
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;"> Reservado al
                                tratamiento de animales</p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

        <div
            style="border:1px solid #c5e8f7; margin-top:0%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <p style="font-family: sans-serif; font-size: 10pt; text-transform=uppercase; margin: 0;">
                                Datos de la
                                familia</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Nombre:</p>

                    </td>
                    <td style="text-align: left; padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{ $prescription->pet->family->name }}
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Teléfono:</p>
                    </td>
                    <td style="padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{ $prescription->pet->family->phone }}
                        </p>
                    </td>
                </tr>
            </table>

            <div
                style="border:1px solid #c5e8f7; margin-top:2%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                <div>
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;"> Datos de la mascota</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Nombre:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $prescription->pet->name }}
                            </p>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Especie</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $prescription->pet->specie }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Raza:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $prescription->pet->raza }}
                            </p>
                        </td>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Peso:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $prescription->pet->weight }}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div
                style="border:1px solid #c5e8f7; margin-top: 40px; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                <div>
                    <table style="width: 100%; ">
                        <tr>
                            <td>
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;"> Detalles de la receta
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div style="border: 1px solid #3459A4;  border-left:none; border-right:none; border-top:none;  margin: 0;">
                <div>
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;"><strong>Fecha de
                                        registro:</strong>
                                </p>
                            </td>
                            <td>
                                <p style="font-size: 10pt;font-family:sans-serif;">{{ $prescription->date }}</p>
                            </td>
                            <td>
                                <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">
                                    <strong>Registra:</strong>
                                </p>
                            </td>
                            <td>
                                <p style="font-size: 10pt;font-family:sans-serif;">{{ $prescription->vet->name }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <p class="head">Diagnóstico:</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="body">{{ $prescription->diagnosis }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="head">Medicamentos:</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="body">{{ $prescription->medicine }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="head">Observaciones:</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="body" style="padding=50px;">{{ $prescription->observations }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div
                style="border:1px solid #c5e8f7; margin-top: 60px; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                <div>
                    <table style="width: 100%; ">
                        <tr>
                            <th>
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;"> Próximo control
                                </p>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>


            <div>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">
                                <strong>Fecha:</strong>
                            </p>
                        </td>

                        <td>
                            <p style="font-size: 10pt;font-family:sans-serif;">{{ $next?->day_next_check }}
                            </p>
                        </td>

                        <td>
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">
                                <strong>Hora:</strong>
                            </p>
                        </td>

                        <td>
                            <p style="font-size: 10pt;font-family:sans-serif;">
                                {{ $next?->time_next_check }}</p>
                        </td>
                        
                        <td>
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">
                                <strong>Motivo:</strong>
                            </p>
                        </td>

                        <td>
                            <p style="font-size: 10pt;font-family:sans-serif;">
                                {{ $next?->reason->name }}</p>
                        </td>
                    </tr>
                </table>
            </div>

        </div>


        <div class="footer">
            <p>Hospital Veterinario Pets Care - Todos los derechos reservados</p>
        </div>
    </div>



</body>

</html>
