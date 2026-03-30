<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="folio" class="form-label">{{ __('Folio') }}</label>
            <input type="text" name="folio" class="form-control @error('folio') is-invalid @enderror" value="{{ old('folio', $voucher?->folio) }}" id="folio" placeholder="Folio">
            {!! $errors->first('folio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $voucher?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $voucher?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vet_id" class="form-label">{{ __('Vet Id') }}</label>
            <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror" value="{{ old('vet_id', $voucher?->vet_id) }}" id="vet_id" placeholder="Vet Id">
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="issuer_id" class="form-label">{{ __('Issuer Id') }}</label>
            <input type="text" name="issuer_id" class="form-control @error('issuer_id') is-invalid @enderror" value="{{ old('issuer_id', $voucher?->issuer_id) }}" id="issuer_id" placeholder="Issuer Id">
            {!! $errors->first('issuer_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="issued_at" class="form-label">{{ __('Issued At') }}</label>
            <input type="text" name="issued_at" class="form-control @error('issued_at') is-invalid @enderror" value="{{ old('issued_at', $voucher?->issued_at) }}" id="issued_at" placeholder="Issued At">
            {!! $errors->first('issued_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="generated_document" class="form-label">{{ __('Generated Document') }}</label>
            <input type="text" name="generated_document" class="form-control @error('generated_document') is-invalid @enderror" value="{{ old('generated_document', $voucher?->generated_document) }}" id="generated_document" placeholder="Generated Document">
            {!! $errors->first('generated_document', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="warehouse_observations" class="form-label">{{ __('Warehouse Observations') }}</label>
            <input type="text" name="warehouse_observations" class="form-control @error('warehouse_observations') is-invalid @enderror" value="{{ old('warehouse_observations', $voucher?->warehouse_observations) }}" id="warehouse_observations" placeholder="Warehouse Observations">
            {!! $errors->first('warehouse_observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cancellation_reason" class="form-label">{{ __('Cancellation Reason') }}</label>
            <input type="text" name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" value="{{ old('cancellation_reason', $voucher?->cancellation_reason) }}" id="cancellation_reason" placeholder="Cancellation Reason">
            {!! $errors->first('cancellation_reason', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="rejection_reason" class="form-label">{{ __('Rejection Reason') }}</label>
            <input type="text" name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" value="{{ old('rejection_reason', $voucher?->rejection_reason) }}" id="rejection_reason" placeholder="Rejection Reason">
            {!! $errors->first('rejection_reason', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>