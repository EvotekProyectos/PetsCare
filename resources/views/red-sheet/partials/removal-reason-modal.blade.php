{{-- Modal informativo "Motivo de eliminación" (auditoría de eliminación de
     servicios de Redsheet, ver RedSheetController::removeService()). Solo
     lectura: no permite modificar el motivo, únicamente consultarlo. Se
     llena vía JS (openRemovalReasonModal, en public/js/hospitalizations/createredsheets.js)
     con datos que ya vienen en la tabla de servicios (entry.removal_reason /
     entry.removed_by_user), sin ningún fetch adicional al backend. --}}
<div class="modal fade" id="removalReasonModal" tabindex="-1" aria-labelledby="removalReasonModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="removalReasonModalTitle">
                            Motivo de eliminación
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <small class="text-muted">Eliminado por</small>
                    <div class="fw-semibold" id="removalReasonWho"></div>
                </div>
                <div>
                    <small class="text-muted">Motivo</small>
                    <div id="removalReasonText"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
