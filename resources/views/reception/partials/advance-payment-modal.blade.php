{{-- Modal de creación rápida de anticipo — reemplaza la navegación a
     advance-payments/add/{reception}. Se llena vía JS (openAdvancePaymentModal,
     en public/js/receptions/advancePayment.js). reception_id viaja como
     variable JS (contexto del botón que se clickeó), nunca como campo
     editable; account_id NUNCA se envía desde aquí — lo resuelve
     AdvancePaymentController::store() a partir de reception_id. La fecha
     tampoco se captura: el backend la asigna con now().

     También se reutiliza como modal de "Pago de consulta"
     (openConsultaPaymentModal, mismo JS): #ap_balance_info y el
     readonly/min de los campos de abajo los controla ese JS, no hay markup
     duplicado ni un segundo modal. --}}
<div class="modal fade" id="advancePaymentModal" tabindex="-1" aria-labelledby="advancePaymentModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="advancePaymentModalTitle">Crear anticipo</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="advancePaymentForm">
                <div class="modal-body">
                    {{-- Lista única de conceptos: Consulta + servicios hospitalarios
                         juntos (ReceptionController::paymentSummary(), campo
                         "servicios") — la Consulta ya no es un mensaje aparte, es un
                         renglón más de esta misma lista (con su propio nombre/precio
                         reales, ver AccountStatementService::consultaServiciosPreview()).
                         Los ids/clases se conservan (#ap_hospitalizacion_*) para no
                         tocar más de lo necesario; solo cambia qué se pinta adentro.
                         Solo se llena/muestra en modo "consulta" (Recepción, pestaña
                         Hospitalización, ver openConsultaPaymentModal() en
                         advancePayment.js). El desglose de Consulta vs. anticipo
                         hospitalario (para saber qué corresponde a cada uno) se sigue
                         calculando aparte — ver #ap_balance_info abajo. --}}
                    <div class="d-none mb-3" id="ap_hospitalizacion_section">
                        <div class="fw-bold text-uppercase mb-1" style="font-size:12px; color:#6c757d;">
                            Servicios
                        </div>
                        <ul class="list-unstyled mb-2" id="ap_hospitalizacion_services" style="font-size:13px;"></ul>
                        <div class="d-flex justify-content-between" style="font-size:13px;">
                            <span class="text-muted">Total servicios</span>
                            <span class="fw-bold" id="ap_hospitalizacion_total">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size:13px;">
                            <span class="text-muted">Anticipo requerido</span>
                            <span class="fw-bold" id="ap_hospitalizacion_anticipo">$0.00</span>
                        </div>
                        <hr class="my-2">
                    </div>

                    <div class="alert alert-info py-2 d-none" id="ap_balance_info"></div>

                    <div class="form-group mb-3">
                        <label for="ap_concept" class="form-label">Concepto</label>
                        <input type="text" id="ap_concept" class="form-control" placeholder="Concepto del anticipo">
                        <div class="invalid-feedback" id="ap_concept_error"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="ap_amount" class="form-label">Monto a pagar</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="ap_amount" class="form-control" min="0.01" step="0.01"
                                placeholder="0.00">
                        </div>
                        <div class="invalid-feedback" id="ap_amount_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="advancePaymentSubmitBtn">
                        <i class="fas fa-check"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
