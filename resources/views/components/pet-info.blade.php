<div class="row align-items-start mt-2">

    {{-- Foto y nombre de la mascota --}}
    <div class="col-md-2 d-flex justify-content-center pt-2">
        <div class="form-group text-center">

            <img src="{{ asset('img/pet_pic.png') }}"
                 alt="Foto Mascota"
                 id="preview"
                 class="img-fixed"
                 style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;">

            <h5 class="mt-2 mb-1">
                {{ $pet->name }}
            </h5>

            <span class="badge rounded-pill px-3 py-1"
                  style="background-color: #E6F1FB; color: #0455A0; font-weight: 500;">
                Paciente
            </span>

        </div>
    </div>


    {{-- Información de la mascota --}}
    <div class="col-md-8">
        <div class="collapse show pt-3" id="petInfoCollapse">

            <div class="row g-3">

                {{-- Especie --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-paw pet-info-icon"></i>
                        Especie
                    </small>

                    <div class="{{ $pet->species ? 'fw-semibold' : 'text-muted' }}">
                        {{ $pet->species->name ?? 'No definido' }}
                    </div>
                </div>


                {{-- Raza --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-tag pet-info-icon"></i>
                        Raza
                    </small>

                    <div class="{{ $pet->breed ? 'fw-semibold' : 'text-muted' }}">
                        {{ $pet->breed->name ?? 'No definido' }}
                    </div>
                </div>


                {{-- Género --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-venus-mars pet-info-icon"></i>
                        Género
                    </small>

                    <div class="{{ $genreName ? 'fw-semibold' : 'text-muted' }}">
                        {{ $genreName ?? 'No definido' }}
                    </div>
                </div>


                {{-- Edad --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-birthday-cake pet-info-icon"></i>
                        Edad
                    </small>

                    <div class="fw-semibold">
                        {{ $years }} años
                        {{ $months }} meses
                        {{ $days }} días
                    </div>
                </div>


                {{-- Peso --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-weight pet-info-icon"></i>
                        Peso
                    </small>

                    <div class="{{ $pet->weight ? 'fw-semibold' : 'text-muted' }}">
                        {{ $pet->weight ? $pet->weight . ' kg' : 'No definido' }}
                    </div>
                </div>


                {{-- Estado reproductivo --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-notes-medical pet-info-icon"></i>
                        Estado reproductivo
                    </small>

                    <div class="{{ $reproductiveStatusName ? 'fw-semibold' : 'text-muted' }}">
                        {{ $reproductiveStatusName ?? 'No definido' }}
                    </div>
                </div>


                {{-- Clasificación --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-list pet-info-icon"></i>
                        Clasificación
                    </small>

                    <div class="{{ $classificationName ? 'fw-semibold' : 'text-muted' }}">
                        {{ $classificationName ?? 'No definido' }}
                    </div>
                </div>


                {{-- Fallecido --}}
                <div class="col-md-3 col-6 pet-info-col">
                    <small class="text-muted">
                        <i class="fas fa-heartbeat pet-info-icon"></i>
                        Fallecido
                    </small>

                    <div class="fw-semibold">
                        {{ $pet->deceased ? 'Sí' : 'No' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Descripción física --}}
    <div class="col-md-2" style="margin-left: -30px;">

        <div class="pet-description-card">

            <div class="pet-description-icon">
                <i class="fas fa-eye"></i>
            </div>

            <div class="pet-description-title">
                Descripción física
            </div>

            <div class="pet-description-text">
                {{ $pet->physic_descrip ?: 'Sin descripción registrada.' }}
            </div>

        </div>

    </div>

</div>