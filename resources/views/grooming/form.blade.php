<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden >
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror"
                value="{{ old('reception_id', $reception->id) }}" id="reception_id" placeholder="Reception Id">
            </div>
                {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="row">
            <div class="col-6 form-group mb-2 mb20">
                <label for="service_id" class="form-label">SERVICIO/S A REALIZAR</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="gravity-ui--scissors"></span></span>
                    <select name="service_id" class="form-control @error('service_id') is-invalid @enderror"
                        id="service_id">
                        <option value=""> Selecciona el servico a registrar</option>
                        @foreach ($products as $product)
                            {{-- @if ($product->product_classification_id == 4) --}}
                            <option value="{{ $product->ARTICULO_ID }}" name="service_id"
                                {{ old('service_id', $grooming?->service_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                            {{-- @endif  --}}
                        @endforeach
                    </select>
                </div>
                {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col-6 form-group mb-2 mb20">
                <label for="notes" class="form-label">NOTAS EXTRA</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span></span>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror"
                        value="{{ old('notes', $grooming?->notes) }}" id="notes" placeholder="Notas">
                </div>
                {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>


    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Guardar</button>
    </div>
</div>
