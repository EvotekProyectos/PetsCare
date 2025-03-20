<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    <style>
       
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
    <input type="hidden" value="{{ route('altaVoluntaria.pdf', $reception->id) }}" id="reception">
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
                     <p style="font-family: sans-serif; font-size: 10pt; text-transform: uppercase;"></p>
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">ALTA VOLUNTARIA</p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <p style="text-align:right;  margin-top:3%;">
            <b>Saltillo, Coahuila a {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</b></p>
    </div>
    
    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top:3%;">
            <tr>
                <td style="text-align: justify; line-height: 1.6;" >

                           Yo: 
                            @if (!isset($isPdf) || !$isPdf)
                                <input type="text" id="name_family" value="{{ $nameFamily ?? '' }}" class="form-control"  style="width: 600px;">
                            @else
                                <span>{{ $nameFamily ?? '' }}</span>
                            @endif
                            , declaro que por mi propia voluntad decido llevarme de ALTA VOLUNTARIA al paciente de Nombre: 
                            <b>{{ $reception->pet->name }}</b>, Especie: <b>{{ $reception->pet->specie }}</b>, 
                            Edad: 
                           
                            @php
                            $birthday = \Carbon\Carbon::parse($reception->pet->birthday);
                            $now = \Carbon\Carbon::now();
                            $years = $birthday->diffInYears($now);
                            $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                            $days = $birthday->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                            @endphp

                           {{ $years }} años, {{ $months }} meses, del cual no acepto el tratamiento médico indicado. 
                            Dejando exento de responsabilidades al Hospital Veterinario Pets Care y a los médicos encargados del caso.
                        
                            <h3 style="margin-top: 5%"> MOTIVO:</h3> 
            @if (!isset($isPdf) || !$isPdf)
                <input type="text" id="reason" value="{{ $reason ?? '' }}" class="form-control" style="width: 900px; height: 80px;">
            @else
                <span>{{ $reason ?? '' }}</span>
            @endif       
                </td>
            </tr>
        </table>
    </div>

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
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/hospitalizations/alta_voluntaria.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script> 
    
</body>

</html>
