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
                    <label for="phone_input" class="form-label">Teléfono principal</label>
                    <input type="tel" id="phone_input" value="{{ old('phone', $family?->phone) }}"
                        class="form-control @error('phone') is-invalid @enderror">
                    {{-- Campo auxiliar de intl-tel-input: aquí el usuario escribe/ve el número.
                         El valor que realmente viaja a Laravel es el input hidden "phone" de abajo. --}}
                    <input type="hidden" name="phone" id="phone" value="{{ old('phone', $family?->phone) }}">
                    {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="email" class="form-label">Correo electronico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="ic--outline-alternate-email text-primary"></span>
                        </span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $family?->email) }}" id="email" placeholder="Email">
                        {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
            
           
        </div>

        <div class="row">
             <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="email_confirmation" class="form-label">Confirmar correo electronico</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="ic--outline-alternate-email text-primary"></span>
                        </span>
                        {{-- Deliberadamente sin old('email_confirmation', $family?->email): el usuario
                             debe volver a teclear el correo cada vez, no se precarga con el ya guardado. --}}
                        <input type="email" name="email_confirmation"
                            class="form-control @error('email_confirmation') is-invalid @enderror"
                            value="{{ old('email_confirmation') }}" id="email_confirmation"
                            placeholder="Confirmar email" onpaste="return false;" ondrop="return false;"
                            autocomplete="off" title="Vuelve a escribir el correo, no se puede pegar">
                        @if ($errors->has('email_confirmation'))
                            <div class="invalid-feedback" role="alert"><strong>{{ $errors->first('email_confirmation') }}</strong></div>
                        @else
                            {{-- La rellena email-confirmation.js (JS) mientras el usuario escribe; queda vacía si JS no corrió. --}}
                            <div id="email_confirmation_live_feedback" class="invalid-feedback"></div>
                        @endif
                    </div>
                </div>
            </div>
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
           
        </div>

        <div class="row">
             <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="contact_number_input" class="form-label">Teléfono Contacto Autorizado</label>
                    <input type="tel" id="contact_number_input"
                        value="{{ old('contact_number', $family?->contact_number) }}"
                        class="form-control @error('contact_number') is-invalid @enderror">
                    {{-- Campo auxiliar de intl-tel-input, ver comentario en el campo "phone" de arriba. --}}
                    <input type="hidden" name="contact_number" id="contact_number"
                        value="{{ old('contact_number', $family?->contact_number) }}">
                    {!! $errors->first(
                        'contact_number',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div> 
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

@push('styles')
    <link href="{{ asset('vendor/intl-tel-input/css/intlTelInput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intl-tel-input-bootstrap.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/intl-tel-input/js/intlTelInputWithUtils.min.js') }}" defer></script>
    <script src="{{ asset('js/families/phone-input.js') }}" defer></script>
    <script src="{{ asset('js/families/email-confirmation.js') }}" defer></script>
@endpush
