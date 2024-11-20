<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{$reception->id}}" id="reception_id_followup"
                    placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="time" class="form-label">Hora</label>
            <div class="input-group mb-3">
                <span class="input-group-text text-primary bg-primary-subtle" id="basic-addon1">
                    <i class="fa fa-clock"></i></span>
                <input type="time" name="time" class="form-control @error('time') is-invalid @enderror"
                    value="{{ old('time', $followUp?->time) }}" id="time" placeholder="Hora">
            </div>
            {!! $errors->first('time', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="details" class="form-label">Detalles</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <textarea name="details" class="form-control @error('details') is-invalid @enderror" rows="3" id="details">{{ old('details', $followUp?->details) }}</textarea>
            </div>
            {!! $errors->first('details', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="temperature" class="form-label">Temperatura</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="temperature" class="form-control @error('temperature') is-invalid @enderror"
                    value="{{ old('temperature', $followUp?->temperature) }}" id="temperature"
                    placeholder="Temperatura">
            </div>
            {!! $errors->first('temperature', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="row">
            <div class="d-flex justify-content-between align-items-center">
                <h6 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                    PRESIÓN
                </h6>
            </div>
            <div class="col form-group mb-2 mb20">
                <label for="systolic" class="form-label">Sistólica</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span></span>
                    <input type="text" name="systolic" class="form-control @error('systolic') is-invalid @enderror"
                        value="{{ old('systolic', $followUp?->systolic) }}" id="systolic" placeholder="sistólica">
                </div>
                {!! $errors->first('systolic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col form-group mb-2 mb20">
                <label for="diastolic" class="form-label">Diastólica</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span></span>
                    <input type="text" name="diastolic" class="form-control @error('diastolic') is-invalid @enderror"
                        value="{{ old('diastolic', $followUp?->diastolic) }}" id="diastolic" placeholder="diastólica">
                </div>
                {!! $errors->first('diastolic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col form-group mb-2 mb20">
                <label for="average" class="form-label">Media</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span></span>
                    <input type="text" name="average" class="form-control @error('average') is-invalid @enderror"
                        value="{{ old('average', $followUp?->average) }}" id="average" placeholder="media">
                </div>
                {!! $errors->first('average', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="glycemia_level" class="form-label">Nivel de glicemia</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="glycemia_level"
                    class="form-control @error('glycemia_level') is-invalid @enderror"
                    value="{{ old('glycemia_level', $followUp?->glycemia_level) }}" id="glycemia_level"
                    placeholder="glicemia">
            </div>
            {!! $errors->first(
                'glycemia_level',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        <div class="form-group mb-2 mb20" hidden>
            <label for="vet_id" class="form-label">{{ __('Vet Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                    value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id" placeholder="Vet Id">
            </div>
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>
