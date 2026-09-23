<style>
    /* Inputs de presupuesto */
    .budget-input {
        height: 38px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Subtítulo de sección (Servicios Médicos/Laboratorio/Imagenología),
       mismo estilo que budget/edit.blade.php (Edit Budget), en tamaño
       compacto -h5 completo ocupaba demasiado alto para un modal-. */
    .budget-section-title {
        color: #BEBEBE;
        margin-bottom: 0;
        font-size: 0.95rem;
        font-weight: 700;
    }

    /* Tabla de resultados más compacta: menos padding por celda que el
       table-hover por defecto de Bootstrap, sin reducir el tamaño de letra
       (legibilidad) ni el alto de los inputs/botones de arriba. */
    #BudgetTable th,
    #BudgetTable td {
        padding: 0.35rem 0.6rem;
        vertical-align: middle;
    }

    #ModalBudget .budget-row {
        margin-bottom: 0.5rem !important;
    }

    /* Select2 dentro de un input-group (ícono + select): Select2 fija un
       width inline en px al iniciarse (select2({width: "100%"}) en
       appointment-modal.js), que sumado al ícono (input-group-text)
       desbordaba el ancho del input-group y lo mandaba a un renglón nuevo
       -input-group tiene flex-wrap: wrap por defecto-. flex:1 1 auto +
       width:1% hace que comparta el espacio con el ícono como cualquier
       input normal, en vez de pedir 100% del contenedor. */
    #ModalBudget .input-group > .select2-container {
        flex: 1 1 auto;
        width: 1% !important;
    }

    #ModalBudget .input-group > .select2-container .select2-selection {
        height: 38px;
        display: flex;
        align-items: center;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* Fondo distintivo por tipo de servicio, mismos colores que ya usa la
       tabla de resultados (budgetTypeColors en appointment-modal.js) para
       que el color signifique lo mismo en todo el modal. */
    #serviceRows {
        background-color: rgba(13, 148, 136, 0.10);
        border-radius: 8px;
        padding: 8px;
    }

    #labRows {
        background-color: rgba(124, 58, 237, 0.10);
        border-radius: 8px;
        padding: 8px;
    }

    #imgRows {
        background-color: rgba(234, 88, 12, 0.10);
        border-radius: 8px;
        padding: 8px;
    }

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

    #budget-total-price {
        color: #0455a0;
        font-weight: 700;
        white-space: nowrap;
    }
</style>
<div class="modal fade" id="ModalBudget" tabindex="-1" aria-labelledby="ModalBudgetTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:24px;"></div>
                    <div>
                        {{-- Mismo título/ícono que budget/edit.blade.php (Edit Budget),
                             para que se sienta la misma pantalla, en tamaño compacto. --}}
                        <div class="fw-bold fs-5 text-dark text-uppercase" id="ModalBudgetTitle">
                            <span class="ic--baseline-price-change"></span> Presupuesto
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-3">
                <div class="col-12" id="previousBudgetsSection" style="display:none;">
                    <div class="mb-3">
                        <div class="fw-bold text-uppercase mb-1" style="font-size:12px; color:#6c757d;">
                            Presupuestos de esta consulta
                        </div>
                        <div id="previousBudgetsList"></div>
                    </div>
                </div>

                {{-- Cada sección (Servicios Médicos/Laboratorio/Imagenología) reproduce
                     el subtítulo gris de Edit Budget (ver budget/edit.blade.php). El
                     botón "Añadir" agrega una fila de captura más -NO guarda nada
                     todavía-: es exactamente el mismo botón "+" de siempre
                     (addBudgetRow(), ver public/js/budgets/appointment-modal.js),
                     solo con la etiqueta/estilo de Edit Budget. No se duplica lógica:
                     sigue siendo la única función que agrega filas para las 3 categorías. --}}
                <div class="row g-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="budget-section-title">Servicios Médicos</h5>
                            <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                                onclick="addBudgetRow('service')">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>

                        <div id="serviceRows"></div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="budget-section-title">Exámenes de Laboratorio</h5>
                            <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                                onclick="addBudgetRow('lab')">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>
                        <div id="labRows"></div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="budget-section-title">Imagenología</h5>
                            <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                                onclick="addBudgetRow('img')">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>
                        <div id="imgRows"></div>
                    </div>

                    {{-- "Agregar" guarda en el presupuesto todas las filas capturadas
                         arriba (AddBudgetBatch(), sin cambios): no tiene equivalente
                         directo en Edit Budget -ahí cada línea se guarda sola al
                         capturarla-, pero sigue siendo necesario aquí para no meter
                         una llamada AJAX por cada fila. --}}
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm text-uppercase rounded-4"
                            onclick="AddBudgetBatch(event)">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive w-100" id="BudgetTable">
                                <thead class="thead table-primary text-uppercase">
                                    <tr>
                                        <th>Tipo Servicio</th>
                                        <th>Nombre</th>
                                        <th>Notas</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Mismo "Gran Total: $0.00" de Edit Budget, fuera de la tabla.
                     Sigue siendo el mismo #budget-total-price de siempre -no cambia
                     nada del cálculo, solo dónde vive el elemento en el HTML-. --}}
                <div class="col-12 mt-2 d-flex justify-content-end">
                    <h5 class="mb-0">Gran Total: <span id="budget-total-price">$0.00</span></h5>
                </div>
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
