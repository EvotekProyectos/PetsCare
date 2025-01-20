<style>
    .select2-container .select2-selection--single {
        height: 2rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
    }
</style>


<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror"
             value="{{ $reception->id}}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="pet_id" class="form-label">{{ __('Pet Id') }}</label>
            <input type="text" name="pet_id" class="form-control @error('pet_id') is-invalid @enderror" 
            value="{{$reception->pet->id }}" id="pet_id" placeholder="Pet Id">
            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="date_death" class="form-label">FECHA DE DEFUNCIÓN</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fas fa-calendar text-primary"></i>
                        </span>
                        <input type="datetime-local" name="date_death"
                            class="form-control @error('date_death') is-invalid @enderror" 
                            value="{{ old('date_death', $cremation?->date_death) }}" id="date_death" 
                            placeholder="Date Death">
                    </div>
                    {!! $errors->first('date_death', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="c_m_id" class="form-label">C.M.</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="CM_id" class="form-control @error('CM_id') is-invalid @enderror" id="c_m_id">
                            <option value=""> Selecciona el tipo de C.M.</option>
                            @foreach ($cms as $cm)
                                <option value="{{ $cm->id }}" name="CM_id"
                                    {{ old('CM_id', $cremation?->CM_id)  == $cm->id ? 'selected' : '' }}>
                                    {{ $cm->name }}</option>
                            @endforeach
                        </select> 
                    </div>
                    {!! $errors->first('CM_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="servicie" class="form-label">SERVICIO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="servicie" class="form-control select2 @error('servicie') is-invalid @enderror" 
                        id="servicie">
                            <option value="">Selecciona el tipo de servicio</option>
                            @foreach ($products as $product)
                        {{-- @if ($product->product_classification_id == 4) --}}
                            <option value="{{ $product->ARTICULO_ID}}" name="service"
                                {{ old('service', $cremation?->service) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                {{ $product->NOMBRE }}</option>
                        {{-- @endif  --}}
                    @endforeach
                        </select>
                    </div>
                    {!! $errors->first('servicie', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2">
                     <label for="type_urn" class="form-label">TIPO DE URNA</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="vaadin--lines-list"></span>
                             </span>
                                <input type="text" name="type_urn" 
                                class="form-control @error('type_urn') is-invalid @enderror" 
                                value="{{ old('type_urn', $cremation?->type_urn) }}" id="type_urn" placeholder="Tipo de urna">
                        </div>
                        {!! $errors->first('type_urn', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            
        </div>
 


        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="vet_id" class="form-label">M.V.Z RESPONSABLE</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="vet_id" class="form-control @error('vet_id') is-invalid @enderror" id="vet_id">
                            <option value=""> Selecciona M.V.Z.</option>
                            @foreach ($vets as $vet)
                                <option value="{{ $vet->id }}" name="vet_id"
                                    {{ old('vet_id', $cremation?->vet_id)  == $vet->id ? 'selected' : '' }}>
                                    {{ $vet->name }}</option>
                            @endforeach
                        </select> 
                    </div>
                    {!! $errors->first('vet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="date_finish" class="form-label">FECHA DE ENTREGA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fas fa-calendar text-primary"></i>
                        </span>
                        <input type="datetime-local" name="date_finish" 
                            class="form-control @error('date_finish') is-invalid @enderror"
                            value="{{ old('date_finish', $cremation?->date_finish) }}" id="date_finish" 
                            placeholder="Date Finish">
                    </div>
                    {!! $errors->first('date_finish', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

        </div>
    </div>

   

    <div class="row ">
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label for="observations" class="form-label">OBSERVACIONES</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <textarea name="observations" rows="3"
                        class="form-control @error('observations') is-invalid @enderror" placeholder=""
                        id="observations">{{ old('observations', $cremation?->observations) }}</textarea>
                </div>
                {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        
       
        <div class="col-md-6">
            <div class="d-flex">
                <div class="me-4">
                    <div class="form-group mb-2">
                        <label for="placa_type_id" class="form-label">TIPO DE PLACA</label>
                        <div class="d-flex gap-3 align-items-center">
                            <label class="text-center" style="margin-right: 10px;">
                                <img src="{{asset('img/bone.png')}}" alt="Placa Hueso" class="img-fluid mb-2 shadow-custom" 
                                    style="width: 80px; height: 80px; border-radius: 50%; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">
                                <br>
                                <input type="radio" name="placa_type_id" value="1" class="form-check-input">
                            </label>
                            <label class="text-center">
                                <img src="{{asset('img/circle.png')}}" alt="Placa Circular" class="img-fluid mb-2" 
                                    style="width: 80px; height: 80px;border-radius: 50%; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">
                                <br>
                                <input type="radio" name="placa_type_id" value="2" class="form-check-input">
                            </label>
                        </div>
                        {!! $errors->first('placa_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
                
                <div style="flex: 1;">
                    <div class="form-group mb-2">
                        <label for="text_placa" class="form-label">MENSAJE DE LA PLACA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="vaadin--lines-list"></span>
                            </span>
                            <textarea name="text_placa" rows="3"
                                class="form-control @error('text_placa') is-invalid @enderror"
                                placeholder="Nombre o texto que se desea para la placa"
                                id="text_placa">{{ old('text_placa', $cremation?->text_placa) }}</textarea>
                        </div>
                        {!! $errors->first('text_placa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
 {{-- <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="placa_type_id" class="form-label">TIPO DE PLACA</label>
                    <div class="d-flex gap-3 justify-content-center align-items-center">
                        <label class="text-center">
                            <img src="{{asset('img/bone.png')}}" alt="Placa Hueso" class="img-fluid mb-2" style="width: 80px; height: 80px; shadow-custom;">
                            <br>
                            <input type="radio" name="placa_type_id" value="1" class="form-check-input">
                        </label>
                        <label class="text-center">
                            <img src="{{asset('img/circle.png')}}" alt="Placa Circular" class="img-fluid mb-2" style="width: 80px; height: 80px;">
                            <br>
                            <input type="radio" name="placa_type_id" value="2" class="form-check-input">
                        </label>
                    </div>
                    {!! $errors->first('placa_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

        <!-- Mensaje para la Placa -->
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label for="observations_plate" class="form-label">MENSAJE PARA LA PLACA</label>
                <div class="input-group">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span>
                    </span>
                    <textarea name="observations_plate" rows="3"
                              class="form-control @error('observations_plate') is-invalid @enderror"
                              placeholder="Nombre o texto de la placa y otras observaciones"
                              id="observations_plate">{{ old('observations_plate', $cremation?->observations_plate) }}</textarea>
                </div>
                {!! $errors->first('observations_plate', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
    </div> --}}
        
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="observations" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea name="observations" rows="2"
                            class="form-control @error('observations') is-invalid @enderror"placeholder=""
                            id="observations">{{ old('observations', $cremation?->observations) }}</textarea>
                    </div>
                    {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-12 d-flex align-items-start">
                <div class="me-4">
                    <div class="form-group mb-2">
                        <label for="placa_type_id" class="form-label">TIPO DE PLACA</label>
                        <div class="d-flex gap-3 align-items-center">
                            <label class="text-center" style="margin-right: 10px;">
                                <img src="{{asset('img/bone.png')}}" alt="Placa Hueso" class="img-fluid mb-2 shadow-custom" 
                                style="width: 80px; height: 80px; border-radius: 50%; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">
                           
                                <br>
                                <input type="radio" name="placa_type_id" value="1" class="form-check-input">
                            </label>
        
                            <label class="text-center">
                                <img src="{{asset('img/circle.png')}}" alt="Placa Circular" class="img-fluid mb-2" style="width: 80px; height: 80px;border-radius: 50%; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">
                                <br>
                                <input type="radio" name="placa_type_id" value="2" class="form-check-input">
                            </label>
                        </div>
                        {!! $errors->first('placa_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
        
                <div style="flex: 1; max-width: 31.8%;">
                    <div class="form-group mb-2">
                        <label for="observations" class="form-label">MENSAJE DE LA PLACA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="vaadin--lines-list"></span>
                            </span>
                            <textarea name="observations" rows="3"
                                class="form-control @error('observations') is-invalid @enderror"
                                placeholder="Nombre o texto que se desea para la placa"
                                id="observations">{{ old('observations', $cremation?->observations) }}</textarea>
                        </div>
                        {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div> --}}
        
        <div class="form-group mb-2 mb20" hidden>
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $cremation?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
     {{-- <div class="col-md-6">
            <div class="form-group mb-2">
            <label for="urn_model" class="form-label">MODELO DE URNA</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
            <input type="text" name="urn_model" 
            class="form-control @error('urn_model') is-invalid @enderror" 
            value="{{ old('urn_model', $cremation?->urn_model) }}" id="urn_model" placeholder="Modelo de urna">
            </div>
            {!! $errors->first('urn_model', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        
    
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>
           REGISTRAR SERVICIO</button>
    </div>

    <form action=""></form>
</div>

@push('scripts')
<script>
    var ruta = "{{ asset('') }}";;
</script>

@endpush 
