<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">Nombre del paquete</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                        class="fas fa-th-list text-primary"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $surgeryPack?->name) }}" id="name" placeholder="Nombre del paquete">
                {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total" class="form-label">Total final del paquete</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                        class="fas fa-th-list text-primary"></i></span>
                <input type="text" name="total" class="form-control @error('total') is-invalid @enderror"
                    value="{{ old('total', $surgeryPack?->total) }}" id="total" placeholder="$">
                {!! $errors->first('total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="catheterization_price" class="form-label">Precio Cateterización y fluido I.V</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="catheterization_price"
                            class="form-control @error('catheterization_price') is-invalid @enderror"
                            value="{{ old('catheterization_price', $surgeryPack?->catheterization_price) }}"
                            id="catheterization_price" placeholder="$">
                        {!! $errors->first(
                            'catheterization_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="preanesthetic_price" class="form-label">Precio Manejo Anestesico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="preanesthetic_price"
                            class="form-control @error('preanesthetic_price') is-invalid @enderror"
                            value="{{ old('preanesthetic_price', $surgeryPack?->preanesthetic_price) }}"
                            id="preanesthetic_price" placeholder="$">
                        {!! $errors->first(
                            'preanesthetic_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="monitoring_price" class="form-label">Precio Monitorización tranquirurgica</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="monitoring_price"
                            class="form-control @error('monitoring_price') is-invalid @enderror"
                            value="{{ old('monitoring_price', $surgeryPack?->monitoring_price) }}" id="monitoring_price"
                            placeholder="$">
                        {!! $errors->first(
                            'monitoring_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="surgical_clothing_price" class="form-label">Precio ropa quirurgica</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="surgical_clothing_price"
                            class="form-control @error('surgical_clothing_price') is-invalid @enderror"
                            value="{{ old('surgical_clothing_price', $surgeryPack?->surgical_clothing_price) }}"
                            id="surgical_clothing_price" placeholder="$">
                        {!! $errors->first(
                            'surgical_clothing_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="preparations_price" class="form-label">Precio preparación prequirurgica</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="preparations_price"
                            class="form-control @error('preparations_price') is-invalid @enderror"
                            value="{{ old('preparations_price', $surgeryPack?->preparations_price) }}"
                            id="preparations_price" placeholder="$">
                        {!! $errors->first(
                            'preparations_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-2 mb20">
                    <label for="observation_price" class="form-label">Precio observación postquirurgica</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1"> <i
                                class="fas fa-th-list text-primary"></i></span>
                        <input type="text" name="observation_price"
                            class="form-control @error('observation_price') is-invalid @enderror"
                            value="{{ old('observation_price', $surgeryPack?->observation_price) }}"
                            id="observation_price" placeholder="$">
                        {!! $errors->first(
                            'observation_price',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
            Guardar registro</button>
    </div>
</div>
