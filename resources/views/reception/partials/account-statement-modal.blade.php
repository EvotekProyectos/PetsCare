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

                <div id="as_close_warning" class="alert alert-warning py-2 px-3 mb-0" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="showAccountStatementPdf()">
                    <i class="fas fa-file-pdf"></i> Mostrar PDF
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="as_close_account_btn" disabled
                    onclick="closeAccountFromModal()">
                    <i class="fas fa-lock"></i> Cerrar cuenta
                </button>
            </div>
        </div>
    </div>
</div>
