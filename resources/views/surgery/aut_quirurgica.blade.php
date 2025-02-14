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
        background-color: #63aaf7; 
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.3); 
    }
    </style>
</head>

<body>
    <input type="hidden" value="{{ route('surgery_authorization.pdf', $reception->id) }}" id="reception">
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
                        <p style="font-size: 15pt; font-weight: bold;font-family:sans-serif;">AUTORIZACIÓN DE PROCEDIMIENTOS 
                            ANESTÉSICOS Y QUIRÚRGICOS</p>
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
                <td colspan="100%" style="text-align: left;">
                    <p>El que suscribe: {{ $reception->pet->family->name }} </p>
                </td>
                
            </tr>
            <tr>
                <td colspan="100%" style="text-align: left;">
                    <p> Tel: {{ $reception->pet->family->phone }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: left;">
                    <p>Domiclio: {{ $reception->pet->family->address }}</p>
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
        <table style="width: 100%; border-collapse: collapse; margin-top:2%;">
            <tr>
                <td>
                    <ol>
                        <li> Por medio de la presente autorizo la realización del procedimiento quirúrgico y/o anestésico:
                            @if (!isset($isPdf) || !$isPdf)
                                <select id="procedure" class="form-control select2" name="procedure">
                                    <option value="">Selecciona el procedimiento a realizar</option>
                                    @foreach ($products as $product)
                                       <option value="{{ $product->NOMBRE }}" 
                                                {{ old('procedure', $procedure ?? '') == $product->ARTICULO_ID ? 'selected' : '' }}>
                                            {{ $product->NOMBRE }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                            <span><strong>{{ $procedure ?? '' }}</strong></span>
                             @endif
                             mismo que ha sido explicado por el médico, por lo que estoy consciente de los beneficios y riesgos 
                             que indica el mismo. El presupuesto de dicha intervención es de <b>TOTAL $ 
                             @if (!isset($isPdf) || !$isPdf)
                             <input type="text" id="total"  value="{{ $total ?? '' }}" class="form-control" style="width: 300px;">
                             @else
                                  <span>{{ $total ?? '' }}</span>
                              @endif
                             </b>
                              <br> <b>INCLUYE</b>
                              @if (!isset($isPdf) || !$isPdf)
                              <input type="text" id="include" value="{{ $include ?? '' }}" class="form-control" style="width: 900px; height: 50px;">
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




    <div style="text-align: center;">
        <p style="margin-top: 80px;"><b>NOMBRE Y FIRMA:</b></p>
    
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
    <script src="{{ asset('js/formats/auth_surgery.js') }}" defer></script>
    <script>
        const RECEPTION_ID = "{{ $reception->id }}";
    </script> 
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
