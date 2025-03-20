<!DOCTYPE html>
<html lang="en">

<head>
    @routes
    
    <style>
        .head {
            color: #3459A4;
            font-weight: 700;
            font-size: 8pt;
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

        .data {
            font-family: sans-serif;
            font-size: 8pt;
            color: #000000;
            font-weight: normal;
            margin: 0;
        }

        .fillable {
            font-family: sans-serif;
            font-size: 8pt;
            color: #b3aeae;
            font-weight: normal;
            margin: 0;
        }
     
    </style>
</head>

<body>
    
    <div>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="align-items: center;">
                    <img src="{{ public_path('img/logo-petscare.png') }}" alt="Logo" style="height: 50px">
                </td>
                
                <td>
                    <p class="head">Hospital Veterinario Pets Care</p>
                    <p style="font-family: sans-serif; font-size: 7pt;">
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
                        <p style="font-size: 10pt; font-weight: bold;font-family:sans-serif;">SERVICIO A DOMICILIO PARA GROOMING
                        </p>
                    </th>
                </tr>
            </table>
        </div>
    </div>

    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top: 3%;">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Recolección:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ (new DateTime($reception->entry_date))->format('d-m-Y h:i') }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Entrega:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ (new DateTime($reception->exit_date))->format('d-m-Y h:i') }} </p>
                </th>
            </tr>
        </table>
    </div>

    <div style="border: 1px solid #3459A4; margin-top: 1%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Familia:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Tel:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->phone }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Dirección:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->family->address }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Referencias:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->grooming->delivery_references }} </p>
                </th>
            </tr>
        </table>

    </div>

    <div style="border: 1px solid #3459A4; margin-top: 1%; width: 100%">
        <table style="width: 100%; border-collapse: collapse; ">
            
                <th style="width: 15%; text-align: left;">
                    <p class="data">Mascota:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->name }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Especie:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->specie }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Raza:</p>
                </th>
                <th style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->raza }} </p>
                </th>
                <th style="width: 20%; text-align: left;">
                    <p class="data">Sexo:</p>
                </th>
                <th style="width: 30%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->genre->name }} </p>
                </th>
            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data"> Clasificación:</p>
                </th>
                <th colspan="3" style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->petClassification?->name }} </p>
                </th>

            </tr>
            <tr>
                <th style="width: 15%; text-align: left;">
                    <p class="data">Descripción:</p>
                </th>
                <th colspan="3" style="width: 35%; text-align: left;">
                    <p class="fillable"> {{ $reception->pet->physic_descrip }} </p>
                </th>

            </tr>
        </table>

    </div>

</body>

</html>
