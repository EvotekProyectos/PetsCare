<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 ">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fas fa-venus-mars text-primary"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $genre?->name) }}" id="name" placeholder="Nombre">
                {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i> Guardar registro</button>
    </div>
</div>