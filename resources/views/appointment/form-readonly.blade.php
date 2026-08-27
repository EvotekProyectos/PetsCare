{{-- Clon de solo lectura de appointment/form.blade.php, para appointment/show.blade.php.
     Sin <form> envolvente (no hay submit en esta vista) y con `readonly` en
     vez de `disabled` para conservar el estilo visual normal de los campos. --}}
<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="anamnesis" class="form-label">SUBJETIVO (ANAMNESIS)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea rows="2" class="form-control" id="anamnesis" readonly>{{ $appointment?->anamnesis }} </textarea>
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
                        <textarea rows="2" class="form-control" id="exam_details" readonly>{{ $appointment?->exam_details }} </textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="diagnosis" class="form-label">INTERPRETACIÓN (DIAGNÓSTICO PRESUNTIVO/FINAL)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea rows="3" class="form-control" id="diagnosis" readonly>{{ $appointment?->diagnosis }} </textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="observations" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea rows="3" class="form-control" id="observations" readonly>{{ $appointment?->observations }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
