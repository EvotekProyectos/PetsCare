<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="voucher_id" class="form-label">{{ __('Voucher Id') }}</label>
            <input type="text" name="voucher_id" class="form-control @error('voucher_id') is-invalid @enderror" value="{{ old('voucher_id', $voucherProduct?->voucher_id) }}" id="voucher_id" placeholder="Voucher Id">
            {!! $errors->first('voucher_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="product_id" class="form-label">{{ __('Product Id') }}</label>
            <input type="text" name="product_id" class="form-control @error('product_id') is-invalid @enderror" value="{{ old('product_id', $voucherProduct?->product_id) }}" id="product_id" placeholder="Product Id">
            {!! $errors->first('product_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="requested_quantity" class="form-label">{{ __('Requested Quantity') }}</label>
            <input type="text" name="requested_quantity" class="form-control @error('requested_quantity') is-invalid @enderror" value="{{ old('requested_quantity', $voucherProduct?->requested_quantity) }}" id="requested_quantity" placeholder="Requested Quantity">
            {!! $errors->first('requested_quantity', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="unit_of_measure" class="form-label">{{ __('Unit Of Measure') }}</label>
            <input type="text" name="unit_of_measure" class="form-control @error('unit_of_measure') is-invalid @enderror" value="{{ old('unit_of_measure', $voucherProduct?->unit_of_measure) }}" id="unit_of_measure" placeholder="Unit Of Measure">
            {!! $errors->first('unit_of_measure', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>