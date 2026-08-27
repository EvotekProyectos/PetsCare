<div class="modal fade" id="petQuickCreateModal" tabindex="-1" aria-labelledby="petQuickCreateModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark text-uppercase" id="petQuickCreateModalTitle">Nueva mascota</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="cancelPetQuickCreate()"></button>
            </div>
            <form id="petQuickCreateForm">
                @csrf
                <div class="modal-body">

                    {{-- Se muestra cuando receptionModal.family_id ya tenía valor al abrir --}}
                    <div id="qc_family_readonly_section" class="mb-3" style="display: none;">
                        <label class="form-label">Familia</label>
                        <div class="form-control-plaintext fw-bold" id="qc_family_readonly_text">—</div>
                        <input type="hidden" id="qc_family_id_fixed" value="">
                    </div>

                    {{-- Se muestra cuando receptionModal.family_id estaba vacío al abrir --}}
                    <div id="qc_family_picker_section" style="display: none;">
                        <label class="form-label d-block">Familia</label>
                        <div class="btn-group mb-2 qc-family-mode" role="group" aria-label="Modo de familia">
                            <input type="radio" class="btn-check" name="qc_family_mode" id="qc_family_mode_existing"
                                value="existing" checked>

                            <label class="btn" for="qc_family_mode_existing">
                                Familia existente
                            </label>

                            <input type="radio" class="btn-check" name="qc_family_mode" id="qc_family_mode_new"
                                value="new">

                            <label class="btn" for="qc_family_mode_new">
                                Nueva familia
                            </label>
                        </div>

                        <div id="qc_family_existing_fields" class="mb-3">
                            <select id="qc_existing_family_id" class="form-control select2" style="width: 100%;">
                                <option value="">Selecciona la familia</option>
                                @foreach ($families as $familyOption)
                                    <option value="{{ $familyOption->id }}">
                                        {{ str_pad($familyOption->id, 4, '0', STR_PAD_LEFT) }}-{{ $familyOption->name }}
                                        Tel.{{ $familyOption->phone }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="qc_family_new_fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_name" class="form-label">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_name" class="form-control"
                                        placeholder="Nombre de la familia/propietario">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_phone" class="form-label">Teléfono <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_phone" class="form-control"
                                        placeholder="Teléfono identificador">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_email" class="form-label">Correo electrónico <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_address" class="form-label">Dirección <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_address" class="form-control"
                                        placeholder="Dirección">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_contact_name" class="form-label">Contacto
                                        Autorizado <span class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_contact_name" class="form-control"
                                        placeholder="Nombre de Contacto Autorizado">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_contact_number" class="form-label">Teléfono Contacto
                                        Autorizado <span class="text-danger">*</span></label>
                                    <input type="text" id="qc_family_contact_number" class="form-control"
                                        placeholder="Teléfono Contacto Autorizado">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="qc_family_classification_id" class="form-label">Clasificación</label>
                                    <select id="qc_family_classification_id" class="form-control">
                                        <option value="">Selecciona una clasificación</option>
                                        @foreach ($FamClassifications as $classification)
                                            <option value="{{ $classification->id }}">{{ $classification->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                    </div>

                    {{-- Datos de la mascota: siempre visibles --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_name" class="form-label">Nombre <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="qc_pet_name" class="form-control"
                                    placeholder="Nombre de la mascota">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_chip" class="form-label">#CHIP</label>
                                <input type="text" id="qc_pet_chip" class="form-control"
                                    placeholder="Número chip (opcional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_species_id" class="form-label">Especie <span
                                        class="text-danger">*</span></label>
                                <select id="qc_pet_species_id" class="form-control">
                                    <option value="">Selecciona la especie</option>
                                    @foreach ($Species as $specie)
                                        <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_breed_id" class="form-label">Raza</label>
                                {{-- Poblado por JS (breedSelectCascade.js) al elegir especie;
                                     mismo endpoint que usa Form Pet. --}}
                                <select id="qc_pet_breed_id" class="form-control" disabled>
                                    <option value="">Selecciona primero una especie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_gender_id" class="form-label">Género <span
                                        class="text-danger">*</span></label>
                                <select id="qc_pet_gender_id" class="form-control">
                                    <option value="">Selecciona el género</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_reproductive_status_id" class="form-label">Estado
                                    Reproductivo <span class="text-danger">*</span></label>
                                <select id="qc_pet_reproductive_status_id" class="form-control">
                                    <option value="">Selecciona el estado</option>
                                    @foreach ($ReproductiveStatuses as $ReproductiveStatus)
                                        <option value="{{ $ReproductiveStatus->id }}">
                                            {{ $ReproductiveStatus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_birthday" class="form-label">Fecha de nacimiento</label>
                                <input type="date" id="qc_pet_birthday" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_weight" class="form-label">Peso</label>
                                <input type="text" id="qc_pet_weight" class="form-control"
                                    placeholder="Peso en kg (opcional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_physic_descrip" class="form-label">Descripción física</label>
                                <input type="text" id="qc_pet_physic_descrip" class="form-control"
                                    placeholder="Breve descripción de la mascota (opcional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="qc_pet_classification_id" class="form-label">Clasificación de la
                                    mascota</label>
                                <select id="qc_pet_classification_id" class="form-control">
                                    <option value="">Selecciona una clasificación</option>
                                    @foreach ($PetClassifications as $PetClassification)
                                        <option value="{{ $PetClassification->id }}">
                                            {{ $PetClassification->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm text-uppercase rounded-4"
                        onclick="cancelPetQuickCreate()">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
                        <i class="fas fa-plus"></i> Guardar mascota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
