<div class="modal fade" id="ModalFollowUps" tabindex="-1" aria-labelledby="ModalFollowUpsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="ModalFollowUpsTitle">Seguimiento</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="AddFollowUp()" id="NewFollowUp" role="form" enctype="multipart/form-data">
                    @csrf
                    @include('follow-up.form', ['followUp' => $followUp ?? new \App\Models\FollowUp(), 'hideSubmit' => true])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm followup-cancel-btn" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="NewFollowUp" class="btn btn-primary btn-sm followup-save-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>
