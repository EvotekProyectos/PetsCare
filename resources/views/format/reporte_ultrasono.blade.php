<!DOCTYPE html>
<html lang="en">

<head>
    @routes
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
    {{-- <input type="hidden" value="{{ route('format-hospital.pdf', $pet->id) }}" id="pet"> --}}
    <div>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="align-items: center;">
                    {{-- <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px"> --}}

                </td>
                <td>
                    <p class="head">Hospital Veterinario Pets Care</p>
                    <p>REPORTE ULTRASONOGRAFICO</p>
                    <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">Report</p>
                    {{-- <p style="font-family: sans-serif; font-size: 10pt;">
                        SMV160511UY0 <br>
                        Blvd. Luis Donaldo Colosio 764, <br>
                        25205 Saltillo, Coahuila.<br>
                        8444851999 admpetscare@gmail.com
                    </p> --}}
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">Patient Information</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse;">

            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <tr>
                <td>
                    <p> Nombre:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{-- {{ $pet->name }} --}}
                    </p>
                <td>
                    <p> Especie</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{-- {{ $pet->specie }} --}}
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p> Raza:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{-- {{ $pet->raza }} --}}
                    </p>
                </td>
                <td>
                    <p> Edad:</p>
                </td>

                {{-- @php
                    $birthday = \Carbon\Carbon::parse($pet->pet->birthday);
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
                </td> --}}

            <tr>
                <td>
                    <p> Sexo:</p>
                </td>
                <td style="padding: 0.5px 10px;">
                    <p>
                        {{-- {{ $pet->genre->name }} --}}
                    </p>
                </td>
                <td>
                    <p> Peso:</p>
                </td>

                <td style="padding: 0.5px 10px;">
                    <p>
                        {{-- {{ $pet->weight }} --}}
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">Exam Information</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div style="border:1px solid #3459A4; margin-top:1%; border-left:none; border-right:none;">
        <div>
            <table style="width: 100%;">
                <tr>
                    <th style="text-align: center; width: 20%">
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">RESUMEN</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">Diagnósticos presuntivos con</p>

    <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">Recomendaciones</p>


    {{-- <div>
        <p style="margin-top: 2%;"><b>Firma y nombre:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100"></canvas>
        @endif
    </div>--}}

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
    <script src="{{ asset('js/formats/create.js') }}" defer></script>
    {{-- <script>
    //    const PET_ID = "{{ $pet->id }}";
    </script> --}}
        
    </script>
</body>

</html>
