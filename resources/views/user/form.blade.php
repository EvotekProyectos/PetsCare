<div class="row d-flex justify-content-center">
    <div class="col-12 col-lg-7">
        <label for="name" class="">{{ __('Name') }}</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                    class="fas fa-user text-primary"></i></span>
            <input placeholder="Nombre" id="name" type="text"
                class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ?? old('name') }}"
                required autocomplete="name" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <label for="role_name" class="">Rol</label>
        <div class="input-group mb-2">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i class="fas fa-cog text-primary"></i></span>
            <select name="role_name" id=""
                class="form-select form-select-sm text-capitalize @error('role_name') is-invalid @enderror">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ $user->roles->isNotEmpty() && $user->roles->first()->name === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('role_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>


    <div class="col-12 col-lg-7">
        <label for="email" class="">Correo electrónico</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                    class="fas fa-at text-primary"></i></span>
            <input placeholder="Correo electrónico" id="email" type="email"
                class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email ?? old('email') }}"
                required autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    <div class="col-12 col-lg-7">
        <label for="password" class="">{{ __('Password') }}</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                    class="fas fa-lock  text-primary"></i></span>
            <input placeholder="Contraseña" id="password" type="password"
                class="form-control @error('password') is-invalid @enderror" name="password"
                autocomplete="new-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    <div class="col-12 col-lg-7">
        <label for="password-confirm" class="">{{ __('Confirm Password') }}</label>
        <div class="input-group mb-3">
            <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                    class="fas fa-lock  text-primary"></i></span>
            <input placeholder="Confirmar contraseña" id="password-confirm" type="password" class="form-control"
                name="password_confirmation" autocomplete="new-password" placeholder="Cofirmar">
        </div>
    </div>
    <div class="col-12 col-lg-7 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
            Guardar registro</button>
    </div>
</div>
