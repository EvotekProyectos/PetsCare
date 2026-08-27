<style>
    .select2-container .select2-selection--single {
        height: 2rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    /* IMPORTANTE: evitar que el icono se vaya arriba */
    .input-group {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        width: 100%;
    }

    /* El bloque azul crece con el Select2 */
    .input-group .input-group-text {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex: 0 0 auto;
    }

    /* Select2 ocupa todo el espacio restante */
    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
        min-width: 0;
    }

    /* Select2 múltiple */
    .input-group .select2-selection--multiple {
        min-height: 2rem;
        height: auto;
        border: 1px solid #ced4da;
        border-radius: 0 0.25rem 0.25rem 0;
    }

    /* Elementos seleccionados */
    .input-group .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.2rem;
        margin: 0;
        padding: 0.2rem 0.4rem;
    }

    .input-group .select2-selection--multiple .select2-selection__choice {
        margin: 0;
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
                    value="{{ old('reception_id', $reception->id) }}" id="reception_id" placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group mb-2">
                    <label for="service_id" class="form-label">SERVICIO/S DE GROOMING</label>

                    <div class="input-group">
                        <span class="input-group-text bg-primary-subtle">
                            <span class="gravity-ui--scissors"></span>
                        </span>

                        <select name="service_id[]" multiple
                            class="form-control @error('service_id') is-invalid @enderror" id="service_id">
                            @foreach ($products as $product)
                                <option value="{{ $product->ARTICULO_ID }}">{{ $product->NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>

                    {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            {{-- <div class="col-6 form-group mb-2 mb20">
                <label for="notes" class="form-label">NOTAS EXTRA</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span></span>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror"
                        value="{{ old('notes', $grooming?->notes) }}" id="notes" placeholder="Notas">
                </div>
                {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div> --}}
        </div>


    </div>
    {{-- <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Guardar</button>
    </div> --}}
</div>
