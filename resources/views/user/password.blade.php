<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 ">
            <label for="name" class="form-label">Escribir nueva contraseña</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                        class="fas fa-lock  text-primary"></i></span>
                <input placeholder="Contraseña" id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password" required
                    autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group mb-2 ">
            <label for="email" class="form-label">{{ __('Confirm Password') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1"><i
                        class="fas fa-lock  text-primary"></i></span>
                <input placeholder="Confirmar contraseña" id="password-confirm" type="password" class="form-control"
                    name="password_confirmation" required autocomplete="new-password" placeholder="Cofirmar">
            </div>
        </div>
    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
            Actualizar contraseña</button>
    </div>
</div>
