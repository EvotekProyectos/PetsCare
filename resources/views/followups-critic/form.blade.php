@push('styles')
    <link rel="stylesheet" href="{{ asset('css/budgets/form.css') }}">
@endpush
<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id_critics" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{ old('reception_id', $followupsCritic?->reception_id) }}" id="reception_id_critics"
                    placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="pet_status_critics" class="form-label">Estado de la Mascota</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="ic--twotone-pets"></span>
                </span>
                <input type="text" name="pet_status" class="form-control @error('pet_status') is-invalid @enderror"
                    value="{{ old('pet_status', $followupsCritic?->pet_status) }}" id="pet_status_critics"
                    placeholder="Estado de la Mascota">
            </div>
            {!! $errors->first('pet_status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <h5> MONITOREO DE CONSTANTES </h5>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="preasure_critics" class="form-label">Presiones</label>
                </div>

                <div class="col-10">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="preasure"
                            class="form-control @error('preasure') is-invalid @enderror"
                            value="{{ old('preasure', $followupsCritic?->preasure) }}" id="preasure_critics"
                            placeholder="Como se mantuvo en turno?">
                    </div>
                    {!! $errors->first('preasure', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="temperature_critics" class="form-label">Temperatura</label>
                </div>

                <div class="col-10">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="temperature"
                            class="form-control @error('temperature') is-invalid @enderror"
                            value="{{ old('temperature', $followupsCritic?->temperature) }}" id="temperature_critics"
                            placeholder="Como se mantuvo en turno?">
                    </div>
                    {!! $errors->first('temperature', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="glycemia_critics" class="form-label">Glicemias</label>
                </div>

                <div class="col-10">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="glycemia"
                            class="form-control @error('glycemia') is-invalid @enderror"
                            value="{{ old('glycemia', $followupsCritic?->glycemia) }}" id="glycemia_critics"
                            placeholder="Como se mantuvo en turno?">
                    </div>
                    {!! $errors->first('glycemia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="throwup_critics" class="form-label">Vomitos</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="throwup_yes_critics" name="throwup" value="0"
                        {{ old('throwup', $followupsCritic?->throwup) == 0 ? 'checked' : '' }}>
                    <label for="throwup_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="throwup_no_critics" name="throwup" value="1"
                        {{ old('throwup', $followupsCritic?->throwup) == 1 ? 'checked' : '' }}>
                    <label for="throwup_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="throwup_detail"
                            class="form-control @error('throwup_detail') is-invalid @enderror"
                            value="{{ old('throwup_detail', $followupsCritic?->throwup_detail) }}" id="throwup_detail_critics"
                            placeholder="Cantidad/Aspecto">
                        {!! $errors->first(
                            'throwup_detail',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="defecate_critics" class="form-label">Defeco/Diarreas</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="defecate_yes_critics" name="defecate" value="0"
                        {{ old('defecate', $followupsCritic?->defecate) == 0 ? 'checked' : '' }}>
                    <label for="defecate_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="defecate_no_critics" name="defecate" value="1"
                        {{ old('defecate', $followupsCritic?->defecate) == 1 ? 'checked' : '' }}>
                    <label for="defecate_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="defecate_detail"
                            class="form-control @error('defecate_detail') is-invalid @enderror"
                            value="{{ old('defecate_detail', $followupsCritic?->defecate_detail) }}"
                            id="defecate_detail_critics" placeholder="Cantidad/Aspecto">
                        {!! $errors->first(
                            'defecate_detail',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="orino_critics" class="form-label">Orino</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="orino_yes_critics" name="orino" value="0"
                        {{ old('orino', $followupsCritic?->orino) == 0 ? 'checked' : '' }}>
                    <label for="orino_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="orino_no_critics" name="orino" value="1"
                        {{ old('orino', $followupsCritic?->orino) == 1 ? 'checked' : '' }}>
                    <label for="orino_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="orino_detail"
                            class="form-control @error('orino_detail') is-invalid @enderror"
                            value="{{ old('orino_detail', $followupsCritic?->orino_detail) }}" id="orino_detail_critics"
                            placeholder="Cantidad/Aspecto">
                        {!! $errors->first('orino_detail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="eat_critics" class="form-label">Comio</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="eat_yes_critics" name="eat" value="0"
                        {{ old('eat', $followupsCritic?->eat) == 0 ? 'checked' : '' }}>
                    <label for="eat_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="eat_no_critics" name="eat" value="1"
                        {{ old('eat', $followupsCritic?->eat) == 1 ? 'checked' : '' }}>
                    <label for="eat_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="eat_detail"
                            class="form-control @error('eat_detail') is-invalid @enderror"
                            value="{{ old('eat_detail', $followupsCritic?->eat_detail) }}" id="eat_detail_critics"
                            placeholder="Tipo de Alimento/Frecuecnia/Cantidad">
                        {!! $errors->first('eat_detail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="infusions_critics" class="form-label">Infusiones</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="infusions_yes_critics" name="infusions" value="0"
                        {{ old('infusions', $followupsCritic?->infusions) == 0 ? 'checked' : '' }}>
                    <label for="infusions_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="infusions_no_critics" name="infusions" value="1"
                        {{ old('infusions', $followupsCritic?->infusions) == 1 ? 'checked' : '' }}>
                    <label for="infusions_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="infusions_detail"
                            class="form-control @error('infusions_detail') is-invalid @enderror"
                            value="{{ old('infusions_detail', $followupsCritic?->infusions_detail) }}"
                            id="infusions_detail_critics" placeholder="Cuales?/Cuantas horas?">
                        {!! $errors->first(
                            'infusions_detail',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="terapeutic_critics" class="form-label">Cambios de Terapeutica</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="terapeutic_yes_critics" name="terapeutic" value="0"
                        {{ old('terapeutic', $followupsCritic?->terapeutic) == 0 ? 'checked' : '' }}>
                    <label for="terapeutic_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="terapeutic_no_critics" name="terapeutic" value="1"
                        {{ old('terapeutic', $followupsCritic?->terapeutic) == 1 ? 'checked' : '' }}>
                    <label for="terapeutic_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="terapeutic_detail"
                            class="form-control @error('terapeutic_detail') is-invalid @enderror"
                            value="{{ old('terapeutic_detail', $followupsCritic?->terapeutic_detail) }}"
                            id="terapeutic_detail_critics" placeholder="Cuales?">
                        {!! $errors->first(
                            'terapeutic_detail',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-2 mb20">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label for="pends_critics" class="form-label">Pendientes</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="pends"  class="form-control @error('pends') is-invalid @enderror" id="pends_critics"
                            rows="4">{{ old('pends', $followupsCritic?->pends) }}</textarea>
                        {!! $errors->first('pends', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            {{-- <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="pends" class="form-label">Pendientes</label>
                </div>

                <div class="col-10">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="pends"
                            class="form-control @error('pends') is-invalid @enderror"
                            value="{{ old('pends', $followupsCritic?->pends) }}" id="pends" placeholder="Pends">
                    </div>
                    {!! $errors->first('pends', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div> --}}
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="imaging_critics" class="form-label">Ultrasonido/Radiografias</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="imaging_yes_critics" name="imaging" value="0"
                        {{ old('imaging', $followupsCritic?->imaging) == 0 ? 'checked' : '' }}>
                    <label for="imaging_yes_critics" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="imaging_no_critics" name="imaging" value="1"
                        {{ old('imaging', $followupsCritic?->imaging) == 1 ? 'checked' : '' }}>
                    <label for="imaging_no_critics" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="imaging_detail"
                            class="form-control @error('imaging_detail') is-invalid @enderror"
                            value="{{ old('imaging_detail', $followupsCritic?->imaging_detail) }}"
                            id="imaging_detail_critics" placeholder="Observaciones">
                        {!! $errors->first(
                            'imaging_detail',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>


        <div class="form-group mb-2 mb20" hidden>
            <label for="vet_id_critics" class="form-label">{{ __('Vet Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                    value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id_critics" placeholder="Vet Id">
            </div>
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>
