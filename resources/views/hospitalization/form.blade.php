<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fas fa-list"></i>
                </span>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $hospitalization?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reason" class="form-label">Motivo del ingreso</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <i class="fas fa-list"></i>
                </span>
            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" value="{{ old('reason', $hospitalization?->reason) }}" id="reason" placeholder="Motivo">
            {!! $errors->first('reason', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total_days" class="form-label">Días Hospitalizado</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="tabler--calendar-time"></span>
                </span>
            <input type="text" name="total_days" class="form-control @error('total_days') is-invalid @enderror" value="{{ old('total_days', $hospitalization?->total_days) }}" id="total_days" placeholder="Días Hospitalizado">
            {!! $errors->first('total_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total_payment" class="form-label">Gran Total</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="f7--money-dollar"></span>
                </span>
            <input type="text" name="total_payment" class="form-control @error('total_payment') is-invalid @enderror" value="{{ old('total_payment', $hospitalization?->total_payment) }}" id="total_payment" placeholder="Total A Pagar">
            {!! $errors->first('total_payment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="already_paid" class="form-label">Abonado</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="f7--money-dollar"></span>
                </span>
            <input type="text" name="already_paid" class="form-control @error('already_paid') is-invalid @enderror" value="{{ old('already_paid', $hospitalization?->already_paid) }}" id="already_paid" placeholder="Total Abonado">
            {!! $errors->first('already_paid', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4"><i class="fas fa-check"></i>
            Guardar Hospitalización</button>
    </div>
</div>