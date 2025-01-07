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

        <div class="form-group mb-2 mb20">
            <label for="number_ticket" class="form-label">N. TICKET</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
                <input type="text" name="number_ticket" 
       class="form-control @error('number_ticket') is-invalid @enderror"
       value="{{ old('number_ticket', $surgerySchedule?->number_ticket) ?? '' }}" 
       id="number_ticket" 
       placeholder="Número de ticket">

                         
            {!! $errors->first('number_ticket', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
     </div>
        <div class="form-group mb-2 mb20">
            <label for="family_id" class="form-label">FAMILIA/PROPIETARIO</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="fluent-mdl2--family text-primary"></span>
                </span>

           <select name="family_id" class="form-control select2 @error('family_id') is-invalid @enderror"
             id="family_id" placeholder="Family Id" onchange="getpets(this.value)" style="width: 100%;">
             <option value="">Selecciona la familia</option>
                 @foreach ($families as $family)
                                <option value="{{ $family->id }}"
                    {{ old('family_id', $surgerySchedule?->family_id) == $family->id ? 'selected' : '' }}>
                    {{ $family->name }} {{ $family->phone }}</option>
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
                  {{ old('pet_id', $surgerySchedule?->pet_id) == $pet->id ? 'selected' : '' }}>
                  {{ $pet->name }} #{{ $pet->number_chip }}
                </option>
                @endforeach
                </select>
            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>


        <div class="form-group mb-2 mb20">
            <label for="surgical_procedures_type_id" class="form-label">PROCEDIMIENTO QUIRÚRGICO</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
                <select name="surgical_procedures_type_id" class="form-control select2 @error('surgical_procedures_type_id') is-invalid @enderror" 
            id="surgical_procedures_type_id">
            <option value="">Selecciona el tipo de procedimiento a realizar</option>
                            @foreach ($products as $product)
                <option value="{{ $product->ARTICULO_ID}}" name="surgical_procedures_type_id"
            {{ old('surgical_procedures_type_id', $surgerySchedule?->surgical_procedures_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
            {{ $product->NOMBRE }}</option>
            @endforeach
            </select>
            </div>
            {!! $errors->first('surgical_procedures_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="day" class="form-label">DÍA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fa fa-calendar-check text-primary"></i></span>
            <input type="date" name="day" class="form-control @error('day') is-invalid @enderror" 
            value="{{ old('day', $surgerySchedule?->day) }}" id="day" placeholder="Day">
            {!! $errors->first('day', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

        <div class="form-group mb-2 mb20">
            <label for="hour" class="form-label">HORA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fa fa-clock text-primary"></i></span>
            <input type="time" name="hour" class="form-control @error('hour') is-invalid @enderror" 
            value="{{ old('hour', $surgerySchedule?->hour) }}" id="hour" placeholder="Hour">
            {!! $errors->first('hour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

        <div class="form-group mb-2 mb20">
            <label for="veterinarian_id" class="form-label">M.V.Z</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="maki--doctor"></span>
                </span>
                <select name="veterinarian_id" 
                class="form-control @error('veterinarian_id') is-invalid @enderror" 
                id="veterinarian_id">
                <option value="">Selecciona M.V.Z</option>

                @foreach ($users as $user)
                 <option value="{{ $user->id }}" name="veterinarian_id"
                {{ old('veterinarian_id', $surgerySchedule?->veterinarian_id) == $user->id ? 'selected' : '' }}>
                {{ $user->name }}</option>
        @endforeach
        </select>
            {!! $errors->first('veterinarian_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
       </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="status_surgery_id" class="form-label">{{ __('Status Surgery Id') }}</label>
            <input type="text" name="status_surgery_id" class="form-control @error('status_surgery_id') is-invalid @enderror" value="{{ old('status_surgery_id', $surgerySchedule?->status_surgery_id) }}" id="status_surgery_id" placeholder="Status Surgery Id">
            {!! $errors->first('status_surgery_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-12 mt-2 d-flex justify-content-end">
      <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
        Guardar asignación</button>
    </div>
</div>