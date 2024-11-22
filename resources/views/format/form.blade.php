<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="format_type_id" class="form-label">{{ __('Format Type Id') }}</label>
            <input type="text" name="format_type_id" class="form-control @error('format_type_id') is-invalid @enderror" value="{{ old('format_type_id', $format?->format_type_id) }}" id="format_type_id" placeholder="Format Type Id">
            {!! $errors->first('format_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $format?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="format_pdf" class="form-label">{{ __('Format Pdf') }}</label>
            <input type="text" name="format_pdf" class="form-control @error('format_pdf') is-invalid @enderror" value="{{ old('format_pdf', $format?->format_pdf) }}" id="format_pdf" placeholder="Format Pdf">
            {!! $errors->first('format_pdf', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>