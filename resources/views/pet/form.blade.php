<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">
            <!-- First Row -->
            <div class="col-md-4 d-flex justify-content-center">
                <div class="form-group text-center">
                    <label for="file" class="form-label d-block ">Foto</label>
                    <label for="file">
                        <img src="{{ asset('img/pet_pic.png') }}" alt="Foto Mascota"
                             id="preview" class="img-fixed" style="width: 115px; height: 115px; object-fit: cover; border-radius: 70px; ">
                    </label>
                    <input type="file" id="file" name="file" style="display: none"> 
                </div>
            </div>
            <div class="col-md-4" style="display: none">
                <div class="form-group mb-2 mb20">
                    <label for="family_id" class="form-label">{{ __('Family Id') }}</label>
                    <input type="text" name="family_id" class="form-control @error('family_id') is-invalid @enderror"
                        value="{{ old('family_id', $family?->id) }}" id="family_id" placeholder="Family Id">
                    {!! $errors->first('family_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Second Row -->
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $pet?->name) }}" id="name" placeholder="Nombre de la mascota">
                        {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
           
                <div class="col-md-4">
                    <div class="form-group mb-2 mb20">
                        <label for="number_chip" class="form-label">#CHIP</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                                <span class="mdi--pets"></span>
                            </span>
                            <input type="text" name="number_chip" class="form-control @error('number_chip') is-invalid @enderror"
                                value="{{ old('number_chip', $pet?->number_chip) }}" id="number_chip" placeholder="Número chip de la mascota">
                            {!! $errors->first('number_chip', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
                

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="species_id" class="form-label">Especie</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <select name="species_id" id="species_id"
                            class="form-control @error('species_id') is-invalid @enderror">
                            <option value="">Seleccione la especie de la mascota</option>
                            @foreach ($Species as $specie)
                                <option value="{{ $specie->id }}"
                                    {{ old('species_id', $pet?->species_id) == $specie->id ? 'selected' : '' }}>
                                    {{ $specie->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('species_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>

            <div class="row">
                <!-- Third Row -->
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="breed_id" class="form-label">{{ __('Raza') }}</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        {{-- Poblado server-side con las razas de la especie ya
                             guardada (ver PetController::edit()/create()), y
                             re-poblado por JS (breedSelectCascade.js) cuando
                             el usuario cambia de especie. --}}
                        <select name="breed_id" id="breed_id"
                            class="form-control @error('breed_id') is-invalid @enderror">
                            <option value="">Seleccione la raza de la mascota</option>
                            @foreach ($Breeds as $breed)
                                <option value="{{ $breed->id }}"
                                    {{ old('breed_id', $pet?->breed_id) == $breed->id ? 'selected' : '' }}>
                                    {{ $breed->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('breed_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>


       
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="gender_id" class="form-label">Género</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <select name="gender_id" class="form-control @error('gender_id') is-invalid @enderror"
                            id="gender_id">
                            <option value="">Seleccione el género de la mascota</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}" name="gender_id"
                                    {{ old('gender_id', $pet?->gender_id) == $gender->id ? 'selected' : '' }}>
                                    {{ $gender->name }} </option>
                            @endforeach
                        </select>
                        {!! $errors->first('gender_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="birthday" class="form-label">Cumpleaños</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <input type="date" name="birthday"
                            class="form-control @error('birthday') is-invalid @enderror"
                            value="{{ old('birthday', $pet?->birthday) }}" id="birthday" placeholder="Birthday">
                        {!! $errors->first('birthday', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <!-- Fourth Row -->
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="reproductive_status_id" class="form-label">Estado Reproductivo</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <select name="reproductive_status_id" id="reproductive_status_id" class="form-control @error('reproductive_status_id') is-invalid @enderror" >
                            <option value=""> Seleccione el estado reproductivo de la mascota </option>
                            @foreach ($ReproductiveStatuses as $ReproductiveStatus)
                                <option value="{{$ReproductiveStatus->id}}" name="reproductive_status_id"
                                    {{ old('reproductive_status_id', $pet?->reproductive_status_id)  == $ReproductiveStatus->id ? 'selected' : '' }}>
                                    {{$ReproductiveStatus->name}}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first(
                            'reproductive_status_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        

        
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="weight" class="form-label">Peso</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <input type="text" name="weight"
                            class="form-control @error('weight') is-invalid @enderror"
                            value="{{ old('weight', $pet?->weight) }}" id="weight" placeholder="Indique el paso en kg de la mascota">
                        {!! $errors->first('weight', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="physic_descrip" class="form-label">Descripción física</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <input type="text" name="physic_descrip"
                            class="form-control @error('physic_descrip') is-invalid @enderror"
                            value="{{ old('physic_descrip', $pet?->physic_descrip) }}" id="physic_descrip"
                            placeholder="Breve descripción de la mascota">
                        {!! $errors->first(
                            'physic_descrip',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Fifth Row -->
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="notes" class="form-label">Notas</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <input type="text" name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            value="{{ old('notes', $pet?->notes) }}" id="notes" placeholder="Notas acerca de la mascota">
                        {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group mb-2 mb20">
                    <label for="pet_classification_id" class="form-label">Clasificación de la mascota</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="background-color: #d3f0f3" id="basic-addon1">
                            <span class="mdi--pets"></span>
                        </span>
                        <select name="pet_classification_id" id="pet_classification_id"
                            class="form-control @error('pet_classification_id') is-invalid @enderror">
                            <option value="">Seleccione una clasificación</option>
                            @foreach ($PetClassifications as $PetClassification)
                                <option value="{{$PetClassification->id}}" name="pet_classification_id"
                                    {{ old('pet_classification_id', $pet?->pet_classification_id) == $PetClassification->id ? 'selected' : '' }}>
                                    {{$PetClassification->name}}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first(
                            'pet_classification_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2 mb20" style="margin-top: 30px;">
                    <div class="input-group mb-3 ">
                        <input type="hidden" name="deceased" value="0">
                        <input type="checkbox" name="deceased" id="deceased" value="1"
                            class="form-check-input @error('deceased') is-invalid @enderror"
                            {{ old('deceased', $pet?->deceased) ? 'checked' : '' }} > 
                        <label for="deceased" class="form-label" style="margin-left: 10px;"> Falleció?</label>
                        {!! $errors->first('deceased', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>

            {{-- <div class="col-md-4">

            </div> --}}
        </div>
        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                <i class="fas fa-plus"></i>
                Guardar mascota</button>
        </div>
    </div>

