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
        
        <div class="form-group mb-2 ">
            <label for="name" class="form-label">Nombre servicio:</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $cubicleType?->name) }}" id="name" placeholder="Nombre">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 ">
            <label for="id_microsip" class="form-label">Nombre del servicio Microsip:</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
                <select name="id_microsip" class="form-control select2 @error('id_microsip') is-invalid @enderror" 
                        id="id_microsip">
                            <option value="">Selecciona el tipo de servicio</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->ARTICULO_ID}}" name="id_microsip"
                                {{ old('id_microsip', $cubicleType?->id_microsip) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                    @endforeach
                        </select>
            {!! $errors->first('id_microsip', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i> Guardar </button>
    </div>
</div>