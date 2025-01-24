<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    
    <style type="text/css">
        @import url(https://themes.googleusercontent.com/fonts/css?kit=fOEonugfEEW2k3BWBOC73CXHfZMcH88HuPcErL5npACHpuVWaP-GHFPZzt35558q);

        .head {
            color: #3459A4;
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
        background-color: #0056b3; 
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.3); 
    }
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('format-responsiva.pdf', $pet->id) }}" id="pet">
    <div>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="align-items: center;">
                    @if($isPdf ?? false)
                        <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 90px">
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">RESPONSIVA DE ESTUDIOS DE GABINETE

                        </p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <p style="text-align:right;  margin-top:10px;"><b>Saltillo, Coahuila a {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</b></p>
    </div>
    
    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top:5px;">
            <tr>
                <td style="text-align: justify;">
                    Yo
                    @if (!isset($isPdf) || !$isPdf)
                        <input type="text" id="name" value="{{ $name ?? '' }}" class="form-control" style="width: 900px;">
                    @else
                        <span>{{ $name ?? '' }}</span>
                    @endif
                    declaro por mi propia voluntad que <b>NO</b> autorizo que se realice las pruebas que el médico me acaba 
                    de solicitar para mi mascota <b>{{ $pet->name }}</b>, de edad 
                    
                    @php
                        $birthday = \Carbon\Carbon::parse($pet->birthday);
                        $now = \Carbon\Carbon::now();
                        $years = $birthday->diffInYears($now);
                        $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                        $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                    @endphp
                    {{ $years }} años, {{ $months }} meses, raza {{ $pet->raza }},
                    entendiendo que, si no se realiza, no se podrá llegar a un diagnóstico definitivo y con el tratamiento más adecuado para mi mascota, 
                    dejando exento de responsabilidades al <b> HOSPITAL VETERINARIO PETS CARE, y a los médicos encargados del caso.</b>
                </td>
            </tr>
        </table>
    
        <div style="margin-top: 30px;">
            <p><b>MOTIVO</b></p> <p>
            @if (!isset($isPdf) || !$isPdf)
                <input type="text" id="reason" value="{{ $reason ?? '' }}" class="form-control" style="width: 900px; height: 80px;">
            @else
                <span>{{ $reason ?? '' }}</span>
            @endif
        </p>
        </div>
    </div>
                       

    {{-- <div  style="text-align: center;">
        <p style="margin-top: 80px;"><b>Firma y nombre:</b></p>

        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0" width="200" height="100" style="border-bottom: 2px solid #000000; "></canvas>

        @endif
    


    @if (!isset($isPdf) || !$isPdf)
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <td style="width: 10%;"><form> <button class="btnEnviar btn btn-lmx button">Aceptar</button> </form></td>
                <td style="width: 10%; text-align:left"> <button class="btnLimpiar btn btn-lmx button" data-target="canvas">Limpiar</button></td>
            </tr>
        </table>
    </div>
    @endif --}}

    <div style="text-align: center;">
        <p style="margin-top: 80px;"><b>NOMBRE Y FIRMA:</b></p>
    
        @if (isset($signatureDataUrl))
            <img src="{{ $signatureDataUrl }}" alt="Firma del propietario" style="width: 200px; height: 100px;">
        @else
            <canvas id="canvas" class="border border-dark p-0"  width="200" height="100" style="border-bottom: 2px solid #2b2b2b;"></canvas>
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
    <script src="{{ asset('js/formats/responsiva.js') }}" defer></script>
    <script>
        const PET_ID = "{{ $pet->id }}";
    </script> 

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
