<div class="modal fade" id="accountStatementModal" tabindex="-1" aria-labelledby="accountStatementModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="accountStatementModalTitle">Estado de cuenta</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="fw-bold">Paciente:</span>
                    <span class="text-muted" id="as_pet_name">—</span>
                    <span class="fw-bold ms-3">Familia:</span>
                    <span class="text-muted" id="as_family_name"></span>
                      <span id="as_account_info" style="display: none;"></span>
                </div>

                {{-- <div id="as_account_info" class="mb-3" style="display: none;"></div> --}}

                {{-- Una tabla por tipo de recepción cuando el episodio tuvo
                     traslados (ver accountStatement.js); si solo hay un
                     grupo se ve igual que antes, sin encabezado de sección. --}}
                <div id="as_items_container">
                    <div class="text-center text-muted py-3">Cargando...</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tfoot>
                            <tr>
                                <th class="text-end" style="border-top: 2px solid #dee2e6;">Total:</th>
                                <th class="text-end" id="as_total" style="border-top: 2px solid #dee2e6; width: 140px;">$0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Solo informativo: no se resta del Total de arriba (eso depende
                     de que el anticipo esté "pagado", flujo aún no implementado). --}}
                <div id="as_advance_payments_section" class="mt-3" style="display: none;">
                    <h6 class="fw-bold mb-1">Anticipos registrados</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Referencia</th>
                                    <th>Concepto</th>
                                    <th>Fecha</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="as_advance_payments_body"></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total anticipado:</th>
                                    <th class="text-end" id="as_advance_payments_total">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div id="as_close_warning" class="alert alert-warning py-2 px-3 mb-0" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="showAccountStatementPdf()">
                    <i class="fas fa-file-pdf"></i> Mostrar PDF
                </button>
                {{-- Mismo mecanismo que el botón "Enviar por WhatsApp" del
                     modal de Documentos (sendDocumentWhatsApp(), ver
                     receptions/index.js): envía el MISMO PDF que ya genera
                     "Mostrar PDF", nunca un documento aparte. --}}
                <button type="button" class="btn btn-outline-success btn-sm" id="as_whatsapp_btn"
                    onclick="sendAccountStatementWhatsApp()">
                    {{-- <i class="fab fa-whatsapp"></i> --}}
                     Compartir
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="as_close_account_btn" disabled
                    onclick="closeAccountFromModal()">
                    <i class="fas fa-lock"></i> Cerrar cuenta
                </button>
            </div>
        </div>
    </div>
</div>
