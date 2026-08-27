<style>
    .certificate-type-group,
    .certificate-type-group * {
        box-sizing: border-box;
    }

    /*
     * Selector de 3 opciones:
     * Vacunas / Desparasitación interna / Desparasitación externa
     */
    .certificate-type-group {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        width: 100%;
        max-width: none;
        margin-bottom: 16px;
    }

    .certificate-type-pill {
        display: flex;
        flex: 1 1 0;
        min-width: 0;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 12px 8px;
        border: 1.5px solid #dee2e6;
        border-radius: 16px;
        background-color: #fff;
        color: #495057;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        text-align: center;
        line-height: 1.2;
        cursor: pointer;
        min-height: 70px;
        transition:
            border-color .15s ease,
            background-color .15s ease,
            color .15s ease,
            box-shadow .15s ease;
    }

    .certificate-type-radio {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: -1px;
        overflow: hidden;
        white-space: nowrap;
    }

    .certificate-type-pill {
        display: flex;
        flex: 1 1 0;
        min-width: 0;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 10px 6px;
        border: 1.5px solid #dee2e6;
        border-radius: 16px;
        background-color: #fff;
        color: #495057;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        text-align: center;
        line-height: 1.2;
        cursor: pointer;
        min-height: 65px;
        transition:
            border-color .15s ease,
            background-color .15s ease,
            color .15s ease,
            box-shadow .15s ease;
    }

    .certificate-type-pill span.icon {
        font-size: 18px;
    }

    .certificate-type-radio:checked+.certificate-type-pill {
        border-color: #0455a0;
        background-color: rgba(4, 85, 160, 0.08);
        color: #0455a0;
        box-shadow: 0 2px 5px rgba(4, 85, 160, 0.08);
    }

    .certificate-type-radio:focus-visible+.certificate-type-pill {
        outline: 2px solid #0455a0;
        outline-offset: 2px;
    }

    /*
     * Tablas
     */
    .tabla-aplicaciones th {
        background: #f8f9fa;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        white-space: nowrap;
    }

    .tabla-aplicaciones td {
        vertical-align: middle;
        padding: 6px 6px;
    }

    /*
     * Badges para tipo de desparasitación
     */
    .badge-tipo-aplicacion {
        font-size: 0.68rem;
        text-transform: uppercase;
        padding: 4px 6px;
        border-radius: 8px;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-tipo-vacuna {
        background: rgba(4, 85, 160, 0.1);
        color: #0455a0;
    }

    .badge-tipo-interna {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .badge-tipo-externa {
        background: rgba(255, 193, 7, 0.15);
        color: #997404;
    }

    /*
     * Contenedor de las secciones.
     */
    #contenedorAplicaciones {
        padding-top: 5px;
    }

    /*
     * En pantallas pequeñas permitimos que los botones
     * se adapten mejor.
     */
    @media (max-width: 576px) {
        .certificate-type-group {
            max-width: 100%;
        }

        .certificate-type-pill {
            font-size: 10px;
            min-height: 60px;
            padding: 8px 4px;
        }

        .certificate-type-pill span.icon {
            font-size: 16px;
        }
    }
</style>


<form id="formCertificado" method="POST" action="{{ route('vaccine-certificates.store', $reception->pet_id) }}">

    @csrf

    <input type="hidden" id="pet_id" name="pet_id"
        value="{{ old('pet_id', $vaccineCertificate?->pet_id ?? $reception->pet_id) }}">

    <input type="hidden" id="reception_id" name="reception_id" value="{{ $reception->id }}">

    <input type="hidden" name="vet_id" value="{{ Auth::user()->id }}">


    {{-- ============================================================
         CATÁLOGO DE PRODUCTOS
         Se renderiza una sola vez y se clona para cada fila.
    ============================================================= --}}
    <select id="productOptionsTemplate" style="display:none">

        <option value="">
            Selecciona el producto
        </option>

        @foreach ($products as $product)
            <option value="{{ $product->ARTICULO_ID }}">
                {{ $product->NOMBRE }}
            </option>
        @endforeach

    </select>


    {{-- ============================================================
         SELECTOR DE TIPO
         3 opciones:
         1 = Vacuna
         2 = Desparasitación interna
         3 = Desparasitación externa
    ============================================================= --}}
    <div class="certificate-type-group mb-3" id="certificateTypeGroup">


        {{-- VACUNAS --}}
        <input type="radio" id="tipo_vacuna" name="tipo_certificado" value="vacuna" class="certificate-type-radio"
            checked>

        <label for="tipo_vacuna" class="certificate-type-pill">

            <span class="icon fluent-mdl2--vaccination"></span>

            <span>
                Vacunas
            </span>

        </label>


        {{-- DESPARASITACIÓN INTERNA --}}
        <input type="radio" id="tipo_interna" name="tipo_certificado" value="interna" class="certificate-type-radio">

        <label for="tipo_interna" class="certificate-type-pill">

            <span class="icon fluent-mdl2--bug-block"></span>

            <span>
                Desparasitación interna
            </span>

        </label>


        {{-- DESPARASITACIÓN EXTERNA --}}
        <input type="radio" id="tipo_externa" name="tipo_certificado" value="externa" class="certificate-type-radio">

        <label for="tipo_externa" class="certificate-type-pill">

            <span class="icon fluent-mdl2--bug-block"></span>

            <span>
                Desparasitación externa
            </span>

        </label>

    </div>


    {{-- ============================================================
         CONTENEDOR DE APLICACIONES
         Solo una sección se muestra a la vez.
    ============================================================= --}}
    <div id="contenedorAplicaciones">


        {{-- ========================================================
             SECCIÓN VACUNAS
        ========================================================= --}}
        <div id="seccionVacunas">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <span class="fw-semibold small text-uppercase" style="color:#0455A0;">

                    Vacunas a registrar

                </span>


                <button type="button" class="btn btn-outline-primary btn-sm rounded-4" id="btnAgregarVacuna">

                    <i class="fas fa-plus"></i>

                    Agregar

                </button>

            </div>


            <div class="table-responsive mb-2">

                <table class="table table-sm tabla-aplicaciones align-middle" id="tablaVacunas">

                    <thead>

                        <tr>

                            <th style="width:22%">
                                Producto
                            </th>

                            <th style="width:12%">
                                F. aplicación
                            </th>

                            <th style="width:15%">
                                Laboratorio
                            </th>

                            <th style="width:15%">
                                Lote
                            </th>

                            <th style="width:14%">
                                Próxima aplicación
                            </th>

                            <th style="width:18%">
                                Observaciones
                            </th>

                            <th style="width:4%"></th>

                        </tr>

                    </thead>


                    <tbody id="filasVacunas">
                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================
             SECCIÓN DESPARASITACIONES
             Se utiliza tanto para interna como externa.
        ========================================================= --}}
        <div id="seccionDesparasitaciones" style="display:none;">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <span class="fw-semibold small text-uppercase" style="color:#0455A0;">

                    Desparasitaciones a registrar

                </span>


                <button type="button" class="btn btn-outline-primary btn-sm rounded-4" id="btnAgregarDesparasitacion">

                    <i class="fas fa-plus"></i>

                    Agregar

                </button>

            </div>


            <div class="table-responsive mb-2">

                <table class="table table-sm tabla-aplicaciones align-middle" id="tablaDesparasitaciones">

                    <thead>

                        <tr>

                            <th style="width:10%">
                                Tipo
                            </th>

                            <th style="width:18%">
                                Producto
                            </th>

                            <th style="width:11%">
                                F. aplicación
                            </th>

                            <th style="width:12%">
                                Dosis
                            </th>

                            <th style="width:13%">
                                Última desp.
                            </th>

                            <th style="width:12%">
                                Próxima aplicación
                            </th>

                            <th style="width:20%">
                                Observaciones
                            </th>

                            <th style="width:4%"></th>

                        </tr>

                    </thead>


                    <tbody id="filasDesparasitaciones">
                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ============================================================
         BOTÓN GUARDAR
    ============================================================= --}}
    <div class="col-12 mt-2 d-flex justify-content-end">

        <button type="submit" class="btn btn-primary btn-sm text-uppercase rounded-4">
            Guardar
            <i class="fas fa-check-circle"></i>
        </button>

    </div>

</form>


{{-- ================================================================
     TEMPLATE FILA VACUNA
================================================================ --}}
<template id="tplFilaVacuna">

    <tr>

        <td>

            <select name="aplicaciones[__idx__][product]" class="form-select form-select-sm select2-producto"
                style="width:100%">
            </select>


            <input type="hidden" name="aplicaciones[__idx__][service_id]" value="1">

        </td>


        <td>

            <input type="date" name="aplicaciones[__idx__][application_date]" class="form-control form-control-sm">

        </td>


        <td>

            <input type="text" name="aplicaciones[__idx__][lab]" class="form-control form-control-sm"
                placeholder="Laboratorio">

        </td>


        <td>

            <input type="text" name="aplicaciones[__idx__][lote]" class="form-control form-control-sm"
                placeholder="Lote">

        </td>


        <td>

            <input type="date" name="aplicaciones[__idx__][next_application_date]"
                class="form-control form-control-sm">

        </td>


        <td>

            <input type="text" name="aplicaciones[__idx__][observations]" class="form-control form-control-sm"
                placeholder="Observaciones">

        </td>


        <td class="text-center">

            <button type="button" class="btn btn-sm btn-link text-danger btn-eliminar-aplicacion p-0">

                <i class="fas fa-trash"></i>

            </button>

        </td>

    </tr>

</template>


{{-- ================================================================
     TEMPLATE FILA DESPARASITACIÓN
================================================================ --}}
<template id="tplFilaDesparasitacion">

    <tr>

        <td>

            <span class="badge-tipo-aplicacion __badgeClass__">
                __tipoLabel__
            </span>


            <input type="hidden" name="aplicaciones[__idx__][service_id]" value="__tipo__">

        </td>


        <td>

            <select name="aplicaciones[__idx__][product]" class="form-select form-select-sm select2-producto"
                style="width:100%">
            </select>

        </td>


        <td>

            <input type="date" name="aplicaciones[__idx__][application_date]"
                class="form-control form-control-sm">

        </td>


        <td>

            <input type="text" name="aplicaciones[__idx__][dose]" class="form-control form-control-sm"
                placeholder="Dosis aplicada">

        </td>


        <td>

            <input type="date" name="aplicaciones[__idx__][last_deworming_date]"
                class="form-control form-control-sm">

        </td>


        <td>

            <input type="date" name="aplicaciones[__idx__][next_application_date]"
                class="form-control form-control-sm">

        </td>


        <td>

            <input type="text" name="aplicaciones[__idx__][observations]" class="form-control form-control-sm"
                placeholder="Observaciones">

        </td>


        <td class="text-center">

            <button type="button" class="btn btn-sm btn-link text-danger btn-eliminar-aplicacion p-0">

                <i class="fas fa-trash"></i>

            </button>

        </td>

    </tr>

</template>


@push('scripts')
    <script>
        $(function() {

            /*
            |--------------------------------------------------------------------------
            | CONTADOR GLOBAL
            |--------------------------------------------------------------------------
            */

            let contadorAplicaciones = 0;


            /*
            |--------------------------------------------------------------------------
            | CONFIGURACIÓN DE DESPARASITACIÓN
            |--------------------------------------------------------------------------
            */

            const TIPO_DESPARASITACION_LABELS = {

                '2': {
                    label: 'Interna',
                    badge: 'badge-tipo-interna'
                },

                '3': {
                    label: 'Externa',
                    badge: 'badge-tipo-externa'
                }

            };


            /*
            |--------------------------------------------------------------------------
            | PRODUCTOS
            |--------------------------------------------------------------------------
            */

            const opcionesProducto =
                $('#productOptionsTemplate').html();


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR SELECT2
            |--------------------------------------------------------------------------
            */

            function inicializarSelect2Producto($fila) {

                $fila.find('.select2-producto').select2({

                    width: '100%',

                    dropdownParent: $('#ModalCertificate'),

                    placeholder: 'Selecciona el producto'

                });

            }


            /*
            |--------------------------------------------------------------------------
            | AGREGAR VACUNA
            |--------------------------------------------------------------------------
            */
            function agregarFilaVacuna() {

                const tpl =
                    document
                    .getElementById('tplFilaVacuna')
                    .innerHTML
                    .replaceAll(
                        '__idx__',
                        contadorAplicaciones
                    );

                const $fila = $(tpl);

                $fila
                    .find('.select2-producto')
                    .html(opcionesProducto);

                // Fecha de aplicación = fecha actual
                $fila
                    .find('input[name$="[application_date]"]')
                    .val(new Date().toISOString().split('T')[0]);

                $('#filasVacunas')
                    .append($fila);

                inicializarSelect2Producto($fila);

                contadorAplicaciones++;
            }

            /*
            |--------------------------------------------------------------------------
            | AGREGAR DESPARASITACIÓN
            |--------------------------------------------------------------------------
            */

            function agregarFilaDesparasitacion() {

                const tipoSeleccionado =
                    $('input[name="tipo_certificado"]:checked').val();

                let serviceId;

                if (tipoSeleccionado === 'interna') {
                    serviceId = '2';
                } else if (tipoSeleccionado === 'externa') {
                    serviceId = '3';
                } else {
                    return;
                }

                const cfg =
                    TIPO_DESPARASITACION_LABELS[serviceId];

                const tpl =
                    document
                    .getElementById('tplFilaDesparasitacion')
                    .innerHTML
                    .replaceAll('__idx__', contadorAplicaciones)
                    .replaceAll('__tipo__', serviceId)
                    .replaceAll('__tipoLabel__', cfg.label)
                    .replaceAll('__badgeClass__', cfg.badge);

                const $fila = $(tpl);

                $fila
                    .find('.select2-producto')
                    .html(opcionesProducto);

                // Fecha de aplicación = fecha actual
                $fila
                    .find('input[name$="[application_date]"]')
                    .val(new Date().toISOString().split('T')[0]);

                $('#filasDesparasitaciones')
                    .append($fila);

                inicializarSelect2Producto($fila);

                contadorAplicaciones++;
            }

            /*
            |--------------------------------------------------------------------------
            | CAMBIO ENTRE VACUNA / INTERNA / EXTERNA
            |--------------------------------------------------------------------------
            */

            $('input[name="tipo_certificado"]').on(
                'change',
                function() {

                    const tipo = $(this).val();


                    if (tipo === 'vacuna') {

                        /*
                         * Mostrar tabla de vacunas.
                         */

                        $('#seccionVacunas').show();

                        $('#seccionDesparasitaciones').hide();

                    } else {

                        /*
                         * Mostrar tabla de desparasitaciones.
                         */

                        $('#seccionVacunas').hide();

                        $('#seccionDesparasitaciones').show();

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | PRIMERA FILA
            |--------------------------------------------------------------------------
            |
            | Al abrir el modal empezamos con una vacuna.
            |
            */

            agregarFilaVacuna();


            /*
            |--------------------------------------------------------------------------
            | BOTÓN AGREGAR VACUNA
            |--------------------------------------------------------------------------
            */

            $('#btnAgregarVacuna').on(
                'click',
                function() {

                    agregarFilaVacuna();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | BOTÓN AGREGAR DESPARASITACIÓN
            |--------------------------------------------------------------------------
            */

            $('#btnAgregarDesparasitacion').on(
                'click',
                function() {

                    agregarFilaDesparasitacion();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR FILA
            |--------------------------------------------------------------------------
            |
            | Se mantiene mínimo una fila POR TABLA.
            |
            */

            $(document).on(
                'click',
                '.btn-eliminar-aplicacion',
                function() {

                    const $tbody =
                        $(this).closest('tbody');


                    if ($tbody.find('tr').length > 1) {

                        /*
                         * Destruimos Select2 antes de eliminar
                         * la fila para evitar residuos en el DOM.
                         */

                        const $select =
                            $(this)
                            .closest('tr')
                            .find('.select2-producto');


                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }


                        $(this)
                            .closest('tr')
                            .remove();

                    } else {

                        Swal.fire({

                            icon: 'info',

                            title: 'No se puede eliminar',

                            text: 'Debe haber al menos una aplicación registrada.',

                            timer: 1500,

                            timerProgressBar: true,

                            showConfirmButton: false

                        });

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | RESET DEL MODAL
            |--------------------------------------------------------------------------
            */

            function resetCertificateModal() {

                /*
                 * Limpiar filas.
                 */

                $('#filasVacunas').empty();

                $('#filasDesparasitaciones').empty();


                /*
                 * Reiniciar contador.
                 */

                contadorAplicaciones = 0;


                /*
                 * Volver a Vacunas.
                 */

                $('#tipo_vacuna')
                    .prop('checked', true);


                $('#seccionVacunas')
                    .show();


                $('#seccionDesparasitaciones')
                    .hide();


                /*
                 * Crear primera fila de vacuna.
                 */

                agregarFilaVacuna();

            }


            /*
            |--------------------------------------------------------------------------
            | CUANDO SE CIERRA EL MODAL
            |--------------------------------------------------------------------------
            */

            $('#ModalCertificate').on(
                'hidden.bs.modal',
                resetCertificateModal
            );


            /*
            |--------------------------------------------------------------------------
            | SUBMIT DEL FORMULARIO
            |--------------------------------------------------------------------------
            */

            $('#formCertificado').on(
                'submit',
                function(e) {

                    e.preventDefault();


                    /*
                     * Validamos que exista al menos una fila.
                     */

                    const totalFilas =
                        $('#filasVacunas tr').length +
                        $('#filasDesparasitaciones tr').length;


                    if (totalFilas === 0) {

                        Swal.fire({

                            icon: 'warning',

                            title: 'Aplicaciones requeridas',

                            text: 'Agrega al menos una vacuna o desparasitación.',

                            timer: 1800,

                            timerProgressBar: true,

                            showConfirmButton: false

                        });

                        return;

                    }


                    const form =
                        document.getElementById(
                            'formCertificado'
                        );


                    const formData =
                        new FormData(form);


                    /*
                     * Enviar al backend.
                     */

                    fetch(
                            form.action, {
                                method: 'POST',

                                body: formData,

                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        )

                        .then(async (response) => {

                            if (!response.ok) {

                                const errorData =
                                    await response
                                    .json()
                                    .catch(() => null);


                                const message =
                                    errorData?.message ||

                                    (
                                        errorData?.errors ?
                                        Object
                                        .values(errorData.errors)
                                        .flat()
                                        .join(' ') :
                                        null
                                    ) ||

                                    'Ocurrió un problema al guardar los registros.';


                                throw new Error(message);

                            }


                            return response.json();

                        })

                        .then(() => {

                            /*
                             * Mensaje de éxito.
                             */

                            Swal.fire({

                                icon: 'success',

                                title: '¡Éxito!',

                                text: 'Se guardaron los registros correctamente.',

                                timer: 1000,

                                timerProgressBar: true,

                                showConfirmButton: false

                            });


                            /*
                             * Limpiar las tablas.
                             */

                            $('#filasVacunas').empty();

                            $('#filasDesparasitaciones').empty();


                            /*
                             * Reiniciar contador.
                             */

                            contadorAplicaciones = 0;


                            /*
                             * Volver a Vacunas.
                             */

                            $('#tipo_vacuna')
                                .prop('checked', true);


                            $('#seccionVacunas')
                                .show();


                            $('#seccionDesparasitaciones')
                                .hide();


                            /*
                             * Crear nueva fila de vacuna.
                             */

                            agregarFilaVacuna();


                            /*
                             * Actualizar servicios.
                             */

                            if (
                                typeof ServicesTable !== 'undefined' &&
                                ServicesTable
                            ) {

                                ServicesTable.ajax.reload();

                            }


                            /*
                             * Cerrar modal.
                             */

                            $('#ModalCertificate').modal('hide');

                        })

                        .catch((error) => {

                            Swal.fire({

                                icon: 'error',

                                title: 'Error al guardar',

                                text: error.message

                            });

                        });

                }
            );

        });
    </script>
@endpush
