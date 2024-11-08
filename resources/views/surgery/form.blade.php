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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="surgery_date" class="form-label">FECHA DE REALIZACIÓN</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock "></span>
                        </span>
                        <input type="datetime-local" name="surgery_date"
                            class="form-control @error('surgery_date') is-invalid @enderror"
                            value="{{ old('surgery_date', $surgery?->surgery_date) }}" id="surgery_date"
                            placeholder="Surgery Date">
                        {!! $errors->first('surgery_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="surgery_type_id" class="form-label">TIPO DE CIRUGÍA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="surgery_type_id"
                            class="form-control @error('surgery_type_id') is-invalid @enderror" id="surgery_type_id">
                            <option value="">Selecciona el tipo de cirugía</option>
                            @foreach ($products as $product)
                                @if ($product->product_classification_id == 1)
                                    <option value="{{ $product->id }}" name="surgery_type_id"
                                        {{ old('surgery_type_id', $surgery?->surgery_type_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first(
                        'surgery_type_id',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2 mb20">
                    <label for="surgery_description" class="form-label">DESCRIPCIÓN QUIRURGÍCA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="surgery_description"class="form-control @error('surgery_description') is-invalid @enderror"
                            id="surgery_description" placeholder="Descripción de la cirugía realizada"  rows="3" >{{ old('surgery_description', $surgery?->surgery_description) }} </textarea>
                    
                    {!! $errors->first(
                        'surgery_description',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="preanesthetic" class="form-label">PREANESTÉSICO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <input type="text" name="preanesthetic"
                            class="form-control @error('preanesthetic') is-invalid @enderror"
                            value="{{ old('preanesthetic', $surgery?->preanesthetic) }}" id="preanesthetic"
                            placeholder="Preanestésico">

                        {!! $errors->first(
                            'preanesthetic',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="anesthetic" class="form-label">ANESTÉSICO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <input type="text" name="anesthetic"
                            class="form-control @error('anesthetic') is-invalid @enderror"
                            value="{{ old('anesthetic', $surgery?->anesthetic) }}" id="anesthetic"
                            placeholder="Anestésico">
                    </div>
                    {!! $errors->first('anesthetic', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="other_medicines" class="form-label">OTROS MEDICAMENTOS</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <input type="text" name="other_medicines"
                            class="form-control @error('other_medicines') is-invalid @enderror"
                            value="{{ old('other_medicines', $surgery?->other_medicines) }}" id="other_medicines"
                            placeholder="Otros medicamentos ">
                    </div>
                    {!! $errors->first(
                        'other_medicines',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="treatment" class="form-label">TRATAMIENTO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="treatment" class="form-control @error('treatment') is-invalid @enderror" rows="2" id="treatment"> {{ old('treatment', $surgery?->treatment) }}</textarea>
                    </div>
                    {!! $errors->first('treatment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="observations" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="observations"class="form-control @error('observations') is-invalid @enderror"
                         rows="2" id="observations_surgery"> {{ old('observations', $surgery?->observations) }} </textarea>
                    </div>
                    {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2 mb20">
                    <label for="complications" class="form-label">COMPLICACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="complications" class="form-control @error('complications') is-invalid @enderror" rows="2"
                            id="complications"> {{ old('complications', $surgery?->complications) }} </textarea>
                    </div>
                    {!! $errors->first(
                        'complications',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
        </div>

            <div class="form-group mb-2 mb20" hidden>
                <label for="vet_id" class="form-label">{{ __('Vet Id') }}</label>
                <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                    value="{{ old('vet_id', $surgery?->vet_id) }}" id="vet_id" placeholder="Vet Id">
                {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>

    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary  btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Registrar Cirugía</button>
    </div>
</div>
