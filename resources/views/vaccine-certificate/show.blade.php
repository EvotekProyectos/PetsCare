@extends('layouts.app')

@section('template_title')
    CARTILLA VIRTUAL
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0"
                        style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <div class=" d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class=" text-uppercase" style="color: #0445A0">
                                    <span class="healthicons--syringe-vaccine"></span> CARTILLA VIRTUAL
                                    </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                        datos de tu mascota
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="text-center">
                                            <img src="{{ asset('img/pic.png') }}" alt="Foto Mascota" id="preview"
                                                class="img-fixed" style="width: 115px; height: 115px; object-fit: cover;  ">

                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="row">
                                            <div class="col-6">
                                                <p style="font-weight: bold">Nombre: <span style="font-weight: normal">
                                                        {{ $pet->name }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">Especie: <span style="font-weight: normal">
                                                        {{ $pet->specie }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">Raza: <span style="font-weight: normal">
                                                        {{ $pet->raza }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">Género: <span style="font-weight: normal">
                                                        {{ $pet->genre->name }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">E. Reproductivo: <span
                                                        style="font-weight: normal">
                                                        {{ $pet->reproductiveStatus->name }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                @php
                                                    $birthday = \Carbon\Carbon::parse($pet->birthday);
                                                    $now = \Carbon\Carbon::now();

                                                    $years = $birthday->diffInYears($now);
                                                    $months = $birthday->copy()->addYears($years)->diffInMonths($now);
                                                    $days = $birthday
                                                        ->copy()
                                                        ->addYears($years)
                                                        ->addMonths($months)
                                                        ->diffInDays($now);
                                                @endphp
                                                <p style="font-weight: bold">Edad: <span style="font-weight: normal">
                                                        {{ $years }} años, {{ $months }} meses, y
                                                        {{ $days }}
                                                        días
                                                    </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">Peso: <span style="font-weight: normal">
                                                        {{ $pet->weight }} </span></p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-weight: bold">Descripción física: <span
                                                        style="font-weight: normal">
                                                        {{ $pet->physic_descrip }} </span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                        datos de la veterinaria
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="text-center">
                                            <img src="{{ asset('img/logo-petscare.png') }}" alt="Foto Mascota"
                                                id="preview" class="img-fixed"
                                                style="width: 115px; height: 115px; object-fit: cover;  ">

                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="row">
                                            <div class="col-12">
                                                <p style="font-family: sans-serif; font-size: 10pt;">
                                                    Hospital Veterinario Pets Care <br>
                                                    SMV160511UY0 <br>
                                                    Blvd. Luis Donaldo Colosio 764, <br>
                                                    25205 Saltillo, Coahuila.<br>
                                                    8444851999 admpetscare@gmail.com</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-uppercase" style="color: #0445A0;">
                                <span class="fluent-mdl2--vaccination"></span> registro de vacunaciones
                            </h5>
                        </div>
                        <table class="table table-striped table-hover responsive w-100" id="vaccines">
                            <thead class="thead table-primary text-uppercase">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Vacuna</th>
                                    <th>Próxima Vacunación</th>
                                    <th>M.V.Z</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaccineCertificates as $register)
                                    @if ($register->service_id == 1)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE }} {{ $register->lab }} {{ $register->lote }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-uppercase" style="color: #0445A0;">
                                <span class="fluent-mdl2--bug-block"></span> programa de desparacitación externa
                            </h5>
                        </div>
                        <table class="table table-striped table-hover responsive w-100" id="vaccines">
                            <thead class="thead table-primary text-uppercase">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Próxima Aplicación</th>
                                    <th>M.V.Z</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaccineCertificates as $register)
                                    @if ($register->service_id == 3)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE }} {{ $register->dose }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-uppercase" style="color: #0445A0;">
                                <span class="fluent-mdl2--bug-block"></span> programa de desparacitación interna
                            </h5>
                        </div>
                        <table class="table table-striped table-hover responsive w-100" id="vaccines">
                            <thead class="thead table-primary text-uppercase">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Próxima Aplicación</th>
                                    <th>M.V.Z</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaccineCertificates as $register)
                                    @if ($register->service_id == 2)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE }} {{ $register->dose }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row d-flex justify-content-between align-items-center">
                            <a type="button" href="{{ route('certificate.imprimir', $pet?->id) }}" target="blank"
                                class="btn btn-lg text-primary">
                                <p>Imprimir Cartilla</p>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var ruta = "{{ asset('') }}";
        var imgDefault = "{{ asset('img/pet_pic.png') }}";
        var Pic_id = {{ $pet->picture_id ?? 'null' }};
        var Pic_route = "{{ $pet->file->route ?? '' }}";
        var Pet_Id = {{ $pet->id }};
        window.onload = function() {
            let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;

            if (Pic_id !== null) {
                $("#preview").attr("src", ruta + fileRoute);
            } else {
                $("#preview").attr("src", imgDefault);
            }

        }
    </script>
@endpush
