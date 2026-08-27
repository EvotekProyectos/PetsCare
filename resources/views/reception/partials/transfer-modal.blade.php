<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="transferModalTitle">Trasladar Recepción</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2 mb20">
                    <label for="transfer_reception_type_id" class="form-label text-uppercase">Trasladar a</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <select id="transfer_reception_type_id" class="form-select">
                            <option value="1">Consulta</option>
                            <option value="2">Hospitalización</option>
                            <option value="3">Grooming</option>
                            <option value="4">Hotel</option>
                            <option value="5">Cremación</option>
                        </select>
                    </div>
                </div>

                {{-- Solo Hospitalización (id=2) los requiere — ver toggleTransferAdmissionArea()
                     en transfer.js. Mismo catálogo ($admissions/$areas) que reception.form,
                     ya disponible en este scope porque este partial se incluye dentro de
                     reception/index.blade.php. IDs propios (transfer_*) para no chocar con
                     los del modal de creación/edición de recepción, que vive en la misma página. --}}
                <div class="form-group mb-2 mb20" id="transfer_adm_group" style="display:none">
                    <label for="transfer_admission_type_id" class="form-label text-uppercase">Admisión<span
                            class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <select id="transfer_admission_type_id" class="form-select">
                            <option value="">Selecciona el tipo de admisión</option>
                            @foreach ($admissions as $admission)
                                <option value="{{ $admission->id }}">{{ $admission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-2 mb20" id="transfer_area_group" style="display:none">
                    <label for="transfer_area_id" class="form-label text-uppercase">Área<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <select id="transfer_area_id" class="form-select">
                            <option value="">Selecciona el área</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Solo Consulta (id=1) lo requiere — ver toggleTransferAdmissionArea()
                     en transfer.js. Catálogo $reasons, mismo que reception.form. No
                     confundir con #transfer_reason de abajo (motivo del traslado en
                     texto libre, ya siempre obligatorio para cualquier destino). --}}
                <div class="form-group mb-2 mb20" id="transfer_reason_id_group" style="display:none">
                    <label for="transfer_reason_id" class="form-label text-uppercase">Motivo de consulta<span
                            class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span></span>
                        <select id="transfer_reason_id" class="form-select">
                            <option value="">Selecciona el motivo</option>
                            @foreach ($reasons as $reason)
                                <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-0">
                    <label for="transfer_reason" class="form-label text-uppercase">Motivo<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                            <span class="vaadin--lines-list"></span>
                        </span>
                        <textarea id="transfer_reason" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm text-uppercase rounded-4"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="button"class="btn btn-primary btn-sm text-uppercase rounded-4" id="transfer_submit_btn"
                    onclick="submitTransfer()">
                    <i class="fas fa-exchange-alt"></i> Trasladar
                </button>
            </div>
        </div>
    </div>
</div>
