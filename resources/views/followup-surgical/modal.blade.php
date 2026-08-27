<div class="modal fade" id="ModalFollowupSurgical" tabindex="-1" aria-labelledby="ModalFollowupSurgicalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-3" style="width:6px;height:28px;"></div>
                    <div>
                        <div class="fw-bold fs-4 text-dark" id="ModalFollowupSurgicalTitle">Pase de guardia — Quirúrgicos</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="AddFollowupSurgical()" id="NewFollowupSurgical" role="form" enctype="multipart/form-data">
                    @csrf
                    @include('followup-surgical.form', ['followupSurgical' => $followupSurgical ?? new \App\Models\FollowupSurgical(), 'hideSubmit' => true])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm followup-cancel-btn" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="NewFollowupSurgical" class="btn btn-primary btn-sm followup-save-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>
