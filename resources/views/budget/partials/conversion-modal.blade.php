{{-- Modal "Convertir presupuesto a servicios" (ver BudgetConversionController/
     BudgetConversionService): reutiliza las líneas de un Presupuesto creado
     durante la Consulta para generar servicios reales de Hospitalización
     (RedSheet), sin volver a capturarlas a mano. El Presupuesto en sí no se
     edita desde aquí — este modal es de selección/confirmación, no de
     captura. Se llena vía JS (openBudgetConversionModal, en
     public/js/budgets/conversion-modal.js). Incluido únicamente en
     red-sheet/create.blade.php (Hospitalización). --}}
<div class="modal fade" id="BudgetConversionModal" tabindex="-1" aria-labelledby="BudgetConversionModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="BudgetConversionModalTitle">
                            Convertir presupuesto a servicios
                        </div>
                        <div class="small text-muted" id="budgetConversionSummary"></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">
                    Selecciona los servicios de este presupuesto que se agregarán a la hospitalización.
                    El precio de cobro será siempre el vigente al cerrar la cuenta, no el precio cotizado.
                </p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" id="budgetConversionSelectAll">
                                </th>
                                <th>Servicio</th>
                                <th>Tipo</th>
                                <th>Precio cotizado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="budgetConversionTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnConvertSelectedBudget">
                    <i class="fas fa-check"></i> Convertir seleccionados
                </button>
            </div>
        </div>
    </div>
</div>
