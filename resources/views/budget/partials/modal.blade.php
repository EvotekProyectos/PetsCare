<style>
    /* Inputs de presupuesto */
    .budget-input {
        height: 38px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Botón pequeño para agregar */
    .btn-add-budget {
        background-color: #007bff;
        /* azul */
        color: #fff;
        /* icono blanco */
        border: none;
        padding: 3px 6px;
        /* más pequeño */
        font-size: 0.75rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add-budget i {
        color: #fff;
        font-size: 0.85rem;
    }

    /* .btn-add-budget:hover {
        background: #0455a0;
        color: #fff;
    } */

    /* Botón eliminar */
    .btn-delete-budget {
        width: 32px;
        height: 32px;
        padding: 0;
        border: 1px solid #adb5bd;
        background: #fff;
        color: #6c757d;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s ease;
    }

    .btn-delete-budget:hover {
        background: #f1f3f5;
        border-color: #6c757d;
        color: #343a40;
    }

    .btn-delete-budget i {
        font-size: 13px;
    }

    #BudgetTable tfoot tr {
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
    }

    #BudgetTable tfoot th {
        color: #0455a0;
        font-weight: 700;
        padding: 10px 12px;
    }

    #budget-total-price {
        color: #0455a0;
        font-weight: 700;
        white-space: nowrap;
    }
</style>
<div class="modal fade" id="ModalBudget" tabindex="-1" aria-labelledby="ModalBudgetTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="ModalBudgetTitle">Presupuesto</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="col-12" id="previousBudgetsSection" style="display:none;">
                    <div class="mb-4">
                        <div class="fw-bold text-uppercase mb-2" style="font-size:13px; color:#6c757d;">
                            Presupuestos de esta consulta
                        </div>
                        <div id="previousBudgetsList"></div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label class="form-label mb-0">SERVICIO MÉDICO</label>

                            <button type="button" class="btn-add-budget" onclick="addBudgetRow('service')"
                                title="Agregar servicio">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <div id="serviceRows"></div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label">LABORATORIO</label>
                            <button type="button" class="btn-add-budget" onclick="addBudgetRow('lab')"
                                title="Agregar laboratorio">
                                +
                            </button>
                        </div>
                        <div id="labRows"></div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label">IMAGENOLOGÍA</label>
                            <button type="button" class="btn-add-budget" onclick="addBudgetRow('img')"
                                title="Agregar imagenología">
                                +
                            </button>
                        </div>
                        <div id="imgRows"></div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="AddBudgetBatch()">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-flat-rows responsive w-100" id="BudgetTable">
                                <thead class="thead table-header-solid text-uppercase">
                                    <tr>
                                        <th>Tipo Servicio</th>
                                        <th>Nombre</th>
                                        <th>Notas</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">
                                            Total:
                                        </th>
                                        <th id="budget-total-price">
                                            $0.00
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-12 d-flex justify-content-end mt-2">
                    <div id="budget-total-price"
                        style="
                        color: #0455a0;
                        font-weight: 700;
                        font-size: 1rem;
                        background: #d3f0f3;
                        border: 1.5px solid #0455a0;
                        padding: 8px 24px;
                        border-radius: 999px;
                        white-space: nowrap;
                    ">
                        Total: $0.00</div>
                </div> --}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm text-uppercase rounded-4"
                    data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                    onclick="generateBudget(event)">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
