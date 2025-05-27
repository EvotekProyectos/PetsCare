@extends('layouts.app')

@section('template_title')
    Appointment
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="ic--twotone-pets"></span> CONSULTA
                            </h4>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS GENERALES
                            </h5>
                        </div>
                        <div class="row d-flex justify-content-left" style="margin-bottom: -11px;">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">FECHA DE INGRESO</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="lucide--calendar-clock"></span>
                                        </span>
                                        <input type="datetime-local" style="background-color: white" class="form-control"
                                            value="{{ $reception->entry_date }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">FAMILIA/PROPETARIO</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="fluent-mdl2--family text-primary"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $reception->family->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">MASCOTA</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="ic--twotone-pets"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $reception->pet->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">TIPO</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $reception->reason->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">M.V.Z</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $reception->vet->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">CONSULTORIO</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $reception->room->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                                DATOS ESPECÍFICOS
                            </h5>
                        </div>
                        <div class="row d-flex justify-content-left" style="margin-bottom: -11px;">
                            <div class="col-md-6">
                                <div class="form-group mb-2 mb20">
                                    <label for="anamnesis" class="form-label">SUBJETIVO (ANAMNESIS)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <textarea rows="2" style="background-color: white" class="form-control" disabled>{{ $appointment?->anamnesis }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2 mb20">
                                    <label for="exam_details" class="form-label">OBJETIVO (DETALLES DEL EXAMEN)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <textarea rows="2" style="background-color: white" class="form-control" disabled>{{ $appointment?->exam_details }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2 mb20">
                                    <label for="diagnosis" class="form-label">INTERPRETACIÓN (DIAGNÓSTICO
                                        PRESUNTIVO/FINAL)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <textarea name="diagnosis" rows="3" style="background-color: white" class="form-control" disabled
                                            id="diagnosis">{{ $appointment?->diagnosis }} </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-2 mb20">
                                    <label for="observations" class="form-label">OBSERVACIONES</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <textarea name="observations" rows="3" style="background-color: white" class="form-control" disabled>{{ $appointment?->observations }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="day_next_check" class="form-label">PRÓXIMO CONTROL</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <i class="fas fa-calendar text-primary"></i>
                                        </span>
                                        <input type="date" style="background-color: white" class="form-control "
                                            disabled value="{{ $appointment?->day_next_check }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="time_next_check" class="form-label">HORA</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <i class="fa fa-clock text-primary"></i>
                                        </span>
                                        <input type="time" style="background-color: white" disabled
                                            class="form-control" value="{{ $appointment?->time_next_check }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="name" class="form-label">TIPO PROXIMO CONTROL</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="vaadin--lines-list"></span>
                                        </span>
                                        <input type="text" style="background-color: white" class="form-control"
                                            value="{{ $appointment?->reason?->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($prescription)
                        <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 id="card_title" class="text-primary text-uppercase">
                                    <span class="material-symbols--prescriptions-outline "></span> FÓRMULA MÉDICA
                                </h4>
                            </div>
                            <div class="row d-flex justify-content-left" style="margin-bottom: -11px;">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="name" class="form-label">FECHA</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                                <span class="lucide--calendar-clock "></span>
                                            </span>
                                            <input type="datetime-local" name="date" disabled class="form-control"
                                                style="background-color: white" value="{{ $prescription?->date }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="name" class="form-label">DIAGNOSTICO</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                                <img src="{{ asset('img/consulta.png') }}" alt="Foto Mascota"
                                                    id="preview" class="img-fixed"
                                                    style="width: 20px; height: 20px; object-fit: cover; ">
                                            </span>
                                            <input type="text" name="diagnosis" disabled class="form-control"
                                                style="background-color: white" value="{{ $prescription?->diagnosis }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class=" text-uppercase">
                                    MEDICAMENTOS
                                </h5>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="name" class="form-label">NOMBRE , PRESENTACIÓN, CANTIDAD Y FORMA
                                            DE
                                            ADMINISTRACIÓN</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                                <span class="icon-park-twotone--medicine-bottle-one"></span>
                                            </span>
                                            <textarea name="medicine" class="form-control" style="background-color: white" disabled rows="4">{{ $prescription?->medicine }}</textarea>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="name" class="form-label">OBSERVACIONES</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                                <span class="icon-park-twotone--medicine-bottle-one"></span>
                                            </span>
                                            <textarea class="form-control" style="background-color: white" disabled rows="4">{{ $prescription?->observations }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-between align-items-center">
                                <a type="button" href="{{ route('prescription.imprimir', $prescription?->id) }}"
                                    target="blank" class="btn btn-lg text-primary">
                                    <p>Imprimir Fórmula Médica</p>
                                </a>

                            </div>
                        </div>
                    @endif

                    @if ($vaccineCertificates->isNotEmpty())
                        <div class="card-body ">
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
                                    @foreach ($vaccineCertificates->where('service_id', 1) as $register)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                {{ $register->lab }}
                                                {{ $register->lote }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
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
                                    @foreach ($vaccineCertificates->where('service_id', 3) as $register)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                {{ $register->dose }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
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
                                    @foreach ($vaccineCertificates->where('service_id', 2) as $register)
                                        <tr>
                                            <td>{{ $register->application_date }}</td>
                                            <td>{{ $register->microsip->NOMBRE ?? $register->product }}
                                                {{ $register->dose }}</td>
                                            <td>{{ $register->next_application_date }}</td>
                                            <td>{{ $register->vet->name }}</td>
                                            <td>{{ $register->observations }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
