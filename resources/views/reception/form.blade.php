<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        min-height: 2rem;
        height: auto;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        white-space: normal;
        word-break: break-word;
        flex: 1 1 auto;
        min-width: 0;
        line-height: 1.2;
        padding-right: 20px;
    }

    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 0;
        display: flex;
        align-items: center;
    }

    .input-group .select2-container {
        width: auto !important;
        flex: 1 1 auto;
        min-width: 0;
    }

    /* Selector de tipo de recepción: radio oculto de forma accesible (no display:none)
       + label estilizada como pill compacto, ícono arriba y texto abajo.
       --pill-border-width es fijo y nunca se toca en los estados (:checked,
       :focus-visible, .is-invalid) para que el box model no varíe entre pills;
       flex: 0 0 <ancho> impide que el navegador encoja cada pill de forma
       desigual según el largo de su texto (esa desigualdad era la causa real
       del overflow, no el borde). */
    .reception-type-group,
    .reception-type-group * {
        box-sizing: border-box;
    }

    .reception-type-group {
        --pill-width: 78px;
        --pill-border-width: 1.5px;
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }

    .reception-type-radio {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: -1px;
        overflow: hidden;
        white-space: nowrap;
    }

    .reception-type-pill {
        display: flex;
        flex: 1 1 0;
        /* todas crecen/se encogen igual, repartiendo el ancho disponible */
        width: auto;
        /* ya no dependemos del --pill-width fijo */
        min-width: 0;
        /* evita que el contenido empuje el ancho más allá de su cuota */
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 10px 4px;
        border: var(--pill-border-width) solid #dee2e6;
        border-radius: 16px;
        background-color: #fff;
        color: #495057;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        text-align: center;
        line-height: 1.2;
        cursor: pointer;
        box-shadow: none;
        min-height: 78px;
        transition: border-color .15s ease, background-color .15s ease, color .15s ease;
    }

    .reception-type-pill img {
        width: 24px;
        height: 24px;
        object-fit: cover;
    }

    /* Estos 3 selectores solo cambian color/fondo: nunca border-width, padding ni width */
    .reception-type-radio:checked+.reception-type-pill {
        border-color: #0455a0;
        background-color: rgba(4, 85, 160, 0.08);
        color: #0455a0;
    }

    .reception-type-radio:focus-visible+.reception-type-pill {
        outline: 2px solid #0455a0;
        outline-offset: 2px;
    }

    .reception-type-radio.is-invalid+.reception-type-pill {
        border-color: #dc3545;
    }

    /* Dentro del modal de recepción: 2 columnas en vez de 3 para el resto de los campos */
    @media (min-width: 768px) {
        #receptionModal .row .col-md-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    /* Select2 de family_id/pet_id dentro de receptionModal: un valor
       seleccionado con texto muy largo no debe expandir el ancho del campo
       ni generar overflow horizontal — debe hacer wrap vertical (como un
       textarea), conservando el ancho fijo del contenedor.
       overflow-wrap:anywhere se suma a word-break:break-word (ya definido
       arriba, sin escopar): ese solo corta en puntos "razonables" del
       texto, así que una palabra larga sin espacios seguía contando para
       el ancho mínimo del flex item y podía forzar el layout a crecer o a
       saltar de línea. Escopado a #receptionModal para no tocar otros
       Select2 del proyecto (ej. petQuickCreateModal) en esta primera etapa. */
    #receptionModal .select2-container {
        max-width: 100%;
    }

    #receptionModal .select2-selection--single {
        min-width: 0;
        max-width: 100%;
    }

    #receptionModal .select2-selection--single .select2-selection__rendered {
        overflow-wrap: anywhere;
        max-width: 100%;
        /* flex-basis:0 en vez de auto (valor sin escopar de arriba): con
           "auto" el item arranca pidiendo el ancho completo del texto largo
           antes de encogerse, lo que en algunos casos alcanzaba a empujar
           el layout un instante/en navegadores menos permisivos. */
        flex: 1 1 0%;
    }

    /* .input-group de Bootstrap es flex-wrap:wrap: sin esto, un Select2 que
       no entra en la fila empuja TODO el campo (con su input-group-text) a
       una segunda línea, en vez de solo hacer wrap del texto adentro.
       nowrap + flex-basis:0/min-width:0 en el Select2 garantizan que el
       ícono y el campo queden siempre en la misma fila, y que el Select2
       use únicamente el espacio que le sobra al ícono. */
    #receptionModal .input-group {
        flex-wrap: nowrap;
    }

    #receptionModal .input-group>.input-group-text {
        flex-shrink: 0;
    }

    #receptionModal .input-group .select2-container {
        min-width: 0;
        max-width: 100%;
        flex: 1 1 0%;
    }
</style>

<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="reception-type-group mb-3" id="receptionTypeGroup">

            <input type="radio" id="consulta" name="reception_type_id" value="1"
                class="reception-type-radio @error('reception_type_id') is-invalid @enderror"
                {{ old('reception_type_id', $reception?->reception_type_id) == 1 ? 'checked' : '' }}
                onchange="togglee(this)">
            <label for="consulta" class="reception-type-pill">
                <img src="{{ asset('img/consulta.png') }}" alt="Consulta" id="preview">
                <span>Consulta</span>
            </label>

            <input type="radio" id="hospital" name="reception_type_id" value="2"
                class="reception-type-radio @error('reception_type_id') is-invalid @enderror"
                {{ old('reception_type_id', $reception?->reception_type_id) == 2 ? 'checked' : '' }}
                onchange="togglee(this)">
            <label for="hospital" class="reception-type-pill">
                <img src="{{ asset('img/hospital.png') }}" alt="Hospitalización">
                <span>Hospitalización</span>
            </label>

            <input type="radio" id="estetica" name="reception_type_id" value="3"
                class="reception-type-radio @error('reception_type_id') is-invalid @enderror"
                {{ old('reception_type_id', $reception?->reception_type_id) == 3 ? 'checked' : '' }}
                onchange="togglee(this)">
            <label for="estetica" class="reception-type-pill">
                <img src="{{ asset('img/estetica.png') }}" alt="Grooming">
                <span>Grooming</span>
            </label>

            <input type="radio" id="hotel" name="reception_type_id" value="4"
                class="reception-type-radio @error('reception_type_id') is-invalid @enderror"
                {{ old('reception_type_id', $reception?->reception_type_id) == 4 ? 'checked' : '' }}
                onchange="togglee(this)">
            <label for="hotel" class="reception-type-pill">
                <img src="{{ asset('img/hotel.png') }}" alt="Hotel">
                <span>Hotel</span>
            </label>

            <input type="radio" id="cremacion" name="reception_type_id" value="5"
                class="reception-type-radio @error('reception_type_id') is-invalid @enderror"
                {{ old('reception_type_id', $reception?->reception_type_id) == 5 ? 'checked' : '' }}
                onchange="togglee(this)">
            <label for="cremacion" class="reception-type-pill">
                <img src="{{ asset('img/cremacion.png') }}" alt="Cremación">
                <span>Cremación</span>
            </label>

        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group mb-2"> 
                    <label for="name" class="form-label">FECHA DE INGRESO  <span class="text-danger">*</span></label>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="lucide--calendar-clock"></span>
                        </span>

                        <input type="datetime-local" name="entry_date" id="entry_date"
                            class="form-control @error('entry_date') is-invalid @enderror"
                            value="{{ old('entry_date', $reception?->entry_date ? \Carbon\Carbon::parse($reception->entry_date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                            placeholder="Entry Date">
                        {!! $errors->first('entry_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="family_id" class="form-label">FAMILIA/PROPIETARIO <span class="text-danger">*</span></label>
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
                                    {{ str_pad($family->id, 4, '0', STR_PAD_LEFT) }}-{{ $family->name }}
                                    Tel.{{ $family->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label for="pet_id" class="form-label">MASCOTA  <span class="text-danger">*</span></label>
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
                                    {{ $pet->name }}{{ filled($pet->number_chip) ? ' #' . $pet->number_chip : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if (isset($showPetQuickCreate) && $showPetQuickCreate)
                            <button type="button" class="btn btn-outline-primary" id="quickCreatePetBtn"
                                title="Nueva mascota" onclick="openPetQuickCreateModal()">
                                <i class="fas fa-plus"></i>
                            </button>
                        @endif
                    </div>
                    {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>


            <div class="col-md-4" id="adm" style="display: none">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">ADMISIÓN  <span class="text-danger">*</span></label>
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
                    <label for="name" class="form-label">AREA  <span class="text-danger">*</span></label>
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
                    <label for="name" class="form-label">TIPO  <span class="text-danger">*</span></label>
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
                            class="form-control @error('veterinarian_id') is-invalid @enderror" id="veterinarian_id"
                            data-veterinarians='@json($veterinarians->map(fn($u) => ['id' => $u->id, 'name' => $u->name]))'
                            data-collaborators='@json($collaborators->map(fn($u) => ['id' => $u->id, 'name' => $u->name]))'>
                            <option value=""> Selecciona M.V.Z</option>
                            @foreach ($veterinarians as $user)
                                <option value="{{ $user->id }}"
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
                    <label for="name" class="form-label">FECHA DE SALIDA <span class="text-danger">*</span></label>
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
                    <label for="name" class="form-label">NÚMERO DE COLLAR/ARETE  <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="f7--number"></span>
                        </span>
                        <input type="number" name="num" class="form-control @error('num') is-invalid @enderror"
                            value="{{ old('num', $reception?->num) }}" id="num_input"
                            placeholder="Número de Collar/Arete">
                        {!! $errors->first('num', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

        </div>
        @if (!isset($grooming) || !$grooming)
            <div class="col-12 mt-2 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                    <i class="fas fa-plus"></i>
                    Guardar recepción</button>
            </div>
        @endif
    </div>
