<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 ">
            <label for="name" class="form-label">NOMBRE</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $cubicle?->name) }}" id="name" placeholder="Nombre/número de cubículo">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

        <div class="form-group mb-2 mb20">
            <label for="cubicle_type_id" class="form-label">TIPO DE PENSIÓN</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
            <select name="cubicle_type_id" class="form-control @error('cubicle_type_id') is-invalid @enderror" 
            id="cubicle_type_id" placeholder="Cubicle Type Id">
             <option value=""> Selecciona el tipo de pensión al que pertenece </option>
            @foreach ($c_types as $c_type)
                 <option  value="{{ $c_type->id }}" name="cubicle_type_id"
                    {{ old('cubicle_type_id', $cubicle?->cubicle_type_id)== $c_type->id ? 'selected' : '' }}>
                    {{ $c_type->name }}</option>
            @endforeach
            </select>
        </div>
            {!! $errors->first('cubicle_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="state" class="form-label">{{ __('State') }}</label>
            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $cubicle?->state) }}" id="state" placeholder="State">
            {!! $errors->first('state', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>


    {{-- <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div> --}}

    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
           GUARDAR</button>
    </div>
</div>