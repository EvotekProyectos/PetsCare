<div class="row padding-1 p-1">
    <div class="col-md-12">

        <form id="NewVaccine">@csrf
            <div class="form-group mb-2 mb20" hidden>
                <label for="pet_id" class="form-label">{{ __('Pet Id') }}</label>
                <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror"
                    value="{{ old('pet_id', $vaccineCertificate?->pet_id) }}" id="pet_id1" placeholder="Pet Id">
                {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="vet_id" class="form-label">VET</label>
                    <div class="input-group mb-3">
                        <input type="text" name="vet_id" class="form-control @error('vet_id') is-invalid @enderror"
                            value="{{ Auth::user()->id }}" id="vet_id" placeholder="vet_id">
                        {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 mb20" hidden>
                <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
                <input type="text" name="service_id" class="form-control @error('service_id') is-invalid @enderror"
                    value="1" id="vaccine_id" placeholder="Service Id">
                {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <h5><span class="fluent-mdl2--vaccination"></span> VACUNAS </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="application_date"
                                class="form-control @error('application_date') is-invalid @enderror"
                                value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                                id="application_date1" placeholder="Fecha de aplicación">
                            {!! $errors->first(
                                'application_date',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="product1" class="form-label">NOMBRE</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--vaccination"></span>
                            </span>

                            {{-- <input type="text" name="product"
                                class="form-control @error('product') is-invalid @enderror"
                                value="{{ old('product', $vaccineCertificate?->vaccine) }}" id="product1"
                                placeholder="Nombre de la vacuna">
                            {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!} --}}

                            <select name="product" class="form-control @error('product') is-invalid @enderror"
                                id="product1">
                                <option value=""> Selecciona la vacuna a aplicar</option>
                                @foreach ($products as $product)
                                    {{-- @if ($product->product_classification_id == 2) --}}
                                    <option value="{{ $product->ARTICULO_ID }}" name="product"
                                        {{ old('product', $vaccineCertificate?->vaccine) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                        {{ $product->NOMBRE }}</option>
                                    {{-- @endif --}}
                                @endforeach
                            </select>
                        </div>
                        {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="lab" class="form-label">LABORATORIO</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--vaccination"></span>
                            </span>

                            <input type="text" name="lab" class="form-control @error('lab') is-invalid @enderror"
                                value="{{ old('lab', $vaccineCertificate?->lab) }}" id="lab1"
                                placeholder="Laboratorio">
                            {!! $errors->first('lab', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
                {{-- </div>
            <div class="row"> --}}
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="lote" class="form-label">LOTE</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--vaccination"></span>
                            </span>
                            <input type="text" name="lote"
                                class="form-control @error('lote') is-invalid @enderror"
                                value="{{ old('lote', $vaccineCertificate?->lote) }}" id="lote1" placeholder="Lote">
                            {!! $errors->first('lote', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="next_application_date" class="form-label">PRÓXIMA APLICACIÓN</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="next_application_date"
                                class="form-control @error('next_application_date') is-invalid @enderror"
                                value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                                id="next_application_date1" placeholder="Fecha de próxima vacuna">
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
                                id="observations1" placeholder="Observaciones">
                            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="NewInterDeworming">@csrf
            <div class="form-group mb-2 mb20" hidden>
                <label for="pet_id" class="form-label">{{ __('Pet Id') }}</label>
                <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror"
                    value="{{ old('pet_id', $vaccineCertificate?->pet_id) }}" id="pet_id2" placeholder="Pet Id">
                {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="vet_id" class="form-label">VET</label>
                    <div class="input-group mb-3">
                        <input type="text" name="vet_id"
                            class="form-control @error('vet_id') is-invalid @enderror"
                            value="{{ Auth::user()->id }}" id="vet_id" placeholder="vet_id">
                        {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 mb20" hidden>
                <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
                <input type="text" name="service_id"
                    class="form-control @error('service_id') is-invalid @enderror" value="2" id="inter_id"
                    placeholder="Service Id">
                {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="row">
                <H5 style="margin-top: 20px"> <span class="fluent-mdl2--bug-block"></span> DESPARASITACIÓN INTERNAS
                </H5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <span class="lucide--calendar-clock"></span>
                                </span>

                                <input type="date" name="application_date"
                                    class="form-control @error('application_date') is-invalid @enderror"
                                    value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                                    id="application_date2" placeholder="Fecha de aplicación">
                                {!! $errors->first(
                                    'application_date',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="product2" class="form-label">PRODUCTO</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <span class="fluent-mdl2--bug-block"></span>
                                </span>

                                {{-- <input type="text" name="product"
                                    class="form-control @error('product') is-invalid @enderror"
                                    value="{{ old('product', $vaccineCertificate?->product) }}" id="product2"
                                    placeholder="Nombre del producto"> --}}
                                <select name="product" class="form-control @error('product') is-invalid @enderror"
                                    id="product2">
                                    <option value=""> Selecciona la desparacitación a aplicar</option>
                                    @foreach ($products as $product)
                                        {{-- @if ($product->product_classification_id == 2) --}}
                                        <option value="{{ $product->ARTICULO_ID }}" name="product"
                                            {{ old('product', $vaccineCertificate?->product) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                            {{ $product->NOMBRE }}</option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="dose" class="form-label">DOSIS</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <span class="fluent-mdl2--bug-block"></span>
                                </span>

                                <input type="text" name="dose"
                                    class="form-control @error('dose') is-invalid @enderror"
                                    value="{{ old('dose', $vaccineCertificate?->dose) }}" id="dose2"
                                    placeholder="Dosis aplicada">
                                {!! $errors->first('dose', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            </div>
                        </div>
                    </div>
                    {{-- <div>

                    <div class="row"> --}}
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="last_deworming" class="form-label">ÚLTIMA DESPARASITACIÓN</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <span class="lucide--calendar-clock"></span>
                                </span>


                                <input type="date" name="last_deworming_date"
                                    class="form-control @error('last_deworming') is-invalid @enderror"
                                    value="{{ old('last_deworming', $vaccineCertificate?->last_deworming) }}"
                                    id="last_deworming_date2" placeholder="FECHA ULTIMA DESPARASITACION">
                                {!! $errors->first(
                                    'last_deworming',
                                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                                ) !!}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="next_application_date" class="form-label">PRÓXIMA DESPARASITACIÓN</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                    <span class="lucide--calendar-clock"></span>
                                </span>


                                <input type="date" name="next_application_date"
                                    class="form-control @error('next_application_date') is-invalid @enderror"
                                    value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                                    id="next_application_date2" placeholder="Next Internal Date">
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
                                    id="observations2" placeholder="Observaciones">
                                {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            </div>
                        </div>
                    </div>
                </div>
        </form>

        <form id="NewExternDeworming">@csrf
            <div class="form-group mb-2 mb20" hidden>
                <label for="pet_id" class="form-label">{{ __('Pet Id') }}</label>
                <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror"
                    value="{{ old('pet_id', $vaccineCertificate?->pet_id) }}" id="pet_id3" placeholder="Pet Id">
                {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="vet_id" class="form-label">VET</label>
                    <div class="input-group mb-3">
                        <input type="text" name="vet_id"
                            class="form-control @error('vet_id') is-invalid @enderror"
                            value="{{ Auth::user()->id }}" id="vet_id" placeholder="vet_id">
                        {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group mb-2 mb20" hidden>
                <label for="service_id" class="form-label">{{ __('Service Id') }}</label>
                <input type="text" name="service_id"
                    class="form-control @error('service_id') is-invalid @enderror" value="3" id="extern_id"
                    placeholder="Service Id">
                {!! $errors->first('service_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <H5 style="margin-top: 20px;"><span class="fluent-mdl2--bug-block"></span> DESPARASITACIÓN EXTERNAS</H5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="application_date" class="form-label">FECHA DE APLICACIÓN</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="application_date"
                                class="form-control @error('application_date') is-invalid @enderror"
                                value="{{ old('application_date', $vaccineCertificate?->application_date) }}"
                                id="application_date3" placeholder="Fecha de aplicación">
                            {!! $errors->first(
                                'application_date',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group mb-2 mb20">
                        <label for="product3" class="form-label">PRODUCTO</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--bug-block"></span>
                            </span>

                            {{-- <input type="text" name="product"
                                class="form-control @error('product') is-invalid @enderror"
                                value="{{ old('product', $vaccineCertificate?->product) }}" id="product3"
                                placeholder="Nombre del producto"> --}}
                            <select name="product" class="form-control @error('product') is-invalid @enderror"
                                id="product3">
                                <option value=""> Selecciona la desparacitación a aplicar</option>
                                @foreach ($products as $product)
                                    {{-- @if ($product->product_classification_id == 2) --}}
                                    <option value="{{ $product->ARTICULO_ID }}" name="product"
                                        {{ old('product', $vaccineCertificate?->product) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                        {{ $product->NOMBRE }}</option>
                                    {{-- @endif --}}
                                @endforeach
                            </select>
                            {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2 mb20">
                        <label for="dose" class="form-label">DOSIS</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="fluent-mdl2--bug-block"></span>
                            </span>

                            <input type="text" name="dose"
                                class="form-control @error('dose') is-invalid @enderror"
                                value="{{ old('dose', $vaccineCertificate?->dose) }}" id="dose3"
                                placeholder="Dosis aplicada">
                            {!! $errors->first('dose', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
                {{-- </div>

            <div class="row"> --}}
                <div class="col-md-6">
                    <div class="form-group mb-2 mb20">
                        <label for="last_deworming_date" class="form-label">ÚLTIMA DESPARASITACIÓN</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="last_deworming_date"
                                class="form-control @error('last_deworming_date') is-invalid @enderror"
                                value="{{ old('last_deworming_date', $vaccineCertificate?->last_deworming_date) }}"
                                id="last_deworming_date3" placeholder="Last Deworming External">
                            {!! $errors->first(
                                'last_deworming_date',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2 mb20">
                        <label for="next_application_date" class="form-label">PRÓXIMA DESPARASITACIÓN</label>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock"></span>
                            </span>

                            <input type="date" name="next_application_date"
                                class="form-control @error('next_application_date') is-invalid @enderror"
                                value="{{ old('next_application_date', $vaccineCertificate?->next_application_date) }}"
                                id="next_application_date3" placeholder="Next External Date">
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
                                id="observations3" placeholder="Observaciones">
                            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button onclick="Register()" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <span class="solar--shield-check-linear"></span>
            REGISTRAR SERVICIOS</button>
    </div>
