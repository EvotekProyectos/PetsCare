<div class="row padding-1 p-1">
    <div class="container">
        <div class="row">
            <!-- First Row -->
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">Nombre</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--family text-primary"></span>
                        </span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $family?->name) }}" id="name"
                            placeholder="Nombre de la familia/propietario">
                        {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="phone" class="form-label">Telefono</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="ic--sharp-phone text-primary"></span>
                        </span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $family?->phone) }}" id="phone"
                            placeholder="Telefono identificador">
                        {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="email" class="form-label">Correo electronico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="ic--outline-alternate-email text-primary"></span>
                        </span>
                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $family?->email) }}" id="email" placeholder="Email">
                        {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Second Row -->
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="address" class="form-label">Dirección</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="pajamas--location text-primary"></span>
                        </span>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', $family?->address) }}" id="address" placeholder="Dirección">
                        {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="contact_name" class="form-label">Contacto Autorizado</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="mdi--person-check text-primary"></span>
                        </span>
                        <input type="text" name="contact_name"
                            class="form-control @error('contact_name') is-invalid @enderror"
                            value="{{ old('contact_name', $family?->contact_name) }}" id="contact_name"
                            placeholder="Nombre de Contacto Autorizado">
                        {!! $errors->first('contact_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="contact_number" class="form-label">Teléfono Contacto Autorizado</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="mdi--phone-check text-primary"></span>
                        </span>
                        <input type="text" name="contact_number"
                            class="form-control @error('contact_number') is-invalid @enderror"
                            value="{{ old('contact_number', $family?->contact_number) }}" id="contact_number"
                            placeholder="Teléfono Contacto Autorizado">
                        {!! $errors->first(
                            'contact_number',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Third Row -->
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="fam_classification_id" class="form-label">Clasificación</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--family text-primary"></span>
                        </span>
                        <select name="fam_classification_id"
                            class="form-control @error('fam_classification_id') is-invalid @enderror"
                            id="fam_classification_id">
                            <option value="">Seleccione una clasificación</option>
                            @foreach ($FamClassifications as $classification)
                                <option value="{{ $classification->id }}" name="fam_classification_id"
                                    {{ old('fam_classification_id', $family?->fam_classification_id) == $classification->id ? 'selected' : '' }}>
                                    {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first(
                            'fam_classification_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
            Guardar familia</button>
    </div>
</div>
