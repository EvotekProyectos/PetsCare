<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror"
                value="{{ old('reception_id', $reception->id) }}" id="reception_id_general" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="instructions" class="form-label">INSTRUCCIONES</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fas fa-edit text-primary"></i>
                </span>
                <textarea name="instructions" class="form-control @error('instructions') is-invalid @enderror" id="instructions"
                    placeholder="Indique las instrucciones para el servicio/s" rows="4">{{ old('instructions', $generalGrooming?->instructions) }}</textarea>
                {!! $errors->first('instructions', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group mb-2 mb20">
                <div class="form-group mb-2 mb20">
                    <label for="next_service" class="form-label">PROXIMO SERVICIO</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock "></span>
                        </span>
                        <input type="datetime-local" name="next_service"
                            class="form-control @error('next_service') is-invalid @enderror"
                            value="{{ old('next_service', $generalGrooming?->next_service ? \Carbon\Carbon::parse($generalGrooming->next_service)->format('Y-m-d\TH:i') : now()->addMonth()->format('Y-m-d\TH:i')) }}"
                            id="next_service" placeholder="Next Service">
                    </div>
                    {!! $errors->first('next_service', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-6 form-group mb-2 mb20">
                <div class="form-group mb-2 mb20">
                    <label for="critic_status" class="form-label">ESTADO CRITICO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="material-symbols--pulse-alert-outline"></span></span>
                        <select name="critic_status" class="form-control @error('critic_status') is-invalid @enderror"
                            id="critic_status">
                            <option value="0"> No Aplica</option>
                            <option value="1"> Aplica</option>
                        </select>
                        {!! $errors->first(
                            'critic_status',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 form-group mb-2 mb20">
                <div class="form-group mb-2 mb20">
                    <label for="delivery_service" class="form-label">SERVICIO A DOMICILIO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="mdi--house-export-outline"></span></span>
                        <select name="delivery_service"
                            class="form-control @error('delivery_service') is-invalid @enderror" id="delivery_service">
                            <option value="0"> No Aplica</option>
                            <option value="1"> Aplica</option>
                        </select>
                        {{-- <input type="text" name="delivery_service" class="form-control @error('delivery_service') is-invalid @enderror" value="{{ old('delivery_service', $generalGrooming?->delivery_service) }}" id="delivery_service" placeholder="Delivery Service"> --}}
                        {!! $errors->first(
                            'delivery_service',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-6 form-group mb-2 mb20"  id="delivery_references_container">
                <div class="form-group mb-2 mb20">
                    <label for="delivery_references" class="form-label">REFERENCIAS DIRECCIÓN</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="gis--poi-map-o"></span>
                        </span>
                        <textarea name="delivery_references" class="form-control @error('delivery_references') is-invalid @enderror"
                            id="delivery_references" placeholder="Indique las referencias para el servicio a domicilio" rows="4">{{ old('delivery_references', $generalGrooming?->delivery_references) }}</textarea>
                        {{-- <input type="text" name="delivery_references" class="form-control @error('delivery_references') is-invalid @enderror" value="{{ old('delivery_references', $generalGrooming?->delivery_references) }}" id="delivery_references" placeholder="Delivery References"> --}}
                        {!! $errors->first(
                            'delivery_references',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>


            <div class="form-group mb-2 mb20" hidden>
                <label for="folio" class="form-label">FOLIO</label>
                <input type="text" name="folio" class="form-control @error('folio') is-invalid @enderror"
                    value="{{ old('folio', $generalGrooming?->folio) }}" id="folio" placeholder="Folio">
                {!! $errors->first('folio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

        </div>
        {{-- <div class="col-md-12 mt20 mt-2">
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div> --}}
    </div>
