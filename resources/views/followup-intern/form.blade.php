<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="col-12" hidden>
            <div class="col-md-6 ms-4">
                <div class="form-group">
                    <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
                    <input type="hidden" name="date" id="date" value="{{ old('date', $followupIntern?->date ?? \Carbon\Carbon::now()->format('Y-m-d H:i:s')) }}">
                   
                </div>
            </div>
        </div>
  
        <div class="text-center bg-primary text-white py-1 mb-4" style="padding: 10px;">
            <h5 class="mb-0">ESTADO DE LA MASCOTA</h5>
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id_interns" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" 
            class="form-control @error('reception_id') is-invalid @enderror" 
            value="{{ old('reception_id', $followupIntern?->reception_id) }}" id="reception_id_interns" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="row">
            
            <div class="col-2">
                <label for="alterations_interns" class="form-label">ALTERACIONES EN CONSTANTES</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="alterations_yes_interns" name="alterations" 
                value="0" 
                {{ old('alterations', isset($followupIntern) ? $followupIntern->alterations : null) === 0 ? 'checked' : '' }}>
                <label for="alterations_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="alterations_no_interns" name="alterations" 
                value="1" 
                {{ old('alterations', isset($followupIntern) ? $followupIntern->alterations : null) === 1 ? 'checked' : '' }}>
                <label for="alterations_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            
            
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="which_alterations" 
                    class="form-control @error('which_alterations') is-invalid @enderror" 
                    value="{{ old('which_alterations', $followupIntern?->which_alterations) }}" id="which_alterations_interns" placeholder="¿Cuáles?">
                    {!! $errors->first('which_alterations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="therapeutic_interns" class="form-label">CAMBIOS DE TERAPÉUTICA</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_yes_interns" name="therapeutic" 
                    value="0" {{ old('therapeutic', isset($followupIntern) ? $followupIntern->therapeutic: null) === 0 ? 'checked' : '' }}>
                <label for="therapeutic_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_no_interns" name="therapeutic" 
                    value="1" {{  old('therapeutic', isset($followupIntern) ? $followupIntern->therapeutic: null) === 1 ? 'checked' : '' }}>
                <label for="therapeutic_no_interns" class="radio-label shadow border-0">No</label>
            </div>

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="which_therapeutic" 
                    class="form-control @error('which_therapeutic') is-invalid @enderror" 
                    value="{{ old('which_therapeutic', $followupIntern?->which_therapeutic) }}" 
                    id="which_therapeutic_interns" placeholder="¿Cuáles?">
                    {!! $errors->first('which_therapeutic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="vomiting_interns" class="form-label">VÓMITOS</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="vomiting_yes_interns" name="vomiting" 
                    value="0" {{ old('vomiting', isset($followupIntern) ? $followupIntern->vomiting: null) === 0 ? 'checked' : '' }}>
                <label for="vomiting_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="vomiting_no_interns" name="vomiting" 
                    value="1" {{ old('vomiting', isset($followupIntern) ? $followupIntern->vomiting: null) === 1 ? 'checked' : '' }}>
                <label for="vomiting_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_vomiting" 
                    class="form-control @error('quantity_vomiting') is-invalid @enderror" 
                    value="{{ old('quantity_vomiting', $followupIntern?->quantity_vomiting) }}" 
                    id="quantity_vomiting_interns" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_vomiting', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="defecation_interns" class="form-label">DEFECO/DIARREAS</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="defecation_yes_interns" name="defecation" 
                    value="0" {{ old('defecation', isset($followupIntern) ? $followupIntern->defecation:null) === 0 ? 'checked' : '' }}>
                <label for="defecation_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="defecation_no_interns" name="defecation" 
                    value="1" {{ old('defecation', isset($followupIntern) ? $followupIntern->defecation:null) === 1 ? 'checked' : '' }}>
                <label for="defecation_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_defecation" 
                    class="form-control @error('quantity_defecation') is-invalid @enderror" 
                    value="{{ old('quantity_defecation', $followupIntern?->quantity_defecation) }}" 
                    id="quantity_defecation_interns" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_defecation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="urine_interns" class="form-label">ORINA</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="urine_yes_interns" name="urine" 
                    value="0" {{ old('urine', isset($followupIntern) ? $followupIntern->urine: null) === 0 ? 'checked' : '' }}>
                <label for="urine_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="urine_no_interns" name="urine" 
                    value="1" {{ old('urine', isset($followupIntern) ? $followupIntern->urine: null) === 1 ? 'checked' : '' }}>
                <label for="urine_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2"><span class="vaadin--lines-list"></span></span>
                    <input type="text" name="quantity_urine" 
                    class="form-control @error('quantity_urine') is-invalid @enderror" 
                    value="{{ old('quantity_urine', $followupIntern?->quantity_urine) }}" 
                    id="quantity_urine_interns" placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_urine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="feeding_interns" class="form-label">ALIMENTACIÓN</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="feeding_yes_interns" name="feeding" 
                    value="0" {{old('feeding', isset($followupIntern) ? $followupIntern->feeding: null) === 0 ? 'checked' : '' }}>
                <label for="feeding_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="feeding_no_interns" name="feeding" 
                    value="1" {{old('feeding', isset($followupIntern) ? $followupIntern->feeding: null) === 1 ? 'checked' : '' }}>
                <label for="feeding_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            

            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="type_feeding" 
                    class="form-control @error('type_feeding') is-invalid @enderror" 
                    value="{{ old('type_feeding', $followupIntern?->type_feeding) }}" 
                    id="type_feeding_interns" 
                    placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('type_feeding', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="ultrasounds_interns" class="form-label">ULTRASONIDO/RADIOGRAFÍAS</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="ultrasounds_yes_interns" name="ultrasounds" 
                    value="0" {{old('ultrasounds', isset($followupIntern) ? $followupIntern->ultrasounds: null) === 0 ? 'checked' : '' }}>
                <label for="ultrasounds_yes_interns" class="radio-label shadow border-0">Sí</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="ultrasounds_no_interns" name="ultrasounds" 
                    value="1" {{old('ultrasounds', isset($followupIntern) ? $followupIntern->ultrasounds: null) === 1 ? 'checked' : '' }}>
                <label for="ultrasounds_no_interns" class="radio-label shadow border-0">No</label>
            </div>
            
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="observations_ultrasounds"
                    class="form-control @error('observations_ultrasounds') is-invalid @enderror" 
                    value="{{ old('observations_ultrasounds', $followupIntern?->observations_ultrasounds) }}" 
                    id="observations_ultrasounds_interns" 
                    placeholder="Observaciones">
                   {!! $errors->first('observations_ultrasounds', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
               </div>
    
            </div>
        </div>
            
    </div>
    

    
    <div class="row">
        <div class="form-group mb-2 mb20">
            <label for="pendings_interns" class="form-label">PENDIENTES</label>
            <div class="input-group mb-3">
                <span class="input-group-text text-primary bg-primary-subtle" id="basic-addon1">
                    <i class="vaadin--lines-list"></i>
                </span>
                <textarea name="pendings" 
                          class="form-control @error('pendings') is-invalid @enderror" 
                          id="pendings_interns"
                          rows="4">{{ old('pendings', $followupIntern?->pendings) }}</textarea>
            </div>
            {!! $errors->first('pendings', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="form-group mb-2 mb20" hidden>
        <label for="vet_id_interns" class="form-label">{{ __('Vet Id') }}</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                <span class="vaadin--lines-list"></span></span>
            <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id_interns" placeholder="Vet Id">
        </div>
        {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>
    

            
        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                <i class="fas fa-plus"></i>
                Registrar</button>
        </div>

    </div>
</div>
