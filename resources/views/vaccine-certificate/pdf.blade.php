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

                        <p style="font-family: sans-serif; font-size: 10pt; text-transform: uppercase;">CARTILLA
                            VACUNACIÓN
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="border:1px solid #3459A4; margin-top:1%; border-left:none; border-right:none;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <th style="text-align: center; width: 20%">
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">CERTIFICADO DE
                                VACUNACIÓN</p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

        <div
            style="border:1px solid #c5e8f7; margin-top:0.1%; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <p style="font-family: sans-serif; font-size: 10pt; text-transform=uppercase; margin: 0;">
                                Datos de la familia</p>
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
                        <p style="font-size: 10pt; font-family: sans-serif; margin: 0;">
                            {{ $pet->family->name }}
                        </p>
                    </td>

                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Teléfono:</p>
                    </td>
                    <td style="padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{ $pet->family->phone }}
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
                                {{ $pet->name }}
                            </p>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Especie</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $pet->specie }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Raza:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $pet->raza }}
                            </p>
                        </td>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Peso:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $pet->weight }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Género:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $pet->gender_id }}
                            </p>
                        </td>
                        
                        
                    </tr>
                </table>
            </div>



            <div
                style="border:1px solid #c5e8f7; margin-top:20px; border-left:none; border-right:none;  background-color: #eef7fc;">
                <div>
                    <table style="width: 100%;">
                        <tr>
                            <th style="border-right:2px solid #c5e8f7; border-left:2px solid #c5e8f7;  ">
                                <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">
                                    <strong>Tipo</strong>
                                </p>
                            </th>

                            <th style="border-right:2px solid #c5e8f7;">
                                <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;"><strong>Fecha de
                                        aplicación</strong>
                                </p>
                            </th>
                            <th style="border-right:2px solid #c5e8f7;">
                                <p style="font-size: 10pt;font-family:sans-serif;"><strong>Nombre/ Producto</strong></p>
                            </th>
                            <th style="border-right:2px solid #c5e8f7;">
                                <p style="font-size: 10pt;font-family:sans-serif;"> <strong>Próxima aplicación</strong>
                                </p>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <table style="width: 100%; border:1px solid #c5e8f7;">
                    @foreach($certificate as $cert)
                    <tr>
                         <td>
                            <p class="body" style="text-align: center;">{{ $cert->service->name }}</p> 
                        </td>
                        <td>
                         <p class="body" style="text-align: center;">{{ $cert->application_date }}</p>
                        </td>
                        <td>
                            <p class="body" style="text-align: center;">{{ $cert->product }}</p>
                        </td>
                        <td>
                            <p class="body" style="text-align: center;">{{ $cert->next_application_date }}</p>
                        </td> 
                    </tr>
                    @endforeach

                </table>
            </div>

            <div>
                <div>
                    <table style="width: 100%; margin-top:20%;">
                        <tr>
                            <td>

                            </td>
                            <th style=" align-content:center; border-top: 1px solid #000000; ">
                                <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;"><strong>Firma
                                        MVZ</strong>
                                </p>
                            </th>
                            <td>

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
