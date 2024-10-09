<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">

            <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="name" class="form-label">reception_id</label>
                    <div class="input-group mb-3">
                        <input type="text" name="reception_id"
                            class="form-control @error('reception_id') is-invalid @enderror"
                            value="{{ $reception->id }}" id="reception_id"
                            placeholder="reception_id">
                        {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="name" class="form-label">VET</label>
                    <div class="input-group mb-3">
                        <input type="text" name="veterinarian_id"
                            class="form-control @error('veterinarian_id') is-invalid @enderror"
                            value="{{ Auth::user()->id }}" id="veterinarian_id"
                            placeholder="veterinarian_id">
                        {!! $errors->first('veterinarian_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

        <div class="col-md-6">
            <div class="form-group mb-2">
                <label for="name" class="form-label">FECHA</label>
                <div class="input-group mb-2">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="lucide--calendar-clock "></span>
                    </span>
                    <input type="datetime-local" name="date"
                        class="form-control @error('date') is-invalid @enderror"
                        value="{{ old('date', $prescription?->date) }}" id="date"
                        placeholder="Date">
                    {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-2">
                <label for="name" class="form-label">DIAGNOSTICO</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <img src="{{ asset('img/consulta.png') }}" alt="Foto Mascota" id="preview" class="img-fixed"
                            style="width: 20px; height: 20px; object-fit: cover; ">
                    </span>
                    <input type="text" name="diagnosis"
                        class="form-control @error('diagnosis') is-invalid @enderror"
                        value="{{ old('diagnosis', $prescription?->diagnosis) }}" id="diagnosis"
                        placeholder="Diagnóstico">
                    {!! $errors->first('diagnosis', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>

    
    <div class="d-flex justify-content-between align-items-center">
        <h5 class=" text-uppercase">
        MEDICAMENTOS
        </h5>
    </div>

    <div class="row">
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="name" class="form-label">NOMBRE , PRESENTACIÓN, CANTIDAD Y FORMA DE ADMINISTRACIÓN</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="icon-park-twotone--medicine-bottle-one"></span>
                </span>
                <textarea name="medicine"
                    class="form-control @error('medicine') is-invalid @enderror"
                    id="medicine"
                    placeholder="Nombres de los medicamentos, la presentación, cantidad y forma de administración."
                    rows="4">{{ old('medicine', $prescription?->medicine) }}</textarea>
                {!! $errors->first('medicine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label for="name" class="form-label">OBSERVACIONES</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="icon-park-twotone--medicine-bottle-one"></span>
                    </span>
                    <textarea name="observations"
                        class="form-control @error('observations') is-invalid @enderror"
                        id="observations"
                        placeholder="Observaciones del emisor"
                        rows="4">{{ old('observations', $prescription?->observations) }}</textarea>
                    {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>


    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Guardar receta</button>
    </div>
</div>