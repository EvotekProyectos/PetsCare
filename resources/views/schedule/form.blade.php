<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="begin" class="form-label">Del día </label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fa fa-calendar-check text-primary"></i></span>
                <input type="date" name="begin" class="form-control @error('begin') is-invalid @enderror"
                    value="{{ old('begin', $schedule?->begin) }}" id="begin" placeholder="Begin">
                {!! $errors->first('begin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="end" class="form-label">Hasta el día</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <i class="fa fa-calendar-check text-primary"></i></span>
                    <input type="date" name="end" class="form-control @error('end') is-invalid @enderror"
                        value="{{ old('end', $schedule?->end) }}" id="end" placeholder="End">
                    {!! $errors->first('end', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            <div class="form-group mb-2 mb20">
                <label for="shift_id" class="form-label">Turno a Cubrir</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <i class="fa fa-calendar-check text-primary"></i></span>
                    <select name="shift_id" id="shift_id" class="form-control @error('shift_id') is-invalid @enderror">
                        <option value="">Selecciona el turno a cubrir </option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" name="shift_id"
                                {{ old('shift_id', $schedule?->shift_id) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('shift_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            <div class="form-group mb-2 mb20">
                <label for="user_id" class="form-label">Persona asignada</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <i class="fa fa-calendar-check text-primary"></i></span>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                        <option value="">Selecciona el personal asignado</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" name="user_id"
                                {{ old('user_id', $schedule?->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            <div class="form-group mb-2 mb20">
                <label for="cover_area_id" class="form-label">Area Asignada</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <i class="fa fa-calendar-check text-primary"></i></span>
                    <select name="cover_area_id" id="cover_area_id"
                        class="form-control @error('cover_area_id') is-invalid @enderror">
                        <option value="">Selecciona el area a cubrir</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" name="cover_area_id"
                                {{ old('cover_area_id', $schedule?->cover_area_id) == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first(
                        'cover_area_id',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>

        </div>
        <div class="col-12 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
                Guardar Horario</button>
        </div>
    </div>
