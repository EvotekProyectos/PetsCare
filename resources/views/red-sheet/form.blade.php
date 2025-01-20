<style>
    .select2-container .select2-selection--single {
        height: 2rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
    }
</style>
<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{ old('reception_id', $reception?->id) }}" id="reception_id" placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20" hidden>
            
            <label for="day_count" class="form-label">DÍA:</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="day_count" class="form-control @error('day_count') is-invalid @enderror"
                    value="{{ old('day_count', $dayCount) }}" id="day_count" placeholder="Day Count">
            </div>
            {!! $errors->first('day_count', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_type_id" class="form-label">SERVICIOS</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="service_type_id" class="form-control @error('service_type_id') is-invalid @enderror"
                    id="service_type_id">
                    <option value=""> Selecciona el servico a registrar</option>
                    @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 4) --}}
                            <option value="{{ $product->ARTICULO_ID}}" name="service_type_id"
                                {{ old('service_type_id', $redSheet?->service_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                        {{-- @endif  --}}
                    @endforeach
                </select>
            </div>
            {!! $errors->first(
                'service_type_id',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lab_type_id" class="form-label">LABORATORIO</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="lab_type_id" class="form-control @error('lab_type_id') is-invalid @enderror"
                    id="lab_type_id">
                    <option value=""> Selecciona el laboratorio a registrar</option>
                    @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 2) --}}
                            <option value="{{ $product->ARTICULO_ID }}" name="lab_type_id"
                                {{ old('lab_type_id', $redSheet?->lab_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                        {{-- @endif --}}
                    @endforeach
                </select>
            </div>
            {!! $errors->first('lab_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="imaging_type_id" class="form-label">IMAGENOLOGIA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="imaging_type_id" class="form-control @error('imaging_type_id') is-invalid @enderror"
                    id="imaging_type_id">
                    <option value=""> Selecciona la imagenologia a registrar</option>
                    @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 3) --}}
                            <option value="{{ $product->ARTICULO_ID }}" name="imaging_type_id"
                                {{ old('imaging_type_id', $redSheet?->lab_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                        {{-- @endif --}}
                    @endforeach
                </select>
            </div>
            {!! $errors->first(
                'imaging_type_id',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="observations" class="form-label">OBSERVACIONES</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" rows="3"
                    id="observations">{{ old('observations', $redSheet?->observations) }}</textarea>
            </div>
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="vet_id" class="form-label">{{ __('Vet Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                    value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id" placeholder="Vet Id">
            </div>
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Registrar</button>
    </div>
</div>
