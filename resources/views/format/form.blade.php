<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="row">
        
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


        <div class="form-group mb-2 mb20">
            <div class="form-group mb-2">
                <label for="format_type_id" class="form-label">TIPO DE FORMATO</label>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                        <span class="vaadin--lines-list"></span>
                    </span>

                <select name="format_type_id" class="form-control @error('format_type_id') is-invalid @enderror" id="format_type_id">
                    <option value="">Selecciona el tipo</option>
                    @foreach ($format_types as $format_type)
                    @if ($format_type->id != 4 && $format_type->id != 5)
                        <option value="{{ $format_type->id }}" {{ old('format_type_id', $format->format_type_id) == $format_type->id ? 'selected' : '' }}>
                            {{ $format_type->name }}
                        </option>
                        @endif

                    @endforeach
                </select>
                {!! $errors->first('format_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            
            
                    {{-- <div class="form-group mb-2 mb20">
            <label for="reception_id" class="form-label">{{ __('Reception Id') }}</label>
            <input type="text" name="reception_id" class="form-control @error('reception_id') is-invalid @enderror" value="{{ old('reception_id', $format?->reception_id) }}" id="reception_id" placeholder="Reception Id">
            {!! $errors->first('reception_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}


{{-- 
        <div class="form-group mb-2 mb20">
            <label for="format_pdf" class="form-label">{{ __('Format Pdf') }}</label>
            <input type="text" name="format_pdf" class="form-control @error('format_pdf') is-invalid @enderror" value="{{ old('format_pdf', $format?->format_pdf) }}" id="format_pdf" placeholder="Format Pdf">
            {!! $errors->first('format_pdf', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}


        <div class="col-12 mt-2 d-flex justify-content-end">
            <a id="generate-format-btn" class="btn btn-sm btn-primary" href="#"><i class="fas fa-plus"></i> {{ __('GENERAR FORMATO') }}</a>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectElement = document.getElementById('format_type_id');
                const generateButton = document.getElementById('generate-format-btn');
                const routes = {
                    1: '{{ route("format.hospital", $pet->id) }}',
                    2: '{{ route("format.alta", $pet->id) }}',
                    3: '{{ route("format.surgery", $pet->id) }}',
                    6: '{{ route("format.responsivaEG", $pet->id) }}',
                };
        
                selectElement.addEventListener('change', function () {
                    const selectedValue = selectElement.value;
                    if (routes[selectedValue]) {
                        generateButton.href = routes[selectedValue];
                    } else {
                        generateButton.href = '#'; 
                    }
                });
            });
        </script>
    </div>
</div>

@push('scripts')
<script>
    var ruta = "{{ asset('') }}";;
    var Pet_Id = {{ $pet->id }};
</script>

{{-- <script src="{{ asset('js/formats/create.js') }}" defer></script> --}}
@endpush 
