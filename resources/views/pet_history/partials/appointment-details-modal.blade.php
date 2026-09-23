{{-- Modal "Ver Detalles" de una Consulta, usado únicamente desde el
     Historial de la mascota (pet_history/view.blade.php, botón "Ver
     Detalles" cuando el tipo de recepción es Consulta — ver Details() en
     public/js/pet-history/appointment-details-modal.js). NO reemplaza a
     appointment/show.blade.php: esa vista de página completa sigue
     intacta y sigue siendo el destino para los demás tipos de recepción.

     Se llena vía AJAX (openConsultaDetailsModal) contra
     AppointmentController::detailsModal(), que ya devuelve el HTML de
     Registro clínico/Fórmula médica pre-renderizado reutilizando los
     mismos partials de solo lectura que usa appointment/show.blade.php
     (appointment.form-readonly / prescription.form-readonly) — nada de
     campos se duplica aquí. Servicios reutiliza tal cual el endpoint
     appointment-services.registros (mismo que ya usa appointments/show.js)
     en un DataTable propio de este modal. --}}
<div class="modal fade" id="consultaDetailsModal" tabindex="-1" aria-labelledby="consultaDetailsModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-5 text-dark" id="consultaDetailsModalTitle">Detalle de consulta</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4" id="consultaDetailsLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <div id="consultaDetailsContent" class="d-none">
                    {{-- Registro clínico: siempre se muestra --}}
                    <h5 class="text-uppercase mb-2" style="color: #0455A0; font-size: 1rem;">Registro clínico</h5>
                    <div id="consultaDetailsRegistro"></div>

                    {{-- Servicios: solo si la consulta tiene alguno --}}
                    <div id="consultaDetailsServiciosWrapper" class="d-none mt-3">
                        <h5 class="text-uppercase mb-2" style="color: #0455A0; font-size: 1rem;">Servicios</h5>
                        <div class="table-responsive service-table-wrapper">
                            <table class="table table-hover table-flat-rows table-header-solid responsive w-100"
                                id="consultaDetailsServicesTable">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nombre</th>
                                        <th>Observaciones</th>
                                        <th>M.V.Z.</th>
                                        <th>Vale</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Fórmula médica: solo si ya se registró una --}}
                    <div id="consultaDetailsPrescriptionWrapper" class="d-none mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="text-uppercase mb-0" style="color: #0455A0; font-size: 1rem;">Fórmula médica</h5>
                            {{-- href lo arma el JS con route('prescription.imprimir', prescription_id)
                                 al llenar el modal (ver openConsultaDetailsModal()) — mismo endpoint
                                 que ya usa el botón de appointment/historic.blade.php. --}}
                            <a type="button" id="consultaDetailsPrintPrescription" href="#" target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-print"></i> Imprimir
                            </a>
                        </div>
                        <div id="consultaDetailsPrescription"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
