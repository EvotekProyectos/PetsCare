<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $receptionStatusHistory?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="attention_status_id" class="form-label">{{ __('Attention Status Id') }}</label>
            <input type="text" name="attention_status_id" class="form-control @error('attention_status_id') is-invalid @enderror" value="{{ old('attention_status_id', $receptionStatusHistory?->attention_status_id) }}" id="attention_status_id" placeholder="Attention Status Id">
            {!! $errors->first('attention_status_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>