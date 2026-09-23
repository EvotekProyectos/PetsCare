{{-- Modal "Registrar peso" (ver PetWeight): cada envío crea una medición
     NUEVA en pet_weights, nunca edita una anterior. La fecha se genera en
     el backend (now()) — el input de abajo es solo informativo. Se llena
     vía JS (openRegisterWeightModal, en public/js/pet-weights/index.js).
     Incluido únicamente donde $showWeightActions se activa (ver
     components/pet-info.blade.php), hoy solo appointment/create.blade.php. --}}
<div class="modal fade" id="registerWeightModal" tabindex="-1" aria-labelledby="registerWeightModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="registerWeightModalTitle">Registrar nuevo peso</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="petWeightForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="pw_weight" class="form-label">Peso</label>
                        <div class="input-group">
                            <input type="number" id="pw_weight" class="form-control" min="0.01" step="0.01"
                                placeholder="0.0">
                            <span class="input-group-text">kg</span>
                        </div>
                        <div class="invalid-feedback" id="pw_weight_error"></div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label text-muted">Fecha de registro</label>
                        <div class="text-muted" id="pw_measured_at_info"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="petWeightSubmitBtn">
                        <i class="fas fa-check"></i> Registrar peso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
