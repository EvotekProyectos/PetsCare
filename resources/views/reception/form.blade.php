<style>
    .select2-container {
    width: 100% !important; /* Se asegura que el select ocupe todo el ancho */
}

    .select2-container .select2-selection--single {
        height: 2rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        display: flex;
    align-items: center; /* Centra verticalmente el contenido */
    box-sizing: border-box;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
    }
    
</style>

<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">

            {{-- <label for="reception_type_id" class="form-label">TIPO</label> --}}
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <input type="radio" id="consulta" name="reception_type_id" value="1"
                        class="form-control @error('reception_type_id') is-invalid @enderror"
                        {{ old('reception_type_id', $reception?->reception_type_id) == 1 ? 'checked' : '' }}
                        onchange="togglee(this)">

                    <label for="consulta" class="radio-label shadow border-0">
                        <img src="{{ asset('img/consulta.png') }}" alt="Foto Mascota" id="preview" class="img-fixed"
                            style="width: 25px; height: 25px; object-fit: cover; ">
                        </span> Consulta</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <input type="radio" id="hospital" name="reception_type_id" value="2"
                        class="form-control @error('reception_type_id') is-invalid @enderror"
                        {{ old('reception_type_id', $reception?->reception_type_id) == 2 ? 'checked' : '' }}
                        onchange="togglee(this)">

                    <label for="hospital" class="radio-label  shadow border-0">
                        <img src="{{ asset('img/hospital.png') }}" alt="Foto hospital" class="img-fixed"
                            style="width: 25px; height: 25px; object-fit: cover; ">
                        <span>Hospitalización</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <input type="radio" id="estetica" name="reception_type_id" value="3"
                        class="form-control @error('reception_type_id') is-invalid @enderror"
                        {{ old('reception_type_id', $reception?->reception_type_id) == 3 ? 'checked' : '' }}
                        onchange="togglee(this)">
                    <label for="estetica" class="radio-label  shadow border-0">
                        <img src="{{ asset('img/estetica.png') }}" alt="Foto estetica" class="img-fixed"
                            style="width: 25px; height: 25px; object-fit: cover; ">
                        <span>
                            Grooming</label>
                </div>
            </div>

            {{-- <div class="form-group mb-2 mb20">
                <label for="reception_type_id" class="form-label">TIPO</label>

                <input type="text" name="reception_type_id"
                    class="form-control @error('reception_type_id') is-invalid @enderror"
                    value="{{ old('reception_type_id', $reception?->reception_type_id) }}" id="reception_type_id"
                    placeholder="Reception Type Id">
                {!! $errors->first(
                    'reception_type_id',
                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                ) !!}
            </div> --}}

        </div>

        <div class="row mb-3 mt-1 d-flex justify-content-center">
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <input type="radio" id="hotel" name="reception_type_id" value="4"
                        class="form-control @error('reception_type_id') is-invalid @enderror"
                        {{ old('reception_type_id', $reception?->reception_type_id) == 4 ? 'checked' : '' }}
                        onchange="togglee(this)">
                    <label for="hotel" class="radio-label  shadow border-0">
                        <img src="{{ asset('img/hotel.png') }}" alt="Foto estetica" class="img-fixed"
                            style="width: 25px; height: 25px; object-fit: cover; ">
                        <span> Hotel</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <input type="radio" id="cremacion" name="reception_type_id" value="5"
                        class="form-control @error('reception_type_id') is-invalid @enderror"
                        {{ old('reception_type_id', $reception?->reception_type_id) == 5 ? 'checked' : '' }}
                        onchange="togglee(this)">
                    <label for="cremacion" class="radio-label  shadow border-0">
                        <img src="{{ asset('img/cremacion.png') }}" alt="Foto estetica" class="img-fixed"
                            style="width: 25px; height: 25px; object-fit: cover; ">
                        <span> Cremación</label>
                    {{-- {!! $errors->first(
                        'reception_type_id',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!} --}}
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">FECHA DE INGRESO</label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock"></span>
                        </span>

                        <input type="datetime-local" name="entry_date"
                            class="form-control @error('entry_date') is-invalid @enderror"
                            value="{{ old('entry_date', $reception?->entry_date) }}" id="entry_date"
                            placeholder="Entry Date">
                        {!! $errors->first('entry_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="family_id" class="form-label">FAMILIA/PROPIETARIO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--family text-primary"></span>
                        </span>
                        <select name="family_id" class="form-control select2 @error('family_id') is-invalid @enderror"
                            id="family_id" onchange="getpets(this.value)" style="width: 100%;">
                            <option value="">Selecciona la familia</option>
                            @foreach ($families as $family)
                                <option value="{{ $family->id }}"
                                    {{ old('family_id', $reception?->family_id) == $family->id ? 'selected' : '' }}>
                                    {{ $family->name }} {{ $family->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="pet_id" class="form-label">MASCOTA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon2">
                            <span class="ic--twotone-pets"></span>
                        </span>
                        <select name="pet_id" class="form-control select2 @error('pet_id') is-invalid @enderror"
                            id="pet_id" onchange="getFamily(this.value)" style="width: 100%;">
                            <option value="">Selecciona la mascota</option>
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}"
                                    {{ old('pet_id', $reception?->pet_id) == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->name }} #{{ $pet->number_chip }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>


            <div class="col-md-4" id="adm" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">ADMISIÓN</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="admission_type_id"
                            class="form-control @error('admission_type_id') is-invalid @enderror"
                            id="admission_type_id">
                            <option value=""> Selecciona el tipo de adminisión</option>
                            @foreach ($admissions as $admission)
                                <option value="{{ $admission->id }}" name="admission_type_id"
                                    {{ old('admission_type_id', $reception?->admission_type_id) == $admission->id ? 'selected' : '' }}>
                                    {{ $admission->name }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first(
                            'admission_type_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>


            <div class="col-md-4" id="area" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">AREA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="area_id" class="form-control @error('area_id') is-invalid @enderror"
                            id="area_id">
                            <option value=""> Selecciona el tipo de area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" name="area_id"
                                    {{ old('area_id', $reception?->area_id) == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('area_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>








            <div class="col-md-4" id="motivo" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">TIPO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <select name="reason_id" class="form-control @error('reason_id') is-invalid @enderror"
                            id="reason_id">
                            <option value=""> Selecciona el motivo</option>
                            @foreach ($reasons as $reason)
                                <option value="{{ $reason->id }}" name="reason_id"
                                    {{ old('reason_id', $reception?->reason_id) == $reason->id ? 'selected' : '' }}>
                                    {{ $reason->name }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('reason_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>


            <div class="col-md-4" id="mvz" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label" id="person">M.V.Z</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="maki--doctor"></span>
                        </span>
                        <select name="veterinarian_id"
                            class="form-control @error('veterinarian_id') is-invalid @enderror" id="veterinarian_id">
                            <option value=""> Selecciona M.V.Z</option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" name="veterinarian_id"
                                    {{ old('veterinarian_id', $reception?->veterinarian_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first(
                            'veterinarian_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4" hidden>
                <div class="form-group mb-2">
                    <label for="name" class="form-label">RECEPCIONISTA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="fluent-mdl2--family text-primary"></span>
                        </span>
                        <input type="text" name="recepcionist_id"
                            class="form-control @error('recepcionist_id') is-invalid @enderror"
                            value="{{ Auth::user()->id }}" id="recepcionist_id" placeholder="Recepcionist Id">
                        {!! $errors->first(
                            'recepcionist_id',
                            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                        ) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="consultorio" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">CONSULTORIO</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="maki--doctor"></span>
                        </span>
                        <select name="room_id" class="form-control @error('room_id') is-invalid @enderror"
                            id="room_id">
                            <option value=""> Selecciona el consultorio </option>

                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" name="room_id"
                                    {{ old('room_id', $reception?->room_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('room_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>



            <div class="col-md-4" id="salida" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">FECHA DE SALIDA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-check"></span>
                        </span>
                        <input type="datetime-local" name="exit_date"
                            class="form-control @error('exit_date') is-invalid @enderror"
                            value="{{ old('exit_date', $reception?->exit_date) }}" id="exit_date"
                            placeholder="Exit Date">
                        {!! $errors->first('exit_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="num" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">NÚMERO DE COLLAR/ARETE</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="f7--number"></span>
                        </span>
                        <input type="number" name="num" class="form-control @error('num') is-invalid @enderror"
                            value="{{ old('num', $reception?->num) }}" id="num"
                            placeholder="Número de Collar/Arete">
                        {!! $errors->first('num', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                <i class="fas fa-plus"></i>
                Guardar recepción</button>
        </div>
    </div>
    
