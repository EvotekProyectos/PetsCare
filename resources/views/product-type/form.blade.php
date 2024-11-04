<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="product_classification_id" class="form-label">{{ __('Product Classification Id') }}</label>
            <input type="text" name="product_classification_id" class="form-control @error('product_classification_id') is-invalid @enderror" value="{{ old('product_classification_id', $productType?->product_classification_id) }}" id="product_classification_id" placeholder="Product Classification Id">
            {!! $errors->first('product_classification_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="microsip_id" class="form-label">{{ __('Microsip Id') }}</label>
            <input type="text" name="microsip_id" class="form-control @error('microsip_id') is-invalid @enderror" value="{{ old('microsip_id', $productType?->microsip_id) }}" id="microsip_id" placeholder="Microsip Id">
            {!! $errors->first('microsip_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $productType?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $productType?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>