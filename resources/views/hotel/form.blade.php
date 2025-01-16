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
            value="{{ old('reception_id',  $reception->id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20" hidden>
            <label for="vaccine_certificate_id" class="form-label">{{ __('Vaccine Certificate Id') }}</label>
            <input type="text" name="vaccine_certificate_id" class="form-control @error('vaccine_certificate_id') is-invalid @enderror" value="{{ old('vaccine_certificate_id', $hotel?->vaccine_certificate_id) }}" id="vaccine_certificate_id" placeholder="Vaccine Certificate Id">
            {!! $errors->first('vaccine_certificate_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-2">
            <label for="food" class="form-label">TIPO DE ALIMENTACIÓN</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="game-icons--dog-bowl"></span>
                </span>
            <textarea name="food" rows="3"
            class="form-control @error('food') is-invalid @enderror"
             value="{{ old('food', $hotel?->food) }}" id="food" placeholder="Agregar tipo de alimentos, horarios de comida, indicaciones"></textarea>
             </div>
            {!! $errors->first('food', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        
            <div class="col-md-6">
                <div class="form-group mb-2">
            <label for="objects" class="form-label">TIPO DE OBJETOS</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="game-icons--dog-bowl"></span>
                </span>
            <textarea name="objects" rows="3" class="form-control @error('objects') is-invalid @enderror" 
            value="{{ old('objects', $hotel?->objects) }}" id="objects" placeholder="Lista de objetos que acompañan a la mascota"></textarea>
            </div>
            {!! $errors->first('objects', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-2">
            <label for="observations" class="form-label">OBSERVACIONES</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span>
                </span>
            <textarea name="observations" rows="3"
            class="form-control @error('observations') is-invalid @enderror"
             value="{{ old('observations', $hotel?->observations) }}" id="observations" placeholder="Detallar las necesidades particulares de la mascota"></textarea>
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
</div>

        <div class="d-flex justify-content-between align-items-center">
            <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
               ESPECIFICACIONES DEL SERVICIO
            </h5>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="service_type_id" class="form-label">TIPO DE SERVICIO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="service_type_id" class="form-control2 @error('service_type_id') is-invalid @enderror" id="service_type_id">
                            <option value="">Selecciona el tipo de pensión</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->ARTICULO_ID }}" name="service_type_id"
                                    {{ old('service_type_id', $hotel?->service_type_id) == $product->ARTICULO_ID ? 'selected' : '' }}>
                                    {{ $product->NOMBRE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first('service_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="number_days" class="form-label">DÍAS</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="tabler--calendar-time"></span>
                        </span>
                        <input type="number" name="number_days" class="form-control @error('number_days') is-invalid @enderror"
                            value="{{ old('number_days', $hotel?->number_days) }}" id="number_days" placeholder="Número de días de servicio" step="1" 
                            min="1">
                        {!! $errors->first('number_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
            <label for="cubicle_id" class="form-label">NÚMERO DE CUBÍCULO</label>
            <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1" style="font-size: .5 rem;">
                        <span class="game-icons--dog-house"></span>
                    </span>
                    <select name="cubicle_id" class="form-control @error('cubicle_id') is-invalid @enderror" id="cubicle_id">
                        <option value="">Selecciona el número de cubículo</option>
                        @foreach($cubicles as $cubicle)
                            <option value="{{ $cubicle->id }}" name="cubicle_id"
                                {{ old('cubicle_id', $hotel?->cubicle_id) == $cubicle->id ? 'selected' : '' }}>
                                {{ $cubicle->name }}
                            </option>
                        @endforeach
                    </select>
        </div>
            {!! $errors->first('cubicle_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
      
        {{-- <div class="form-group mb-2 mb20" hidden>
            <label for="video" class="form-label">{{ __('video') }}</label>
            <input type="text" name="video" class="form-control @error('video') is-invalid @enderror" 
            value="{{ old('video',  $reception->id) }}" id="video" placeholder="video">
            {!! $errors->first('video', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}

        
    </div>
    </div>


 
      <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Agregar servicio</button>
    </div> 
</div>