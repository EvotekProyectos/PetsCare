<div class="modal fade" id="accountDetailModal" tabindex="-1" aria-labelledby="accountDetailModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="accountDetailModalTitle">
                            Detalle de la cuenta
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="accountDetailLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <div id="accountDetailContent" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="fw-bold fs-5" id="accountDetailPet"></div>
                            <div class="text-muted small" id="accountDetailFamily"></div>
                        </div>
                        <span class="badge" id="accountDetailStatus"></span>
                    </div>

                    <div class="small text-muted mb-3" id="accountDetailExtra"></div>

                    <ul class="list-group mb-3" id="accountDetailCharges"></ul>

                    <div class="d-flex justify-content-end">
                        <span class="fw-bold fs-5" id="accountDetailTotal"></span>
                    </div>
                </div>

                <div id="accountDetailEmpty" class="text-center text-muted py-4" style="display: none;">
                    Esta cuenta todavía no tiene cargos registrados.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
