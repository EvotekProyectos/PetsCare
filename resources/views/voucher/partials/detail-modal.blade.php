{{-- Modal compartido de detalle/cancelación de vale — usado por Consulta
     (appointment/create.blade.php) y Hospitalización (red-sheet/create.blade.php).
     Se llena vía JS (openVoucherDetailModal, en public/js/vouchers/detail-modal.js)
     al hacer clic en un badge de folio, no recibe datos del servidor al renderizarse. --}}
<div class="modal fade" id="voucherDetailModal" tabindex="-1" aria-labelledby="voucherDetailModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="voucherDetailModalTitle">
                            Detalle del vale
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="voucherDetailLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <div id="voucherDetailContent" style="display: none;">
                    {{-- Modo "detalle de un vale" (openVoucherDetailModal) --}}
                    <div id="voucherDetailSingle" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5" id="voucherDetailFolio"></span>
                            <span class="badge" id="voucherDetailStatus"></span>
                        </div>

                        <ul class="list-group mb-3" id="voucherDetailProducts"></ul>

                        <div id="voucherDetailExtra" class="small text-muted"></div>
                    </div>

                    {{-- Modo "historial de una fila" (openVoucherHistoryModal): puede haber
                         más de un vale (uno cancelado, luego uno nuevo generado). --}}
                    <div id="voucherHistoryList" style="display: none;"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="voucherDetailCancelBtn" class="btn btn-outline-danger btn-sm"
                    style="display: none;">
                    <i class="fas fa-ban"></i> Cancelar vale
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
