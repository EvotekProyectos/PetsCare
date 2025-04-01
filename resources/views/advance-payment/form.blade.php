<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20" hidden>
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="reception_id"
                    class="form-control @error('reception_id') is-invalid @enderror"
                    value="{{ old('reception_id', $reception->id ?? $advancePayment?->reception_id) }}" id="reception_id"
                    placeholder="Reception Id">
            </div>
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="reference" class="form-label">{{ __('Reference') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
            <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $advancePayment?->reference) }}" id="reference" placeholder="Reference">
            </div>
            {!! $errors->first('reference', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="date" class="form-label">Fecha</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="tabler--calendar-time"></span></span>
                    <input type="datetime-local" name="date" class="form-control" id="date" 
                    value="{{ old('date', $fechaGuardada) }}" readonly>
                
            </div>
            {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="row mb-3">
            <div class="col-8 form-group mb-2 mb20">
                <div class="form-group mb-2 mb20">
                    <label for="concept" class="form-label">Concepto</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <input type="text" name="concept" class="form-control @error('concept') is-invalid @enderror"
                            value="{{ old('concept', $advancePayment?->concept) }}" id="concept"
                            placeholder="Concepto">
                    </div>
                    {!! $errors->first('concept', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-4 form-group mb-2 mb20">
                <div class="form-group mb-2 mb20">
                    <label for="amount" class="form-label">Monto a Pagar</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="f7--money-dollar"></span></span>
                        <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror"
                            value="{{ old('amount', $advancePayment?->amount) }}" id="amount" placeholder="Monto">
                    </div>
                    {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

        </div>


        <div class="form-group mb-2 mb20" hidden>
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
                <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                    value="{{ old('user_id', Auth::user()->id) }}" id="user_id" placeholder="User Id">
            </div>
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <div class="input-group mb-3">
                <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                    <span class="vaadin--lines-list"></span></span>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $advancePayment?->status) }}" id="status" placeholder="Status">
            </div>
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}

    </div>
    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Registrar</button>
    </div>
</div>
