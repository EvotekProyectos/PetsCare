
<div class="row padding-1 p-1">
    <div class="col-md-12">

        
        {{-- <div class="form-group mb-2 mb20">
            <label for="date" class="form-label">Hora</label>
            <div class="input-group mb-3">
                <span class="input-group-text text-primary bg-primary-subtle" id="basic-addon1">
                    <i class="fa fa-clock"></i></span>
                <input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', $followupSurgical?->date) }}" id="date" placeholder="Hora">
            </div>
            {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}

        <div class="col-12" hidden>
            <div class="col-md-6 ms-4">
                <div class="form-group">
                    <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
                    <input type="hidden" name="date" id="date_surgicals" value="{{ old('date', $followupSurgical?->date ?? \Carbon\Carbon::now()->format('Y-m-d H:i:s')) }}">
                    
                </div>
            </div>
        </div>
        

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id_surgicals" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" 
            value="{{ old('reception_id', $followupSurgical?->reception_id) }}" id="reception_id_surgicals" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <div class="text-center bg-primary text-white py-1 mb-4 padding: 10px;">
            <h5 class="mb-0">ESTADO DE LA MASCOTA</h5>
        </div>
        

        <div class="row">
            <div class="col-2">
                <label for="alterations_surgicals" class="form-label">ALTERACIONES EN CONSTANTES</label>
            </div>
            
            <div class="col-md-1">
                <input type="radio" id="alterations_yes_surgicals" 
                name="alterations" value="0"
                {{ (int) old('alterations', $followupSurgical?->alterations) == '0' ? 'checked' : '' }}>
                <label for="alterations_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="alterations_no_surgicals" name="alterations" value="1"
                {{ (int) old('alterations', $followupSurgical?->alterations) == '1' ? 'checked' : '' }}>
                <label for="alterations_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
            
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="which_alterations"
                        class="form-control @error('which_alterations') is-invalid @enderror"
                        value="{{ old('which_alterations', $followupSurgical?->which_alterations) }}" 
                        id="which_alterations_surgicals"
                        placeholder="¿Cuáles?">
                        {!! $errors->first('which_alterations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                    
            </div>
        </div>
    
        <div class="row">
            <div class="col-2">
                <label for="therapeutic_surgicals" class="form-label">CAMBIOS DE TERAPEUTICA</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_yes_surgicals" name="therapeutic" value="0"
                {{ (int) old('therapeutic', $followupSurgical?->therapeutic) == '0' ? 'checked' : '' }}>
                <label for="therapeutic_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="therapeutic_no_surgicals" name="therapeutic" value="1"
                {{ (int) old('therapeutic', $followupSurgical?->therapeutic) == '1' ? 'checked' : '' }}>
                <label for="therapeutic_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
    
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="which_therapeutic" 
                class="form-control @error('which_therapeutic') is-invalid @enderror"
                 value="{{ old('which_therapeutic', $followupSurgical?->which_therapeutic) }}" 
                 id="which_therapeutic_surgicals" 
                 placeholder="¿Cuáles?">
                {!! $errors->first('which_therapeutic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
            
    
        <div class="row">
            <div class="col-2">
                <label for="vomiting_surgicals" class="form-label">VÓMITOS</label>
            </div>
    
            <div class="col-md-1">
                <input type="radio" id="vomiting_yes_surgicals" name="vomiting" value="0"
                {{(int) old('vomiting',  $followupSurgical?->vomiting) == '0' ? 'checked' : '' }}>
                <label for="vomiting_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="vomiting_no_surgicals" name="vomiting" value="1"
                {{(int) old('vomiting',  $followupSurgical?->vomiting) == '1' ? 'checked' : '' }}>
                <label for="vomiting_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
    
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                <input type="text" name="quantity_vomiting"
                class="form-control @error('quantity_vomiting') is-invalid @enderror" 
                value="{{ old('quantity_vomiting',  $followupSurgical?->quantity_vomiting) }}" 
                id="quantity_vomiting_surgicals"
                 placeholder="Cantidad y/o aspecto">
               {!! $errors->first('quantity_vomiting', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
           </div>
        </div>
    </div>
          
           
        <div class="row">
            <div class="col-2">
                <label for="defecation_surgicals" class="form-label">DEFECO/DIARREAS</label>
            </div>
    
            <div class="col-md-1">
                <input type="radio" id="defecation_yes_surgicals" name="defecation" value="0"
                {{(int) old('defecation',  $followupSurgical?->defecation) == '0' ? 'checked' : '' }}>
                <label for="defecation_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="defecation_no_surgicals" name="defecation" value="1"
                {{(int) old('defecation',  $followupSurgical?->defecation) == '1' ? 'checked' : '' }}>
                <label for="defecation_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
    
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="quantity_defecation" 
                    class="form-control @error('quantity_defecation') is-invalid @enderror"
                     value="{{ old('quantity_defecation',  $followupSurgical?->quantity_defecation) }}" 
                     id="quantity_defecation_surgicals" 
                     placeholder="Cantidad y/o aspecto">
                    {!! $errors->first('quantity_defecation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
               
    
        <div class="row">
            <div class="col-2">
                <label for="urine_surgicals" class="form-label">ORINA</label>
            </div>
           
            <div class="col-md-1">
                <input type="radio" id="urine_yes_surgicals" name="urine" value="0"
                {{(int) old('urine', $followupSurgical?->urine) == '0' ? 'checked' : '' }}>
                <label for="urine_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="urine_no_surgicals" name="urine" value="1"
                {{(int) old('urine', $followupSurgical?->urine) == '1' ? 'checked' : '' }}>
                <label for="urine_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
    
            
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="quantity_urine" 
                class="form-control @error('quantity_urine') is-invalid @enderror"
                 value="{{ old('quantity_urine', $followupSurgical?->quantity_urine) }}" 
                 id="quantity_urine_surgicals" 
                 placeholder="Cantidad y/o aspecto">
                {!! $errors->first('quantity_urine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
            
        <div class="row">
            <div class="col-2">
                <label for="feeding_surgicals" class="form-label">ALIMENTACIÓN</label>
            </div>
       
            <div class="col-md-1">
                <input type="radio" id="feeding_yes_surgicals" name="feeding" value="0"
                {{(int) old('feeding',  $followupSurgical?->feeding) == '0' ? 'checked' : '' }}>
                <label for="feeding_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="feeding_no_surgicals" name="feeding" value="1"
                {{(int) old('feeding', $followupSurgical?->feeding) == '1' ? 'checked' : '' }}>
                <label for="feeding_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>
    
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="type_feeding" 
                    class="form-control @error('type_feeding') is-invalid @enderror" 
                    value="{{ old('type_feeding',  $followupSurgical?->type_feeding) }}" 
                    id="type_feeding_surgicals" 
                    placeholder="Tipo de alimento">
                    {!! $errors->first('type_feeding', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
        </div>

        <div class="row">
            <div class="form-group mb-2 mb20">
                <label for="pendings_surgicals" class="form-label">PENDIENTES</label>
                <div class="input-group mb-3">
                    <span class="input-group-text text-primary bg-primary-subtle" id="basic-addon1">
                        <i class="vaadin--lines-list"></i>
                    </span>
                    <textarea name="pendings" 
                        class="form-control @error('pendings') is-invalid @enderror" 
                        id="pendings_surgicals" 
                        rows="4">{{ old('pendings', $followupSurgical?->pendings) }}</textarea>
                    {!! $errors->first('pendings', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
        

        <div class="text-center bg-primary text-white py-1 mb-4 padding: 10px;">
            <h5 class="mb-0">HERIDA</h5>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="cleaning_surgicals" class="form-label">SE REALIZÓ LIMPIEZA</label>
            </div>
       
            <div class="col-md-1">
                <input type="radio" id="cleaning_yes_surgicals" name="cleaning" value="0"
                {{(int) old('cleaning',  $followupSurgical?->cleaning) == '0' ? 'checked' : '' }}>
                <label for="cleaning_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="cleaning_no_surgicals" name="cleaning" value="1"
                {{(int) old('cleaning', $followupSurgical?->cleaning) == '1' ? 'checked' : '' }}>
                <label for="cleaning_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>

        <div class="col-md-8">
        
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="clean_observations" 
                class="form-control @error('clean_observations') is-invalid @enderror" 
                value="{{ old('clean_observations',   $followupSurgical?->clean_observations) }}" 
                id="clean_observations_surgicals" 
                placeholder="OBSERVACIONES">
                {!! $errors->first('clean_observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
    </div>
</div>
        <div class="row">
            <div class="col-2">
                <label for="secretion_surgicals" class="form-label">PRESENTÓ SECRESIÓN</label>
            </div>
       
            <div class="col-md-1">
                <input type="radio" id="secretion_yes_surgicals" name="secretion" value="0"
                {{(int) old('secretion',  $followupSurgical?->secretion) == '0' ? 'checked' : '' }}>
                <label for="secretion_yes_surgicals" class="radio-label shadow border-0">Si</label>
            </div>
            <div class="col-md-1">
                <input type="radio" id="secretion_no_surgicals" name="secretion" value="1"
                {{(int) old('secretion', $followupSurgical?->cleaning) == '1' ? 'checked' : '' }}>
                <label for="secretion_no_surgicals" class="radio-label shadow border-0">No</label>
            </div>

        <div class="col-md-8">
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="secretion_observations" 
                class="form-control @error('secretion_observations') is-invalid @enderror" 
                value="{{ old('secretion_observations',   $followupSurgical?->secretion_observations) }}"
                 id="secretion_observations_surgicals" 
                placeholder="OBSERVACIONES">
                {!! $errors->first('secretion_observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-2">
            <label for="drainage_surgicals" class="form-label">DRENES ACTIVOS</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="drainage_yes_surgicals" name="drainage" value="0"
            {{(int) old('drainage', $followupSurgical?->drainage) == '0' ? 'checked' : '' }}>
            <label for="drainage_yes_surgicals" class="radio-label shadow border-0">Si</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="drainage_no_surgicals" name="drainage" value="1"
            {{(int) old('drainage', $followupSurgical?->drainage) == '1' ? 'checked' : '' }}>
            <label for="drainage_no_surgicals" class="radio-label shadow border-0">No</label>
        </div>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="quantity_drainage"
                class="form-control @error('quantity_drainage') is-invalid @enderror" 
                value="{{ old('quantity_drainage', $followupSurgical?->quantity_drainage) }}" 
                id="quantity_drainage_surgicals"
                placeholder="{{ __('CANTIDAD COLECTADA/ASPECTOS') }}">
                {!! $errors->first('quantity_drainage', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
    
    <div class="text-center bg-primary text-white py-1 mb-4 padding: 10px;">
        <h5 class="mb-0">MANEJO DEL DOLOR</h5>
    </div>
    <div class="row mb-3">
        <div class="col-2">
            <label for="blockedages_surgicals" class="form-label">BLOQUEOS</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="blockedages_yes_surgicals" name="blockedages" value="0"
            {{(int) old('blockedages', $followupSurgical?->blockedages) == '0' ? 'checked' : '' }}>
            <label for="blockedages_yes_surgicals" class="radio-label shadow border-0">Si</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="blockedages_no_surgicals" name="blockedages" value="1"
            {{(int) old('blockedages', $followupSurgical?->blockedages) == '1' ? 'checked' : '' }}>
            <label for="blockedages_no_surgicals" class="radio-label shadow border-0">No</label>
        </div>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="type_blocked"
                class="form-control @error('type_blocked') is-invalid @enderror"
                value="{{ old('type_blocked', $followupSurgical?->type_blocked) }}" 
                id="type_blocked_surgicals"
                placeholder="{{ __('TIPO DE BLOQUEO') }}">
                {!! $errors->first('type_blocked', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-2">
            <label for="infusions_surgicals" class="form-label">INFUSIONES</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="infusions_yes_surgicals" name="infusions" value="0"
            {{(int) old('infusions', $followupSurgical?->infusions) == '0' ? 'checked' : '' }}>
            <label for="infusions_yes_surgicals" class="radio-label shadow border-0">Si</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="infusions_no_surgicals" name="infusions" value="1"
            {{(int) old('infusions', $followupSurgical?->infusions) == '1' ? 'checked' : '' }}>
            <label for="infusions_no_surgicals" class="radio-label shadow border-0">No</label>
        </div>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-primary-subtle" id="basic-addon3">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="type_time_infusions"
                class="form-control @error('type_time_infusions') is-invalid @enderror"
                value="{{ old('type_time_infusions', $followupSurgical?->type_time_infusions) }}" 
                id="type_time_infusions_surgicals"
                placeholder="{{ __('TIPO DE INFUSIÓN/¿CUÁNTAS HORAS?') }}">
                {!! $errors->first('type_time_infusions', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-2">
            <label for="alterations_surgery_surgicals" class="form-label">ALTERACIONES EN CIRUGÍA</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="alterations_surgery_yes_surgicals" name="alterations_surgery" value="0"
            {{(int) old('alterations_surgery', $followupSurgical?->alterations_surgery) == '0' ? 'checked' : '' }}>
            <label for="alterations_surgery_yes_surgicals" class="radio-label shadow border-0">Si</label>
        </div>
        <div class="col-md-1">
            <input type="radio" id="alterations_surgery_no_surgicals" name="alterations_surgery" value="1"
            {{(int) old('alterations_surgery', $followupSurgical?->alterations_surgery) == '1' ? 'checked' : '' }}>
            <label for="alterations_surgery_no_surgicals" class="radio-label shadow border-0">No</label>
        </div>

        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="which_alterations_surgery"
                class="form-control @error('which_alterations_surgery') is-invalid @enderror"
                value="{{ old('which_alterations_surgery', $followupSurgical?->which_alterations_surgery) }}" 
                id="which_alterations_surgery_surgicals"
                placeholder="{{ __('¿CUÁLES?') }}">
                {!! $errors->first('which_alterations_surgery', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
    <div class="form-group mb-2 mb20" hidden>
        <label for="vet_id_surgicals" class="form-label">{{ __('Vet Id') }}</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                <span class="vaadin--lines-list"></span></span>
            <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id_surgicals" placeholder="Vet Id">
        </div>
        {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Registrar</button>
    </div>
</div>