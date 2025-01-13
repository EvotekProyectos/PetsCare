<div class="row padding-1 p-1">
    <div class="row">

        <div class="form-group mb-2 mb20" hidden>
            <label for="budget_id_lab" class="form-label">{{ __('Budget Id') }}</label>
            <input type="text" name="budget_id" class="form-control @error('budget_id') is-invalid @enderror"
                value="{{ old('budget_id', $budget?->id) }}" id="budget_id_lab" placeholder="Budget Id">
            {!! $errors->first('budget_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="col-5 form-group mb-2 mb20">
            <label for="lab_id" class="form-label">LABORATORIOS</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="lab_id" class="form-control @error('lab_id') is-invalid @enderror"
                    id="lab_id" onchange="getLabprice(this.value)">
                    <option value=""> Selecciona el servico a registrar</option>
                    @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 4) --}}
                        <option value="{{ $product->ARTICULO_ID }}" name="lab_id"
                            {{ old('lab_id', $budgetDetail?->lab_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                            {{ $product->NOMBRE }}</option>
                        {{-- @endif  --}}
                    @endforeach
                </select>
            </div>
            {!! $errors->first('lab_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="col-3 form-group mb-2 mb20">
            <div class="form-group mb-2 mb20">
                <label for="pricelab" class="form-label">Precio</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="f7--money-dollar"></span>
                    </span>
                    <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $budgetDetail?->price) }}" id="pricelab" placeholder="Precio">
                </div>
                {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="col-3 form-group mb-2 mb20">
            <div class="form-group mb-2 mb20">
                <label for="noteslab" class="form-label">Notas</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror"
                        value="{{ old('notes', $budgetDetail?->notes) }}" id="noteslab" placeholder="Notas">
                </div>
                {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

        </div>
        <div class="col-1 form-group mb-2 mb20 ">
            {{-- <div class="col-12 mt-2 d-flex justify-content-end"> --}}
                <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                    <i class="fas fa-plus"></i>
                    Añadir</button>
            {{-- </dikv> --}}
        </div>

    </div>

</div>
