{{-- Modal "Historial de peso" (ver PetWeight): solo lectura, no permite
     editar ni eliminar mediciones — cada fila es una medición
     independiente. Se llena vía JS (openWeightHistoryModal, en
     public/js/pet-weights/index.js). --}}
<div class="modal fade" id="weightHistoryModal" tabindex="-1" aria-labelledby="weightHistoryModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="weightHistoryModalTitle">Historial de peso</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive service-table-wrapper">
                    <table class="table table-hover table-flat-rows table-header-solid responsive w-100">
                        <thead class="text-uppercase">
                            <tr>
                                <th>Fecha</th>
                                <th>Peso</th>
                                <th>Recepción</th>
                                <th>Registrado por</th>
                            </tr>
                        </thead>
                        <tbody id="weightHistoryTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
