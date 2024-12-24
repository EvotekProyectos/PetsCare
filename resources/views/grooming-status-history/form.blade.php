<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $groomingStatusHistory?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="grooming_status_id" class="form-label">{{ __('Grooming Status Id') }}</label>
            <input type="text" name="grooming_status_id" class="form-control @error('grooming_status_id') is-invalid @enderror" value="{{ old('grooming_status_id', $groomingStatusHistory?->grooming_status_id) }}" id="grooming_status_id" placeholder="Grooming Status Id">
            {!! $errors->first('grooming_status_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>