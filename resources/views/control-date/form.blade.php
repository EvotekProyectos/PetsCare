<style>
    .select2-container .select2-selection--single {
        height: 2rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
    }
</style>


<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
           
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $controlDate?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        
        {{-- <div class="form-group mb-2 mb20">
            <label for="family_id" class="form-label">FAMILIA/PROPIETARIO</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="fluent-mdl2--family text-primary"></span>
                    </span>
                    <select name="family_id" class="form-control select2 @error('family_id') is-invalid @enderror"
                    id="family_id" placeholder="Family Id" onchange="getpets(this.value)" style="width: 100%;">
                        <option value="">Selecciona la familia</option>
                            @foreach ($families as $family)
                                <option value="{{ $family->id }}" {{ old('family_id', $controlDate?->family_id) == $family->id ? 'selected' : '' }}>
                                    {{ $family->name }} {{ $family->phone }}
                                </option>
                            @endforeach
                    </select>        
                     {!! $errors->first('family_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
        </div>

        <div class="form-group mb-2 mb20">
            <label for="pet_id" class="form-label">MASCOTA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="ic--twotone-pets"></span>
                </span>
                <select name="pet_id" class="form-control select2 @error('pet_id') is-invalid @enderror"
                  id="pet_id" placeholder="Pet Id" onchange="getFamily(this.value)" style="width: 100%;">
            
                  <option value="">Selecciona la mascota</option>
                  @foreach ($pets as $pet)
                      <option value="{{ $pet->id }}"
                  {{ old('pet_id', $controlDate?->pet_id) == $pet->id ? 'selected' : '' }}>
                  {{ $pet->name }} #{{ $pet->number_chip }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
    </div>

    <div class="form-group mb-2 mb20">
        <label for="family_id" class="form-label">FAMILIA/PROPIETARIO</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                <span class="fluent-mdl2--family text-primary"></span>
            </span>
            <select name="family_id" class="form-control select2 @error('family_id') is-invalid @enderror"
                id="family_id" placeholder="Family Id" onchange="getpets(this.value)" style="width: 100%;"
                {{ isset($controlDate) ? 'disabled' : '' }}>  <!-- Se deshabilita solo en edición -->
                <option value="">Selecciona la familia</option>
                @foreach ($families as $family)
                    <option value="{{ $family->id }}" 
                        {{ old('family_id', $controlDate?->family_id) == $family->id ? 'selected' : '' }}>
                        {{ $family->name }} {{ $family->phone }}
                    </option>
                @endforeach
            </select>        
            {!! $errors->first('family_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="form-group mb-2 mb20">
        <label for="pet_id" class="form-label">MASCOTA</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                <span class="ic--twotone-pets"></span>
            </span>
            <select name="pet_id" class="form-control select2 @error('pet_id') is-invalid @enderror"
                id="pet_id" placeholder="Pet Id" onchange="getFamily(this.value)" style="width: 100%;"
                {{ isset($controlDate) ? 'disabled' : '' }}>  <!-- Se deshabilita solo en edición -->
                <option value="">Selecciona la mascota</option>
                @foreach ($pets as $pet)
                    <option value="{{ $pet->id }}"
                        {{ old('pet_id', $controlDate?->pet_id) == $pet->id ? 'selected' : '' }}>
                        {{ $pet->name }} #{{ $pet->number_chip }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
        <div class="form-group mb-2 mb20">
            <label for="surgical_procedures_type_id" class="form-label">TIPO DE CITA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>

            <select name="date_type_id" class="form-control @error('date_type_id') is-invalid @enderror" id="date_type_id">
                <option value="">Selecciona el tipo de cita</option>
                @foreach ($types as $type)
                <option value="{{ $type->id }}" name="date_type_id"
                {{ old('date_type_id', $controlDate?->date_type_id)== $type->id  ? 'selected' : ''  }}>
                {{ $type->name }}</option>
                @endforeach
            </select>
        </div>
            {!! $errors->first('date_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="status_date_id" class="form-label">{{ __('Status Date Id') }}</label>
            <input type="text" name="status_date_id" class="form-control @error('status_date_id') is-invalid @enderror" value="{{ old('status_date_id', $controlDate?->status_date_id) }}" id="status_date_id" placeholder="Status Date Id">
            {!! $errors->first('status_date_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="day" class="form-label">FECHA Y HORA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fa fa-calendar-check text-primary"></i></span>

            <input type="datetime-local" name="date" class="form-control @error('date') is-invalid @enderror"
             value="{{ old('date', $controlDate?->date) }}" id="date" placeholder="Date">
            {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $controlDate?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
{{-- 
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div> --}}

    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
          Registrar cita</button>
      </div>
</div>