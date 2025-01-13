
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
                        {{-- <img src="{{ public_path('img/cremation_draw.jpg') }}" alt="Logo" style="height: 90px"> --}}
                    </td>
                </tr>
            </table>
        </div>

        <div style="border:1px solid #3459A4; margin-top:1%; border-left:none; border-right:none;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <th style="text-align: center; width: 20%">
                            <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;"> DATOS DE PENSIÓN</p>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

        <div>
            <table style="width: 100%;">
                <tr>
                    <td>
                        {{-- <p style="font-size: 10pt; font-family:sans-serif;">Fecha entrada: {{ $cremation->reception->entry_date}} --}}
                        </p>
                    </td>
                
                    <td>
                        {{-- <p style="font-size: 10pt; font-family:sans-serif;">Recepcionista: {{ $cremation->reception->receptionist->name}} --}}
                        </p>
                    </td>
                   
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif;">
                            {{-- Médico responsable: {{ $cremation->vet->name ?? 'Sin especificar'}} --}}
                        </p>
                    </td>
                    
                </tr>
            </table>
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
                            {{-- {{ $cremation->pet->family->name }} --}}
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Teléfono:</p>
                    </td>
                    <td style="padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{-- {{ $cremation->pet->family->phone }} --}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Contacto emergencia:</p>

                    </td>
                    <td style="text-align: left; padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{-- {{ $cremation->pet->family->name }} --}}
                        </p>
                    </td>
                    <td>
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Teléfono emergencia:</p>
                    </td>
                    <td style="padding: 5px 10px;">
                        <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">
                            {{-- {{ $cremation->pet->family->phone }} --}}
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
                                {{-- {{ $cremation->pet->name }} --}}
                            </p>
                            <td>
                                <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Raza:</p>
                            </td>
                            <td style="padding: 5px 10px;">
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                    {{-- {{ $cremation->pet->raza }} --}}
                                </p>
                            </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Peso:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->pet->weight }} --}}
                            </p>
                        </td>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Sexo:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->pet->genre->name }} --}}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Fecha de nacimiento:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->pet->birthday}} --}}
                            </p>
                        </td>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">No. de Carnet:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->date_death ?? 'Sin especificar' }} --}}
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
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;"> Detalles del servicio
                                </p>
                            </td>
                            <td></td>
                         
                            {{-- <td style="padding: 5px 10px;">
                                 <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Folio para Pagar:
                                    {{ $reception->payment->folio_odv ?? 'Pendiente'}}
                                </p>
                            </td> --}}

                        </tr>
                    </table>
                </div>
            </div>
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Alimentación:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->serv->NOMBRE }} --}}
                            </p>
                          
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Objetos:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->type_urn ?? 'Sin especificar'}} --}}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Observaciones:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->cm->name ?? 'Sin especificar'}} --}}
                            </p>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Tipo de servicio:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{-- {{ $cremation->text_placa ?? 'Sin especificar'}} --}}
                            </p>
                            <td>
                                <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">Días:</p>
                            </td>
                            <td style="padding: 5px 10px; word-wrap: break-word; word-break: break-word; white-space: normal;">
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                    {{-- {{ $cremation->observations ?? 'Sin especificar'}} --}}
                                </p>
                            </td>
                            
                    </tr>
                    <tr>
                       
                            <td>
                                <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> No. cubículo:</p>
                            </td>
                            <td style="padding: 5px 10px;">
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                    {{-- ${{ $cremation->service?->PRECIO ?? 'Sin especificar'}} --}}
                                </p>
                            </td>
                            <td>
                                <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Fecha de salida:</p>
                            </td>
                            <td style="padding: 5px 10px;">
                                <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                    {{-- {{$cremation->date_finish ?? 'Sin especificar'}} --}}
                                </p>
                            </td>
                            
                    </tr>

                   

                    {{-- <tr>
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;"> Folio para Pagar:</p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                {{ $reception->payment->folio_odv ?? 'Pendiente'}}
                            </p>
                        </td>
                        
                </tr> --}}
                </div>

                {{-- <div
                style="border:1px solid #c5e8f7; margin-top: 40px; border-left:none; border-right:none; border-top:none; background-color: #eef7fc;">
                        
            </div> --}}
                       

                
            {{-- <div>
                <table style="width: 100%;">
                    <tr>
                       
                        <td>
                            <p style="font-size: 10pt; font-family:sans-serif; margin: 0;">  <strong> Folio para Pagar:</strong> </p>
                        </td>
                        <td style="padding: 5px 10px;">
                            <p style="font-family: sans-serif; font-size: 10pt; margin: 0;">
                                <strong>{{$reception->payment->folio_odv ?? 'Pendiente'}}</strong>
                            </p>
                        </td>
                 </tr>
                    
                </table>
            </div> --}}

        </div>


        <div class="footer">
            <p>Hospital Veterinario Pets Care</p>
        </div>
    </div>



</body>

</html>
