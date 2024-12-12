<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{ old('reception_id', $appointmentService?->reception_id) }}" id="reception_id_img"
                    placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20" hidden>
            <label for="lab_type_id" class="form-label">Laboratorio</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="lab_type_id" class="form-control @error('lab_type_id') is-invalid @enderror"
                    id="lab_type_id_1">
                    <option value=""> Selecciona el laboratorio a registrar</option>
                    @foreach ($products as $product)
                        @if ($product->product_classification_id == 2)
                            <option value="{{ $product->ARTICULO_ID }}" name="lab_type_id"
                                {{ old('lab_type_id', $appointmentService?->lab_type_id) == $product->ARTICULO_ID  ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            {!! $errors->first('lab_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="imaging_type_id" class="form-label">Imagenologia</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <select name="imaging_type_id" class="form-control @error('imaging_type_id') is-invalid @enderror"
                    id="imaging_type_id">
                    <option value=""> Selecciona la imagenologia a registrar</option>
                    @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 3) --}}
                            <option value="{{ $product->ARTICULO_ID }}" name="imaging_type_id"
                                {{ old('imaging_type_id', $appointmentService?->lab_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
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
            <label for="observations" class="form-label">Observaciones</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" rows="3"
                    id="observations_img">{{ old('observations', $appointmentService?->observations) }}</textarea>
            </div>
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20" hidden>
            <label for="vet_id" class="form-label">{{ __('Vet Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                    value="{{ old('vet_id', Auth::user()->id) }}" id="vet_id_img" placeholder="Vet Id">
            </div>
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>
