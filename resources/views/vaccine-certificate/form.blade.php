<div class="row padding-1 p-1">
    <div class="col-md-12">


        <div class="form-group mb-2 mb20">
            <label for="pet_id" class="form-label">{{ __('Pet Id') }}</label>
            <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror"
                value="{{ old('pet_id', $vaccineCertificate?->pet_id) }}" id="pet_id" placeholder="Pet Id">
            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
            <input type="text" name="service_id" class="form-control @error('service_id') is-invalid @enderror"
                value="{{ old('service_id', $vaccineCertificate?->service_id) }}" id="service_id"
                placeholder="Service Id">
            {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <h5>VACUNAS</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock"></span>
                        </span>

                        <input type="date" name="application_date"
                            class="form-control @error('application_date') is-invalid @enderror"
                            value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                            id="application_date" placeholder="Fecha de aplicación">
                        {!! $errors->first(
                            'application_date',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="product" class="form-label">NOMBRE</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--vaccination"></span>
                        </span>

                        <input type="text" name="product" class="form-control @error('product') is-invalid @enderror"
                            value="{{ old('product', $vaccineCertificate?->vaccine) }}" id="product"
                            placeholder="Nombre de la vacuna">
                        {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="lab" class="form-label">LABORATORIO</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--vaccination"></span>
                        </span>

                        <input type="text" name="lab" class="form-control @error('lab') is-invalid @enderror"
                            value="{{ old('lab', $vaccineCertificate?->lab) }}" id="lab"
                            placeholder="Laboratorio">
                        {!! $errors->first('lab', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>


        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group mb-2">
                    <label for="lote" class="form-label">LOTE</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--vaccination"></span>
                        </span>

                        <input type="text" name="lote" class="form-control @error('lote') is-invalid @enderror"
                            value="{{ old('lote', $vaccineCertificate?->lote) }}" id="lote" placeholder="Lote">
                        {!! $errors->first('lote', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-2">
                    <label for="next_application_date" class="form-label">PRÓXIMA APLICACIÓN</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock"></span>
                        </span>

                        <input type="date" name="next_application_date"
                            class="form-control @error('next_application_date') is-invalid @enderror"
                            value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                            id="next_application_date" placeholder="Fecha de próxima vacuna">
                        {!! $errors->first(
                            'next_application_date',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="observations" class="form-label">OBSERVACIONES</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--vaccination"></span>
                        </span>

                        <input type="text" name="observations"
                            class="form-control @error('observations') is-invalid @enderror"
                            value="{{ old('observations', $vaccineCertificate?->observations) }}"
                            id="observations" placeholder="Observaciones">
                        {!! $errors->first(
                            'observations',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                <i class="fas fa-plus"></i>
                REGISTRAR VACUNA</button>
        </div>

        <div class="row">
            <H5 style="margin-top: 20px">DESPARASITACIÓN INTERNAS</H5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="application_date"
                                class="form-control @error('application_date') is-invalid @enderror"
                                value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                                id="application_date" placeholder="Fecha de aplicación">
                            {!! $errors->first(
                                'application_date',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label for="product" class="form-label">PRODUCTO</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--bug-block"></span>
                            </span>

                            <input type="text" name="product"
                                class="form-control @error('product') is-invalid @enderror"
                                value="{{ old('product', $vaccineCertificate?->product) }}"
                                id="product" placeholder="Nombre del producto">
                            {!! $errors->first(
                                'product',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-2 mb20">
                        <label for="dose" class="form-label">DOSIS</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--bug-block"></span>
                            </span>

                            <input type="text" name="dose"
                                class="form-control @error('dose') is-invalid @enderror"
                                value="{{ old('dose', $vaccineCertificate?->dose) }}"
                                id="dose" placeholder="Dosis aplicada">
                            {!! $errors->first(
                                'dose',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>
                <div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-2 mb20">
                                <label for="last_deworming" class="form-label">ÚLTIMA DESPARASITACIÓN</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="lucide--calendar-clock"></span>
                                    </span>


                                    <input type="date" name="last_deworming"
                                        class="form-control @error('last_deworming') is-invalid @enderror"
                                        value="{{ old('last_deworming', $vaccineCertificate?->last_deworming) }}"
                                        id="last_deworming" placeholder="FECHA ULTIMA DESPARASITACION">
                                    {!! $errors->first(
                                        'last_deworming',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>
        
                   
                        <div class="col-md-3">
                            <div class="form-group mb-2 mb20">
                                <label for="next_application_date" class="form-label">PRÓXIMA DESPARASITACIÓN</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="lucide--calendar-clock"></span>
                                    </span>


                                    <input type="date" name="next_application_date"
                                        class="form-control @error('next_application_date') is-invalid @enderror"
                                        value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                                        id="next_application_date" placeholder="Next Internal Date">
                                    {!! $errors->first(
                                        'next_application_date',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2 mb20">
                                <label for="observations" class="form-label">OBSERVACIONES</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="fluent-mdl2--bug-block"></span>
                                    </span>

                                    <input type="text" name="observations"
                                        class="form-control @error('observations') is-invalid @enderror"
                                        value="{{ old('observations', $vaccineCertificate?->observations) }}"
                                        id="observations" placeholder="Observaciones">
                                    {!! $errors->first(
                                        'observations',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                            <i class="fas fa-plus"></i>
                            REGISTRAR DESPARASITACIÓN</button>
                    </div>

                    <H5 style="margin-top: 20px;">DESPARASITACIÓN EXTERNAS</H5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>
            
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="lucide--calendar-clock"></span>
                                    </span>
            
                                    <input type="date" name="application_date"
                                        class="form-control @error('application_date') is-invalid @enderror"
                                        value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                                        id="application_date" placeholder="Fecha de aplicación">
                                    {!! $errors->first(
                                        'application_date',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>

                  
                        <div class="col-md-4">
                            <div class="form-group mb-2 mb20">
                                <label for="product" class="form-label">PRODUCTO</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="fluent-mdl2--bug-block"></span>
                                    </span>

                                    <input type="text" name="product"
                                        class="form-control @error('product') is-invalid @enderror"
                                        value="{{ old('product', $vaccineCertificate?->product) }}"
                                        id="product" placeholder="Nombre del producto">
                                    {!! $errors->first(
                                        'product',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-2 mb20">
                                <label for="dose" class="form-label">DOSIS</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="fluent-mdl2--bug-block"></span>
                                    </span>

                                    <input type="text" name="dose"
                                        class="form-control @error('dose') is-invalid @enderror"
                                        value="{{ old('dose', $vaccineCertificate?->dose) }}"
                                        id="dose" placeholder="Dosis aplicada">
                                    {!! $errors->first(
                                        'dose',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-2 mb20">
                                <label for="last_deworming_date" class="form-label">ÚLTIMA DESPARASITACIÓN</label>

                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                        <span class="lucide--calendar-clock"></span>
                                    </span>

                                    <input type="date" name="last_deworming_date"
                                        class="form-control @error('last_deworming_date') is-invalid @enderror"
                                        value="{{ old('last_deworming_date', $vaccineCertificate?->last_deworming_date) }}"
                                        id="last_deworming_date" placeholder="Last Deworming External">
                                    {!! $errors->first(
                                        'last_deworming_date',
                                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                    ) !!}
                                </div>
                            </div>
                        </div>

                            <div class="col-md-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="next_application_date" class="form-label">PRÓXIMA DESPARASITACIÓN</label>

                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="lucide--calendar-clock"></span>
                                        </span>

                                        <input type="date" name="next_application_date"
                                            class="form-control @error('next_application_date') is-invalid @enderror"
                                            value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                                            id="next_application_date" placeholder="Next External Date">
                                        {!! $errors->first(
                                            'next_application_date',
                                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                        ) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-2 mb20">
                                    <label for="observations" class="form-label">OBSERVACIONES</label>

                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                            <span class="fluent-mdl2--bug-block"></span>
                                        </span>

                                        <input type="text" name="observations"
                                            class="form-control @error('observations') is-invalid @enderror"
                                            value="{{ old('observations', $vaccineCertificate?->observations) }}"
                                            id="observations" placeholder="Observaciones">
                                        {!! $errors->first(
                                            'observations',
                                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                                <i class="fas fa-plus"></i>
                                REGISTRAR DESPARASTACIÓN</button>
                        </div>
                    </div>
