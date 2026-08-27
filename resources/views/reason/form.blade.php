<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">Nombre del motivo</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i class="fas fa-list text-primary"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $reason?->name) }}" id="name" placeholder="Nombre del motivo">
                {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

        <div class="form-group mb-2 mb20">
            <label for="articulo_id" class="form-label">Concepto en Microsip (cargo base de consulta)</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2"> <i class="fas fa-file-invoice-dollar text-primary"></i></span>
                <select name="articulo_id" class="form-control @error('articulo_id') is-invalid @enderror" id="articulo_id">
                    <option value="">Sin concepto asociado</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->ARTICULO_ID }}"
                            {{ old('articulo_id', $reason?->articulo_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                            {{ $product->NOMBRE }}</option>
                    @endforeach
                </select>
                {!! $errors->first('articulo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary rounded-4 btn-sm">GUARDAR</button>
    </div>
</div>
