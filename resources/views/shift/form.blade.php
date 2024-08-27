<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 ">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fa fa-clock text-primary"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $shift?->name) }}" id="name" placeholder="Nombre">
                {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 ">
            <label for="begin" class="form-label">Inicio del turno</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fa fa-clock text-primary"></i></span>
            <input type="time" name="begin" class="form-control @error('begin') is-invalid @enderror" value="{{ old('begin', $shift?->begin) }}" id="begin" placeholder="Begin">
            {!! $errors->first('begin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 ">
            <label for="end" class="form-label">Termino del turno</label>
            <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fa fa-clock text-primary"></i></span>
            <input type="time" name="end" class="form-control @error('end') is-invalid @enderror" value="{{ old('end', $shift?->end) }}" id="end" placeholder="End">
            {!! $errors->first('end', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i> Guardar registro</button>
    </div>
</div>