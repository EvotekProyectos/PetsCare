<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fas fa-list text-primary"></i></span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $petsStatus?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">Descripción </label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fas fa-list text-primary"></i></span>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $petsStatus?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Color') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fas fa-list text-primary"></i></span>
            <input type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $petsStatus?->color) }}" id="color" placeholder="Color">
            {!! $errors->first('color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i> Guardar registro</button>
    </div>
</div>