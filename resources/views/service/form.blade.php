<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="vaccine" class="form-label">{{ __('Vacuna') }}</label>
            <input type="text" name="vaccine" class="form-control @error('vaccine') is-invalid @enderror" value="{{ old('vaccine', $service?->vaccine) }}" id="vaccine" placeholder="Vaccine">
            {!! $errors->first('vaccine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="deworming_internal" class="form-label">{{ __('Desparasitación Interna') }}</label>
            <input type="text" name="deworming_internal" class="form-control @error('deworming_internal') is-invalid @enderror" value="{{ old('deworming_internal', $service?->deworming_internal) }}" id="deworming_internal" placeholder="Deworming Internal">
            {!! $errors->first('deworming_internal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="deworming_external" class="form-label">{{ __('Desparasitación Externa') }}</label>
            <input type="text" name="deworming_external" class="form-control @error('deworming_external') is-invalid @enderror" value="{{ old('deworming_external', $service?->deworming_external) }}" id="deworming_external" placeholder="Deworming External">
            {!! $errors->first('deworming_external', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i> Guardar registro</button>
    </div>
</div>