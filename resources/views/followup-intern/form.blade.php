<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="col-12">
            <div class="col-md-6 ms-4">
                <div class="form-group">
                    <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
                    <input type="hidden" name="date" id="date" value="{{ old('date', $followupIntern?->date ?? \Carbon\Carbon::now()->format('Y-m-d H:i:s')) }}">
                    <p>Paciente: <strong>Happy</strong></p>
                </div>
            </div>
        </div>
  
        <div class="text-center bg-primary text-white py-1 mb-4" style="padding: 10px;">
            <h5 class="mb-0">ESTADO DE LA MASCOTA</h5>
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $followupIntern?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="row">
            <div class="col-2">
                <label for="alterations" class="form-label">ALTERACIONES EN CONSTANTES</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="alterations_yes" name="alterations" value="0" {{ old('alterations', $followupIntern?->alterations) == '0' ? 'checked' : '' }}>
                <label for="alterations_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="alterations_no" name="alterations" value="1" {{ old('alterations', $followupIntern?->alterations) == '1' ? 'checked' : '' }}>
                <label for="alterations_no" class="radio-label shadow border-0">No</label>
            </div>
            
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="which_alterations" class="form-control @error('which_alterations') is-invalid @enderror" value="{{ old('which_alterations') }}" id="which_alterations" placeholder="¿Cuáles?">
                    {!! $errors->first('which_alterations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="therapeutic" class="form-label">CAMBIOS DE TERAPEUTICA</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_yes" name="therapeutic" value="0" {{ old('therapeutic', $followupIntern?->therapeutic) == '0' ? 'checked' : '' }}>
                <label for="therapeutic_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_no" name="therapeutic" value="1" {{ old('therapeutic', $followupIntern?->therapeutic) == '1' ? 'checked' : '' }}>
                <label for="therapeutic_no" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="which_therapeutic" class="form-control @error('which_therapeutic') is-invalid @enderror" value="{{ old('which_therapeutic', $followupIntern?->which_therapeutic) }}" id="which_therapeutic" placeholder="¿Cuáles?">
                    {!! $errors->first('which_therapeutic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="vomiting" class="form-label">VÓMITOS</label>
            </div>

            <div class="col-md-1">
                <input type="radio" id="vomiting_yes" name="vomiting" value="0" {{ old('vomiting', $followupIntern?->vomiting) == '0' ? 'checked' : '' }}>
                <label for="vomiting_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="vomiting_no" name="vomiting" value="1" {{ old('vomiting', $followupIntern?->vomiting) == '1' ? 'checked' : '' }}>
                <label for="vomiting_no" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_vomiting" class="form-control @error('quantity_vomiting') is-invalid @enderror" value="{{ old('quantity_vomiting', $followupIntern?->quantity_vomiting) }}" id="quantity_vomiting" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_vomiting', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="defecation" class="form-label">DEFECO/DIARREAS</label>
            </div>

            <div class="col-md-1">
                <input type="radio" id="defecation_yes" name="defecation" value="0" {{ old('defecation', $followupIntern?->defecation) == '0' ? 'checked' : '' }}>
                <label for="defecation_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="defecation_no" name="defecation" value="1" {{ old('defecation', $followupIntern?->defecation) == '1' ? 'checked' : '' }}>
                <label for="defecation_no" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_defecation" class="form-control @error('quantity_defecation') is-invalid @enderror" value="{{ old('quantity_defecation', $followupIntern?->quantity_defecation) }}" id="quantity_defecation" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_defecation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="urine" class="form-label">ORINA</label>
            </div>

            <div class="col-md-1">
                <input type="radio" id="urine_yes" name="urine" value="0" {{ old('urine', $followupIntern?->urine) == '0' ? 'checked' : '' }}>
                <label for="urine_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="urine_no" name="urine" value="1" {{ old('urine', $followupIntern?->urine) == '1' ? 'checked' : '' }}>
                <label for="urine_no" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_urine" class="form-control @error('quantity_urine') is-invalid @enderror" value="{{ old('quantity_urine', $followupIntern?->quantity_urine) }}" id="quantity_urine" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_urine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="feeding" class="form-label">ALIMENTACIÓN</label>
            </div>

            <div class="col-md-1">
                <input type="radio" id="feeding_yes" name="feeding" value="0"
                {{ old('feeding', $followupIntern->feeding) == '0' ? 'checked' : '' }}>
                <label for="feeding_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="feeding_no" name="feeding" value="1"
                {{ old('feeding', $followupIntern->feeding) == '1' ? 'checked' : '' }}>
                <label for="feeding_no" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="type_feeding" 
                    class="form-control @error('type_feeding') is-invalid @enderror" 
                    value="{{ old('type_feeding', $followupIntern?->type_feeding) }}" id="type_feeding" 
                    placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('type_feeding', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="ultrasounds" class="form-label">ULTRASONIDO/RADIOGRAFÍAS</label>
            </div>

            <div class="col-md-1">
                <input type="radio" id="ultrasounds_yes" name="ultrasounds" value="0"
                {{ old('ultrasounds', $followupIntern->ultrasounds) == '0' ? 'checked' : '' }}>
                <label for="ultrasounds_yes" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="ultrasounds_no" name="ultrasounds" value="1"
                {{ old('ultrasounds', $followupIntern->ultrasounds) == '1' ? 'checked' : '' }}>
                <label for="ultrasounds_no" class="radio-label shadow border-0">No</label>
            </div>
    

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="observations_ultrasounds"
                    class="form-control @error('observations_ultrasounds') is-invalid @enderror" 
                    value="{{ old('observations_ultrasounds', $followupIntern?->observations_ultrasounds) }}" id="observations_ultrasounds" 
                    placeholder="Observaciones">
                   {!! $errors->first('observations_ultrasounds', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
               </div>
    
            </div>
        </div>
            
    </div>
    

    
    <div class="row">
        <div class="form-group mb-2 mb20">
            <label for="pendings" class="form-label">PENDIENTES</label>
            <div class="input-group mb-3">
                <span class="input-group-text text-primary bg-primary-subtle" id="basic-addon1">
                    <i class="vaadin--lines-list"></i>
                </span>
                <textarea name="pendings" 
                          class="form-control @error('pendings') is-invalid @enderror" 
                          id="pendings"
                          rows="4">{{ old('pendings', $followupIntern?->pendings) }}</textarea>
            </div>
            {!! $errors->first('pendings', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    

            
        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                <i class="fas fa-plus"></i>
                Registrar</button>
        </div>

    </div>
</div>
