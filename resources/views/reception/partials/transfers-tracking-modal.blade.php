{{-- Modal compartido de tracking de traslados — usado desde receptions.index
     (botón por fila, ver public/js/receptions/index.js) y desde pet_history
     (botón a nivel mascota, ver resources/views/pet_history/view.blade.php).
     Se llena vía JS (openTransfersTrackingModal, en
     public/js/receptions/transfers-tracking.js) al hacer clic en esos
     botones — no recibe datos del servidor al renderizarse. --}}
<div class="modal fade" id="transfersTrackingModal" tabindex="-1" aria-labelledby="transfersTrackingModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="transfersTrackingModalTitle">
                            Tracking de traslados
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="transfersTrackingLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <div id="transfersTrackingContent" style="display: none;"></div>

                <div id="transfersTrackingEmpty" class="text-center text-muted py-4" style="display: none;">
                    No hay traslados registrados.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
