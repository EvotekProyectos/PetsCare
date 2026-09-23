{{-- Modal único de firma de vale — reemplaza las 4 pantallas completas
     (voucher.voucher/cancel/issue/reject) para las 4 acciones (generar/
     cancelar/surtir/rechazar). Se llena vía JS (openVoucherSignModal, en
     public/js/vouchers/sign-modal.js), no recibe datos del servidor al
     renderizarse. El PDF sigue generándose únicamente DESPUÉS de guardar la
     firma (ver VoucherController) — este modal no cambia ese orden, solo
     evita la navegación a otra pantalla. --}}
<div class="modal fade" id="voucherSignModal" tabindex="-1" aria-labelledby="voucherSignModalTitle"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="voucherSignModalTitle">Firmar vale</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="voucherSignLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <div id="voucherSignContent" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold fs-5" id="voucherSignFolio"></span>
                        <span class="badge" id="voucherSignStatus"></span>
                    </div>

                    <div class="small text-muted mb-3" id="voucherSignPatientInfo"></div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th style="width: 110px;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="voucherSignProductsBody"></tbody>
                    </table>

                    <div id="voucherSignObservacionesWrap" class="mb-3" style="display: none;">
                        <label for="voucherSignObservaciones" class="form-label" id="voucherSignObservacionesLabel">
                            Observaciones
                        </label>
                        <textarea id="voucherSignObservaciones" class="form-control" rows="3"></textarea>
                    </div>

                    {{-- Oculto por completo cuando la acción no requiere firma
                         (surtir/rechazar), ver requireSignature en sign-modal.js. --}}
                    <div id="voucherSignCanvasWrap" class="text-center">
                        <canvas id="voucherSignCanvas" width="300" height="120"
                            style="border: 1px solid #E5E7EB; border-radius: 6px; background: #fff;"></canvas>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                id="voucherSignClearBtn">
                                Limpiar firma
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="voucherSignConfirmBtn">Firmar</button>
            </div>
        </div>
    </div>
</div>
