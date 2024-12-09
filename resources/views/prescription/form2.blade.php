<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">

            {{-- <div class="col-md-6" hidden>
                <div class="form-group mb-2">
                    <label for="name" class="form-label">reception_id</label>
                    <div class="input-group mb-3">
                        <input type="text" name="reception_id"
                            class="form-control @error('reception_id') is-invalid @enderror"
                            value="{{ $reception->id }}" id="reception_id" placeholder="reception_id">
                        {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div> --}}
            

                <div class="col-md-6" hidden>
                    <div class="form-group mb-2">
                        <label for="name" class="form-label">pet_id</label>
                        <div class="input-group mb-3">
                            <input type="text" name="pet_id"
                                class="form-control @error('pet_id') is-invalid @enderror" value="{{ $pet->id }}"
                                id="pet_id" placeholder="pet_id">
                            {!! $errors->first('pet_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6" hidden>
                    <div class="form-group mb-2">
                        <label for="name" class="form-label">VET</label>
                        <div class="input-group mb-3">
                            <input type="text" name="veterinarian_id"
                                class="form-control @error('veterinarian_id') is-invalid @enderror"
                                value="{{ Auth::user()->id }}" id="veterinarian_id" placeholder="veterinarian_id">
                            {!! $errors->first(
                                'veterinarian_id',
                                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                            ) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6"  hidden>
                    <div class="form-group mb-2">
                        <label for="name" class="form-label">FECHA</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="lucide--calendar-clock "></span>
                            </span>
                            @php
                                $now = \Carbon\Carbon::now();
                            @endphp
                            <input type="datetime-local" name="date"
                                class="form-control @error('date') is-invalid @enderror" value="{{ $now }}"
                                id="date" placeholder="Date">
                            {!! $errors->first('date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="name" class="form-label">DIAGNÓSTICO</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <img src="{{ asset('img/consulta.png') }}" alt="Foto Mascota" id="preview"
                                    class="img-fixed" style="width: 20px; height: 20px; object-fit: cover; ">
                            </span>
                            <input type="text" name="diagnosis"
                                class="form-control @error('diagnosis') is-invalid @enderror"
                                value="{{ old('diagnosis', $prescription?->diagnosis) }}" id="diagnosis_prescription"
                                placeholder="Diagnóstico">
                            {!! $errors->first('diagnosis', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
        </div>


        <div class="d-flex justify-content-between align-items-center">
            <h5 class=" text-uppercase">
                MEDICAMENTOS
            </h5>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">NOMBRE , PRESENTACIÓN, CANTIDAD Y FORMA DE
                        ADMINISTRACIÓN</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>
                        <textarea name="medicine" class="form-control @error('medicine') is-invalid @enderror" id="medicine"
                            placeholder="Nombres de los medicamentos, la presentación, cantidad y forma de administración." rows="4">{{ old('medicine', $prescription?->medicine) }}</textarea>
                        {!! $errors->first('medicine', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label for="name" class="form-label">OBSERVACIONES</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="icon-park-twotone--medicine-bottle-one"></span>
                        </span>

                        <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" id="observations"
                            placeholder="Observaciones del emisor" rows="4">{{ old('observations', $prescription?->observations) }}</textarea>
                        {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>

        </form>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-2 mb20">
                    <label for="day_next_check" class="form-label">PRÓXIMO CONTROL</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fas fa-calendar text-primary"></i>
                        </span>

                        <input type="date" name="day_next_check"
                            class="form-control @error('day_next_check') is-invalid @enderror"
                            value="{{ old('day_next_check', $prescription?->day_next_check) }}" id="day_next_check"
                            placeholder="Day Next Check">
                    </div>
                    {!! $errors->first(
                        'day_next_check',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>
                              
             <div class="col-md-3">
                <div class="form-group mb-2 mb20">
                    <label for="time_next_check" class="form-label">HORA</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <i class="fa fa-clock text-primary"></i>
                        </span>
                        <input type="time" name="time_next_check"
                            class="form-control @error('time_next_check') is-invalid @enderror"
                            value="{{ old('time_next_check' , $prescription?->time_next_check) }}" id="time_next_check"
                            placeholder="Time Next Check">
                    </div>
                    {!! $errors->first(
                        'time_next_check',
                        '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                    ) !!}
                </div>
            </div>

           <div class="col-md-6">
                 <div class="form-group mb-2 mb20">
                    <label for="reason_next_check_id" class="form-label">Tipo de proxima consulta</label>
                   <div class="input-group mb-3">
                         <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                             <span class="vaadin--lines-list"></span>
                         </span>
                         <select name="reason_next_check_id"
                             class="form-control @error('reason_next_check_id') is-invalid @enderror"
                             id="reason_next_check_id">
                             <option value=""> Selecciona el tipo</option>
                             @foreach ($reasons as $reason)
                                 <option value="{{ $reason->id }}" name="reason_next_check_id"
                                     {{ old('reason_next_check_id') == $reason->id ? 'selected' : '' }}>
                                     {{ $reason->name }}</option>
                             @endforeach
                         </select>
                     </div>
                     {!! $errors->first(
                         'reason_next_check_id',
                         '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                     ) !!}
                 </div>
            </div>
        </div>
    </div> 

    <div class="col-12 mt-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            <i class="fas fa-plus"></i>
            Guardar receta</button>
    </div>
    <form action=""></form>
</div>


 @push('scripts')
    <script>
        var ruta = "{{ asset('') }}";;
        var Pet_Id = {{ $pet->id }};
    </script>

    <script src="{{ asset('js/prescriptions/create.js') }}" defer></script>
@endpush 


