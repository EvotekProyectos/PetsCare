@push('styles')
    <link rel="stylesheet" href="{{ asset('css/budgets/form.css') }}">
@endpush
<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2 ">
                    <label for="pet_id" class="form-label">Mascota</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="ic--twotone-pets"></span>
                        </span>
                        <select name="pet_id" class="form-control select2 @error('pet_id') is-invalid @enderror"
                            id="pet_id" style="width: 90%;">
                            <option value="">Selecciona la mascota</option>
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}"
                                    {{ old('pet_id', $budget?->pet_id) == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->name }} #{{ $pet->number_chip }}
                                </option>
                            @endforeach
                        </select>
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

        <div class="row mb-2 mb20">
            
            <div class="col-6">
                <div class="form-group mb-2 mb20">
                    <label for="others" class="form-label">Otros</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <input type="text" name="others" class="form-control @error('others') is-invalid @enderror"
                            value="{{ old('others', $budget?->others) }}" id="others" placeholder="Ottos">
                        {!! $errors->first('others', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="row mb-2 mb20">
        <div class="col-6">
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
        </div>
        <div class="col-6">
            <div class="form-group mb-2 mb20">
                <label for="vet_id" class="form-label">Médico Responsable</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="maki--doctor"></span>
                    </span>
                    <select name="vet_id" class="form-control @error('vet_id') is-invalid @enderror" id="vet_id">
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

    </div>



</div>
<div class="col-12 mt-2 d-flex justify-content-end">
    <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
        <i class="fas fa-plus"></i>
        Registrar</button>
</div>
</div>
