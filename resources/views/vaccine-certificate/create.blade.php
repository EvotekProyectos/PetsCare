@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Vaccine Certificate
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 id="card_title" class="text-primary text-uppercase">
                                <span class="solar--document-add-broken"></span>
                                VACUNAS Y DESPARASITACIONES
                            </h4>
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                {{-- <form  onsubmit="Register()"
                                    enctype="multipart/form-data"> --}}


                                @include('vaccine-certificate.form')

                                {{-- </form> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        async function Register() {
            event.preventDefault();
            let product1 = document.getElementById("product1").value;
            let product2 = document.getElementById("product2").value;
            let product3 = document.getElementById("product3").value;

            let vaccine_date = document.getElementById("next_application_date1").value;
            let intern_date = document.getElementById("next_application_date2").value;
            let extern_date = document.getElementById("next_application_date3").value;

            let formSent = false;

            if (product1 !== null && product1 !== "") {
                if (!vaccine_date) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo obligatorio',
                        text: 'Por favor, selecciona la proxima aplicación de vacuna para guardar los registros.'
                    });
                    return;
                }
                let url = route('vaccine-certificates.store');
                let form = new FormData(document.getElementById("NewVaccine"));
                let pet = await fetch(url, {
                    method: "POST",
                    body: form
                });
                if (pet.ok) {
                    // Swal.fire({
                    //     icon: "success",
                    //     title: "Se guardaron los registros",
                    //     timer: 7000,
                    //     showConfirmButton: true
                    // })
                    formSent = true;
                }
            }
            if (product2 !== null && product2 !== "") {
                if (!intern_date) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo obligatorio',
                        text: 'Por favor, selecciona la proxima desparacitación interna para guardar los registros.'
                    });
                    return;
                }
                let url = route('vaccine-certificates.store');
                let form = new FormData(document.getElementById("NewInterDeworming"));
                let pet = await fetch(url, {
                    method: "POST",
                    body: form
                });
                if (pet.ok) {
                    // Swal.fire({
                    //     icon: "success",
                    //     title: "Se guardaron los registros",
                    //     timer: 7000,
                    //     showConfirmButton: true
                    // })
                    formSent = true;
                }
            }
            if (product3 !== null && product3 !== "") {
                if (!extern_date) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo obligatorio',
                        text: 'Por favor, selecciona la proxima desparacitación externa para guardar los registros.'
                    });
                    return;
                }
                let url = route('vaccine-certificates.store');
                let form = new FormData(document.getElementById("NewExternDeworming"));
                let pet = await fetch(url, {
                    method: "POST",
                    body: form
                });
                if (pet.ok) {
                    // Swal.fire({
                    //     icon: "success",
                    //     title: "Se guardaron los registros",
                    //     timer: 7000,
                    //     showConfirmButton: true
                    // })
                    formSent = true;
                }
            }
            if (formSent) {
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Se han guardado los registros correctamente.",
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = route('vaccine-certificates.index');
                });
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "No se enviaron registros",
                    text: "Por favor, completa al menos una vacuna y/o desparacitación para guardar los registros."
                });
            }

        }
    </script>
@endpush
