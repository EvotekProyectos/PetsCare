<div class="row padding-1 p-1">
    <div class="col-md-12">
        {{-- $hideDiagnosis: usado únicamente por el modal de detalle de
             Consulta en el Historial de la mascota (ver
             AppointmentController::detailsModal()), donde el diagnóstico ya
             se muestra en Registro clínico justo arriba — es el mismo dato
             (la Fórmula Médica lo copia automáticamente del Appointment, ver
             PrescriptionController::store()). appointment/show.blade.php no
             pasa esta variable, así que sigue mostrándolo igual que hoy. --}}
        @unless($hideDiagnosis ?? false)
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="diagnosis_prescription" class="form-label">DIAGNOSTICO</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <img src="{{ asset('img/consulta.png') }}" alt="Foto Mascota" class="img-fixed"
                                    style="width: 20px; height: 20px; object-fit: cover; ">
                            </span>
                            <input type="text" class="form-control" value="{{ $prescription?->diagnosis }}"
                                id="diagnosis_prescription" readonly>
                        </div>
                    </div>
                </div>
            </div>
        @endunless

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-uppercase">
                MEDICAMENTOS
            </h5>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label for="medicine" class="form-label">NOMBRE , PRESENTACIÓN, CANTIDAD Y FORMA DE
                        ADMINISTRACIÓN
                    </label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <textarea class="form-control" id="medicine" rows="4" readonly>{{ $prescription?->medicine }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label for="prescription_observations" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <textarea class="form-control" id="prescription_observations" rows="4" readonly>{{ $prescription?->observations }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group mb-2 mb20">
                    <label for="day_next_check" class="form-label">PRÓXIMO CONTROL</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fas fa-calendar text-primary"></i>
                        </span>
                        <input type="date" class="form-control" value="{{ $appointment?->day_next_check }}"
                            id="day_next_check" readonly>
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
                        <input type="time" class="form-control" value="{{ $appointment?->time_next_check }}"
                            id="time_next_check" readonly>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="reason_next_check_id" class="form-label">TIPO DE PRÓXIMA CONSULTA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select class="form-control" id="reason_next_check_id" disabled>
                            <option value="">
                                {{ $appointment?->reason?->name ?? 'Sin definir' }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
