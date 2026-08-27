<div class="modal fade" id="ModalFollowupIntern" tabindex="-1" aria-labelledby="ModalFollowupInternTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="ModalFollowupInternTitle">Pase de guardia — Internos</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="AddFollowupIntern()" id="NewFollowupIntern" role="form" enctype="multipart/form-data">
                    @csrf
                    @include('followup-intern.form', ['followupIntern' => $followupIntern ?? new \App\Models\FollowupIntern(), 'hideSubmit' => true])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm followup-cancel-btn" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="NewFollowupIntern" class="btn btn-primary btn-sm followup-save-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>
