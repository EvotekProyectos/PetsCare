@push('styles')
    <link rel="stylesheet" href="{{ asset('css/budgets/form.css') }}">
@endpush
<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2 mb20">
                    <label for="pet_id" class="form-label">Mascota</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="ic--twotone-pets"></span>
                        </span>
                        <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror"
                            value="{{ old('pet_id', $budget?->pet_id) }}" id="pet_id" placeholder="Mascota">
                        {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2 mb20">
                    <label for="date" class="form-label">Fecha de creación</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock"></span>
                        </span>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', $budget?->date) }}" id="date" placeholder="Date">
                        {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="surgery_pack_id" class="form-label">Paquete de Cirugia</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="vaadin--lines-list"></span>
                </span>
                <select name="surgery_pack_id" class="form-control @error('surgery_pack_id') is-invalid @enderror"
                    id="surgery_pack_id">
                    <option value=""> Selecciona el paquete</option>
                    @foreach ($packs as $pack)
                        <option value="{{ $pack->id }}" name="surgery_pack_id"
                            {{ old('surgery_pack_id', $budget?->surgery_pack_id) == $pack->id ? 'selected' : '' }}>
                            {{ $pack->name }} ${{ $pack->total }}</option>
                    @endforeach
                </select>
                {!! $errors->first(
                    'surgery_pack_id',
                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                ) !!}
            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="col-6">
                <div class="form-group mb-2 mb20">
                    <label for="procedure" class="form-label">Nombre del procedimiento a realizar</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <input type="text" name="procedure"
                            class="form-control @error('procedure') is-invalid @enderror"
                            value="{{ old('procedure', $budget?->procedure) }}" id="procedure"
                            placeholder="Procedimiento">
                        {!! $errors->first('procedure', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2 mb20">
                    <label for="procedure_price" class="form-label">Precio del Procedimiento</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="procedure_price"
                            class="form-control @error('procedure_price') is-invalid @enderror"
                            value="{{ old('procedure_price', $budget?->procedure_price) }}" id="procedure_price"
                            placeholder="Procedimiento">
                        {!! $errors->first(
                            'procedure_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="biometric" class="form-label">Biometria Hematica</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="biometric_yes" name="biometric" value="0"
                        {{ old('biometric', $budget?->biometric) == 0 ? 'checked' : '' }}>
                    <label for="biometric_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="biometric_no" name="biometric" value="1"
                        {{ old('biometric', $budget?->biometric) == 1 ? 'checked' : '' }}>
                    <label for="biometric_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="biometric_price"
                            class="form-control @error('biometric_price') is-invalid @enderror"
                            value="{{ old('biometric_price', $budget?->biometric_price) }}" id="biometric_price"
                            placeholder="biometria hematica">
                        {!! $errors->first(
                            'biometric_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="biometric" class="form-label">{{ __('Biometric') }}</label>
            <input type="text" name="biometric" class="form-control @error('biometric') is-invalid @enderror"
                value="{{ old('biometric', $budget?->biometric) }}" id="biometric" placeholder="Biometric">
            {!! $errors->first('biometric', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="chemistry" class="form-label">Quimica Sanguinea</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="chemistry_10" name="chemistry" value="10"
                        {{ old('chemistry', $budget?->chemistry) == 10 ? 'checked' : '' }}>
                    <label for="chemistry_10" class="radio-label shadow border-0">
                        </span>10</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="chemistry_15" name="chemistry" value="15"
                        {{ old('chemistry', $budget?->chemistry) == 15 ? 'checked' : '' }}>
                    <label for="chemistry_15" class="radio-label shadow border-0">
                        </span>15</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="chemistry_17" name="chemistry" value="17"
                        {{ old('chemistry', $budget?->chemistry) == 17 ? 'checked' : '' }}>
                    <label for="chemistry_17" class="radio-label shadow border-0">
                        </span>17</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="chemistry_24" name="chemistry" value="24"
                        {{ old('chemistry', $budget?->chemistry) == 24 ? 'checked' : '' }}>
                    <label for="chemistry_24" class="radio-label shadow border-0">
                        </span>24</label>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="chemistry_price"
                            class="form-control @error('chemistry_price') is-invalid @enderror"
                            value="{{ old('chemistry_price', $budget?->chemistry_price) }}" id="chemistry_price"
                            placeholder="Quimica sanguinea">
                        {!! $errors->first(
                            'chemistry_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="nodulectomy" class="form-label">Nodulectomia</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="nodulectomy_yes" name="nodulectomy" value="0"
                        {{ old('nodulectomy', $budget?->nodulectomy) == 0 ? 'checked' : '' }}>
                    <label for="nodulectomy_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="nodulectomy_no" name="nodulectomy" value="1"
                        {{ old('nodulectomy', $budget?->nodulectomy) == 1 ? 'checked' : '' }}>
                    <label for="nodulectomy_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <input type="text" name="nodulectomy_price"
                            class="form-control @error('nodulectomy_price') is-invalid @enderror"
                            value="{{ old('nodulectomy_price', $budget?->nodulectomy_price) }}"
                            id="nodulectomy_price" placeholder="Sitio de la cirugia">
                        {!! $errors->first(
                            'nodulectomy_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="histopathology" class="form-label">Histopatologia</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="histopathology_yes" name="histopathology" value="0"
                        {{ old('histopathology', $budget?->histopathology) == 0 ? 'checked' : '' }}>
                    <label for="histopathology_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="histopathology_no" name="histopathology" value="1"
                        {{ old('histopathology', $budget?->histopathology) == 1 ? 'checked' : '' }}>
                    <label for="histopathology_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="histopathology_price"
                            class="form-control @error('histopathology_price') is-invalid @enderror"
                            value="{{ old('histopathology_price', $budget?->histopathology_price) }}"
                            id="histopathology_price" placeholder="Histopatologia">
                        {!! $errors->first(
                            'histopathology_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="xrays" class="form-label">Radiografias</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="xrays_yes" name="xrays" value="0"
                        {{ old('xrays', $budget?->xrays) == 0 ? 'checked' : '' }}>
                    <label for="xrays_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="xrays_no" name="xrays" value="1"
                        {{ old('xrays', $budget?->xrays) == 1 ? 'checked' : '' }}>
                    <label for="xrays_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <input type="text" name="xrays_price"
                            class="form-control @error('xrays_price') is-invalid @enderror"
                            value="{{ old('xrays_price', $budget?->xrays_price) }}" id="xrays_price"
                            placeholder="Proyección">
                        {!! $errors->first('xrays_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="collar" class="form-label">Collar Isabelino</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="collar_yes" name="collar" value="0"
                        {{ old('collar', $budget?->collar) == 0 ? 'checked' : '' }}>
                    <label for="collar_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="collar_no" name="collar" value="1"
                        {{ old('collar', $budget?->collar) == 1 ? 'checked' : '' }}>
                    <label for="collar_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="collar_price"
                            class="form-control @error('collar_price') is-invalid @enderror"
                            value="{{ old('collar_price', $budget?->collar_price) }}" id="collar_price"
                            placeholder="Collar Isabelino">
                        {!! $errors->first('collar_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="body" class="form-label">Body de cobre</label>
                </div>

                <div class="col-md-1">
                    <input type="radio" id="body_yes" name="body" value="0"
                        {{ old('body', $budget?->body) == 0 ? 'checked' : '' }}>
                    <label for="body_yes" class="radio-label shadow border-0">
                        </span>Si</label>
                </div>
                <div class="col-md-1">
                    <input type="radio" id="body_no" name="body" value="1"
                        {{ old('body', $budget?->body) == 1 ? 'checked' : '' }}>
                    <label for="body_no" class="radio-label shadow border-0">
                        </span>No</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="f7--money-dollar"></span>
                        </span>
                        <input type="text" name="body_price"
                            class="form-control @error('body_price') is-invalid @enderror"
                            value="{{ old('body_price', $budget?->body_price) }}" id="body_price"
                            placeholder="Body de cobre">
                        {!! $errors->first('body_price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-2 mb20">
            <div class="row form-group mb-2 mb20">
                <div class="col-2">
                    <label for="others" class="form-label">Otros</label>
                </div>

                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <input type="text" name="others"
                            class="form-control @error('others') is-invalid @enderror"
                            value="{{ old('others', $budget?->others) }}" id="others" placeholder="Ottos">
                        {!! $errors->first('others', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total" class="form-label">{{ __('Total') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                    <span class="f7--money-dollar"></span>
                </span>
            <input type="text" name="total" class="form-control @error('total') is-invalid @enderror"
                value="{{ old('total', $budget?->total) }}" id="total" placeholder="Total">
            {!! $errors->first('total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vet_id" class="form-label">Médico Responsable</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="maki--doctor"></span>
                </span>
                <select name="vet_id"
                    class="form-control @error('vet_id') is-invalid @enderror" id="vet_id">
                    <option value=""> Selecciona M.V.Z</option>

                    @foreach ($vets as $vet)
                        <option value="{{ $vet->id }}" name="vet_id"
                            {{ old('vet_id', $budget?->vet_id) == $vet->id ? 'selected' : '' }}>
                            {{ $vet->name }}</option>
                    @endforeach
                </select>
            {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Registrar</button>
    </div>
</div>
