<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{ $reception->id }}" id="reception_id"
                    placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="anamnesis" class="form-label">SUBJETIVO (ANAMNESIS)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea rows="2" name="anamnesis" class="form-control @error('anamnesis') is-invalid @enderror"
                           id="anamnesis">{{ old('anamnesis', $appointment?->anamnesis) }} </textarea>
                    </div>
                    {!! $errors->first('anamnesis', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="exam_details" class="form-label">OBJETIVO (DETALLES DEL EXAMEN)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea rows="2" name="exam_details" class="form-control @error('exam_details') is-invalid @enderror"
                            id="exam_details">{{ old('exam_details', $appointment?->exam_details) }} </textarea>
                    </div>
                    {!! $errors->first('exam_details', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2 mb20">
                    <label for="diagnosis" class="form-label">INTERPRETACIÓN (DIAGNÓSTICO PRESUNTIVO/FINAL)</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="diagnosis" rows="3"
                            class="form-control @error('diagnosis') is-invalid @enderror"
                            id="diagnosis">{{ old('diagnosis', $appointment?->diagnosis) }} </textarea>
                    </div>
                    {!! $errors->first('diagnosis', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2 mb20">
                    <label for="observations" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="observations" rows="3"
                            class="form-control @error('observations') is-invalid @enderror"
                             id="observations">{{ old('observations', $appointment?->observations) }}</textarea>
                    </div>
                    {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-2 mb20">
                    <label for="day_next_check" class="form-label">PRÓXIMO CONTROL</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fas fa-calendar text-primary"></i>
                        </span>
                        <input type="date" name="day_next_check"
                            class="form-control @error('day_next_check') is-invalid @enderror"
                            value="{{ old('day_next_check', $appointment?->day_next_check) }}" id="day_next_check"
                            placeholder="Day Next Check">
                    </div>
                    {!! $errors->first(
                        'day_next_check',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-2 mb20">
                    <label for="time_next_check" class="form-label">HORA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fa fa-clock text-primary"></i>
                        </span>
                        <input type="time" name="time_next_check"
                            class="form-control @error('time_next_check') is-invalid @enderror"
                            value="{{ old('time_next_check', $appointment?->time_next_check) }}" id="time_next_check"
                            placeholder="Time Next Check">
                    </div>
                    {!! $errors->first(
                        'time_next_check',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="reason_next_check_id" class="form-label">Tipo de proxima consulta</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="reason_next_check_id"
                            class="form-control @error('reason_next_check_id') is-invalid @enderror"
                            id="reason_next_check_id">
                            <option value=""> Selecciona el tipo</option>
                            @foreach ($reasons as $reason)
                                <option value="{{ $reason->id }}" name="reason_next_check_id"
                                    {{ old('reason_next_check_id', $appointment?->reason_next_check_id) == $reason->id ? 'selected' : '' }}>
                                    {{ $reason->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first(
                        'reason_next_check_id',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
        </div>

    </div>
    {{-- <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            Finalizar Consulta <i class="fas fa-file-medical fa-lg"></i></button>
    </div> --}}
</div>
