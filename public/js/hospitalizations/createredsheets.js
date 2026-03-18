window.onload = function () {
  fetchAndRenderData();
  if (Pic_id !== null) {
    let fileRoute = Pic_route.startsWith("/")
      ? Pic_route.substring(1)
      : Pic_route;
    $("#preview").attr("src", ruta + fileRoute);
  } else {
    $("#preview").attr("src", imgDefault);
  }
};

$(document).ready(function () {
  $("#service_type_id").select2({
    placeholder: "Añadir Servicio",
    width: "resolve",
  });
  $("#lab_type_id").select2({
    placeholder: "Añadir Laboratorio",
    width: "resolve",
  });
  $("#imaging_type_id").select2({
    placeholder: "Añadir Imagenologia",
    width: "resolve",
  });
  // $('#product_type_id').select2({
  //     placeholder: 'Añadir Cirugia',
  //      width: 'resolve'
  // });
  $("#product_type_id").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#ModalSurgeries"),
  });
});

$("#table-container").on("click", "#btnGenerarVale", function () {
  let redsheets = [];

  $(".descontar-stock:checked").each(function () {
    redsheets.push($(this).data("redsheet"));
  });

  if (redsheets.length === 0) {
    Swal.fire({
      icon: "warning",
      title: "Selecciona al menos un insumo",
    });
    return;
  }

  Swal.fire({
    title: "¿Generar vale?",
    text: "Se generará el documento para firma del médico",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, generar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(route("vouchers.store-products"), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      body: JSON.stringify({
        reception_id: Reception_Id,
        redsheets: redsheets,
      }),
    })
      .then((res) => res.json())
      .then((resp) => {
        if (resp.success) {
          window.open(route("vouchers.format", resp.voucher_id), "_blank");
        }
      });
  });
});

function generarVale() {
  let redsheets = [];

  $(".descontar-stock:checked").each(function () {
    let id = $(this).data("redsheet");
    redsheets.push(id);
  });

  //console.log(redsheets);
}

function fetchAndRenderData(showLoader = true) {
  if (showLoader) {
    $("#table-container").hide();
    $("#table-loader").show();
  }

  $.ajax({
    url: route("red-sheets.recap", Reception_Id),
    method: "GET",
    success: function (response) {
      const Data = response.data.flat();
      const normalizedData = normalizeData(Data);
      renderData(normalizedData);

      if (showLoader) {
        $("#table-loader").hide();
        $("#table-container").show();
      }
      // Arrancar o detener polling según si hay vales activos
      const hayValesActivos = normalizedData.some(
        (entry) => entry.add_voucher == "1",
      );

      if (hayValesActivos) {
        startPolling();
      } else {
        stopPolling();
      }
    },
  });
}

function normalizeData(data) {
  //console.log(data);
  return data.map((entry) => ({
    ...entry,
    lab: entry.lab || null,
    imaging: entry.imaging || null,
    service: entry.service || null,
    surgery: entry.surgery || null,
    observations: entry.observations || "Sin observaciones",
    vet: entry.vet || { name: "Desconocido" },
  }));
}

function renderData(data) {
  const groupedData = data.reduce((acc, item) => {
    acc[item.day_count] = acc[item.day_count] || [];
    acc[item.day_count].push(item);
    return acc;
  }, {});

  let grandTotal = 0;

  $("#table-container").empty();

  for (const [dayCount, entries] of Object.entries(groupedData)) {
    let total = 0;
    entries.forEach((entry) => {
      if (entry.lab && entry.lab.PRECIO) total += parseFloat(entry.lab.PRECIO);
      if (entry.service && entry.service.PRECIO)
        total += parseFloat(entry.service.PRECIO);
      if (entry.imaging && entry.imaging.PRECIO)
        total += parseFloat(entry.imaging.PRECIO);
      if (entry.surgery && entry.surgery.PRECIO)
        total += parseFloat(entry.surgery.PRECIO);
    });
    grandTotal += total;

    const dayHeader = `
            <div style="display:flex; align-items:center; gap:14px; margin: 2rem 0 .5rem;">
                <div style="
                    background: #0455a0;
                    color: #fff;
                    font-weight: 800;
                    font-size: 1rem;
                    padding: 6px 22px;
                    border-radius: 999px;
                    letter-spacing: .06em;
                    white-space: nowrap;
                ">DÍA ${dayCount}</div>
                <div style="flex:1; height:2px; background:linear-gradient(to right,#0455a0,#e2e8f0); border-radius:2px;"></div>
            </div>
        `;
    $("#table-container").append(dayHeader);

    const table = `
            <table class="table table-striped table-hover responsive w-100" style="background-color: #2596be;">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Observaciones</th>
                        <th>M.V.Z.</th>
                        <th>Precio</th>
                        <th>Vale</th>
                    </tr>
                </thead>
                <tbody>
                    ${entries.map((entry) => renderEntryRow(entry)).join("")}
                    <tr>
                        <td colspan="5"><strong>Subtotal de día</strong></td>
                        <td><strong>$${total.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
    $("#table-container").append(table);
  }

  const totalFinalSection = `
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 1.5rem;">
        <button id="btnGenerarVale" style="
            background: #16a34a;
            color: #fff;
            font-weight: 600;
            font-size: 0.78rem;
            border: 1.5px solid #16a34a;
            padding: 4px 14px;
            border-radius: 999px;
            white-space: nowrap;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        "
        onmouseover="this.style.background='#dcfce7'; this.style.color='#15803d';"
        onmouseout="this.style.background='#16a34a'; this.style.color=' #fff';"
        >
            Generar Vale
        </button>
        <div style="
            color: #0455a0;
            font-weight: 700;
            font-size: 1rem;
            background:#d3f0f3;
            border: 1.5px solid #0455a0;
            padding: 8px 24px;
            border-radius: 999px;
            white-space: nowrap;
        ">Total: $${grandTotal.toFixed(2)}</div>
    </div>
`;
  $("#table-container").append(totalFinalSection);
}

function renderEntryRow(entry) {
  let rows = "";

  if (entry.lab) {
    rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.laboratory.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>$${parseFloat(entry.lab.PRECIO).toFixed(2)}</td>
                <td></td>
             
            </tr>
        `;
  }

  if (entry.imaging) {
    rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.img.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>$${parseFloat(entry.imaging.PRECIO).toFixed(2)}</td>
                 <td></td>
            </tr>
        `;
  }

  if (entry.service) {
    let checkbox = "";

    if (entry.serv && entry.serv.ES_ALMACENABLE === "S") {
      if (entry.add_voucher == "0") {
        checkbox = `<input type="checkbox" 
                                   class="form-check-input descontar-stock border border-primary"
                    data-redsheet="${entry.id}">`;
      } else if (entry.add_voucher == "1") {
        const folio =
          entry.voucher_product?.find(
            (vp) => vp.voucher?.status?.toLowerCase() === "surtido",
          )?.voucher?.folio ??
          entry.voucher_product?.find(
            (vp) => vp.voucher?.status?.toLowerCase() === "pendiente",
          )?.voucher?.folio ??
          "Sin vale";

        checkbox = `<span class="badge bg-primary">
                ${folio}
              </span>`;
      }
    }

    rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.serv.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>$${parseFloat(entry.service.PRECIO).toFixed(2)}</td>
                <td >${checkbox}</td>
            </tr>
        `;
  }

  if (entry.surgery) {
    rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surg.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>$${parseFloat(entry.surgery.PRECIO).toFixed(2)}</td>
                 <td></td>
            </tr>
        `;
  }

  return rows;
}

async function NewEntry() {
  event.preventDefault();
  let url = route("red-sheets.store");
  let form = new FormData(document.getElementById("NewRedSheet"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardaron los procedimientos con exito",
      timer: 7000,
      showConfirmButton: true,
    });
    // table.ajax.reload();
    fetchAndRenderData();
    $("#lab_type_id").val("").trigger("change");
    $("#service_type_id").val("").trigger("change");
    $("#imaging_type_id").val("").trigger("change");
    $("#observations").val("");
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

async function OpenFollowUps() {
  document.getElementById("reception_id_followup").value = Reception_Id;
  console.log(Reception_Id);

  $("#ModalFollowUps").modal("show");
}

function CloseFollowUp() {
  document.getElementById("time").value = "";
  document.getElementById("details").value = "";
  document.getElementById("temperature").value = "";
  document.getElementById("systolic").value = "";
  document.getElementById("diastolic").value = "";
  document.getElementById("average").value = "";
  document.getElementById("glycemia_level").value = "";
  $("#ModalFollowUps").modal("hide");
}

async function AddFollowUp() {
  event.preventDefault();
  let url = route("follow-ups.store");
  let form = new FormData(document.getElementById("NewFollowUp"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardo el seguimiento con exito",
      timer: 7000,
      showConfirmButton: true,
    });
    followtable.ajax.reload();
    CloseFollowUp();
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

async function discharge(receptionID) {
  event.preventDefault();
  const result = await Swal.fire({
    title: "¿Dar de alta a este paciente?",
    text: "Por favor seleccione el tipo de alta para seguir el proceso",
    icon: "question",
    input: "select",
    inputOptions: getDischarges(altas),
    inputPlaceholder: "Selecciona el tipo de alta",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar.",
    cancelButtonText: "Cancelar.",
    inputValidator: (value) => {
      return new Promise((resolve) => {
        if (value === "") {
          resolve("Debes seleccionar un tipo de alta");
        } else {
          resolve();
        }
      });
    },
  });

  if (result.isConfirmed) {
    const selectedOption = result.value;

    switch (selectedOption) {
      case "1":
        normal(receptionID, selectedOption);
        break;
      case "2":
        volunteer(receptionID, selectedOption);
        break;
      case "3":
        death(receptionID, selectedOption);
        break;
    }
  }
}

async function normal(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    if (response.ok) {
      window.location.href = route("prescriptions.new", reception);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function volunteer(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    if (response.ok) {
      window.location.href = route("alta.voluntaria", reception);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function death(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    if (response.ok) {
      const result = await Swal.fire({
        title: "Alta por fallecimiento",
        text: "¿Desea iniciar el proceso de cremación con Pets Care?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, iniciar proceso",
        cancelButtonText: "No, solo registrar",
      });

      if (result.isConfirmed) {
        window.location.href = route("new.cremation", { id: reception });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: "Procesando...",
          text: "Por favor espera mientras procesamos la solicitud.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });

        let url3 = route("redsheet.pay", reception);
        let pet3 = await fetch(url3, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });
        let resp3 = await pet3.json();
        Swal.close();
        Swal.fire({
          icon: "success",
          title: "El folio para pagar el servicio es " + resp3,
          timer: 27000,
          showConfirmButton: true,
        }).then(() => {
          window.location.href = route("assignment.hospital");
        });
      }
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

function getDischarges(DischargeData) {
  return DischargeData.reduce((options, Discharges) => {
    options[Discharges.id] = Discharges.name;
    return options;
  }, {});
}

async function OpenSurgeries() {
  const receptionId = document.getElementById("reception_id_followup").value;

  const url = route("surgery.checkRequirements", receptionId);
  const response = await fetch(url);
  const data = await response.json();

  if (data.status === "ok") {
    $("#ModalSurgeries").modal("show");
  } else {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: data.message || "Ocurrió un error al verificar los requisitos.",
    });
  }
}
jQuery("#ModalSurgeries").on("shown.bs.modal", function () {
  jQuery(document).off("focusin.modal");
});

async function AddSurgery() {
  event.preventDefault();
  let url = route("surgeries.store");
  let form = new FormData(document.getElementById("NewSurgery"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardo la cirugia con exito",
      timer: 7000,
      showConfirmButton: true,
    });
    fetchAndRenderData();
    CloseSurgeries();
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

function CloseSurgeries() {
  document.getElementById("date").value = "";
  document.getElementById("product_type_id").value = "";
  document.getElementById("observations").value = "";
  $("#ModalSurgeries").modal("hide");
}

var followtable = undefined;
$(document).ready(function () {
  followtable = $("#follow-ups").DataTable({
    ajax: route("followup.entry", Reception_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: "created_at",
        render: function (data) {
          if (data) {
            let date = new Date(data);
            let formattedDate = date.toLocaleDateString("en-US", {
              year: "numeric",
              month: "short",
              day: "numeric",
            });
            return `${formattedDate} `;
          }
          return "";
        },
      },

      {
        data: "time",
      },

      {
        data: "details",
      },

      {
        data: "temperature",
      },
      {
        data: "systolic",
      },
      {
        data: "diastolic",
      },
      {
        data: "average",
      },
      {
        data: "glycemia_level",
      },
      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },
    ],
  });
});

async function Transfer() {
  event.preventDefault();
  Swal.fire({
    title: "Vas a trasladar a este paciente",
    icon: "question",
    html: `Decide cúal es el nuevo tipo de admisión`,
    input: "select",
    inputOptions: getAdm(admisiones),
    inputPlaceholder: "Selecciona la admisión",
    showCancelButton: true,
    confirmButtonText: "Asignar",
    cancelButtonText: "Cancelar",
    inputValidator: (value) => {
      return new Promise((resolve) => {
        if (value === "") {
          resolve("Debes seleccionar una admisión");
        } else {
          resolve();
        }
      });
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      const form = new FormData();
      const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
      form.append("_token", token);
      form.append("_method", "PUT");
      form.append("admission_type_id", result.value);

      let url = route("reception.transfer", Reception_Id);
      let pet = await fetch(url, {
        method: "POST",
        body: form,
      });
      if (pet.ok) {
        window.location.reload();
      }
    }
  });
}

function getAdm(AdminssionData) {
  return AdminssionData.reduce((options, Adminssion) => {
    options[Adminssion.id] = Adminssion.name;

    return options;
  }, {});
}

//Tabla vales
var tableVoucher = undefined;
$(document).ready(function () {
  tableVoucher = $("#tableVoucher").DataTable({
    ajax: route("vouchers.listForReception", Reception_Id),
    columns: [
      {
        data: "folio",
      },
      {
        data: "created_at",
        render: function (data) {
          if (data) {
            let date = new Date(data);
            let formattedDate = date.toLocaleDateString("en-US", {
              year: "numeric",
              month: "short",
              day: "numeric",
            });
            return `${formattedDate} `;
          }
          return "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },
      {
        data: "voucher_products",
        render: function (data) {
          if (!data || data.length === 0) return "Sin insumos";

          const visibles = data.slice(0, 2);
          const resto = data.slice(2);

          let html = visibles
            .map(
              (p) =>
                `<span class="badge bg-light text-dark border">${p.product?.NOMBRE ?? "—"}</span>`,
            )
            .join(" ");

          if (resto.length > 0) {
            const todosHtml = data
              .map((p) => `<li>${p.product?.NOMBRE ?? "—"}</li>`)
              .join("");

            html += `
                 <a href="javascript:void(0)"  class="badge bg-primary ms-1 ver-mas-insumos" 
                   data-bs-toggle="popover"
                   data-bs-html="true"
                   data-bs-content="<ul class='mb-0 ps-3'>${todosHtml}</ul>"
                   title="Todos los insumos">
                   +${resto.length} más
                </a>`;
          }

          return html;
        },
      },
      {
        data: "status",
        render: function (data) {
          const colores = {
            Creado: "secondary",
            Pendiente: "warning",
            Surtido: "success",
            Rechazado: "danger",
            Cancelado: "dark",
          };
          const color = colores[data] ?? "secondary";
          return `<span class="badge bg-${color}">${data}</span>`;
        },
      },
      {
        data: null,
        render: function (data) {
          let botones = `
            <a type="button" href="/storage/${data.generated_document}" target="_blank" 
               class="btn btn-sm text-primary" title="Ver vale">
                <span class="mdi--eye"></span>
            </a>`;

          if (data.status === "Pendiente") {
            botones += `
                <button type="button" class="btn btn-sm text-danger btnCancelarVale" 
                    data-id="${data.id}" title="Cancelar vale">
                    <span class="ic--baseline-cancel"></span>
                </button>`;
          }

          return botones;
        },
      },
    ],
  });
});

$("#tableVoucher").on("draw.dt", function () {
  $('[data-bs-toggle="popover"]').popover({
    trigger: "focus",
  });
});

$(document).on("click", ".btnCancelarVale", function () {
  const voucherId = $(this).data("id");

  Swal.fire({
    title: "¿Cancelar vale?",
    text: "Deberás ingresar el motivo y firmar para confirmar la cancelación.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Continuar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    window.open(route("vouchers.cancelFormat", voucherId), "_blank");
  });
});

function voucherTableReload() {
  tableVoucher.ajax.reload(null, false);
  fetchAndRenderData();
}

let pollingInterval = null;
let lastUpdate = null;

function startPolling() {
  if (pollingInterval) return; // ya está corriendo

  pollingInterval = setInterval(function () {
    $.ajax({
      url: route("vouchers.lastUpdate"),
      method: "GET",
      data: { reception_id: Reception_Id },
      success: function (response) {
        if (lastUpdate === null) {
          lastUpdate = response.last_update;
          return;
        }

        if (response.last_update !== lastUpdate) {
          lastUpdate = response.last_update;
          fetchAndRenderData(false);

          if (typeof tableVoucher !== "undefined") {
            tableVoucher.ajax.reload(null, false);
          }
        }
      },
    });
  }, 15000);
}

function stopPolling() {
  if (pollingInterval) {
    clearInterval(pollingInterval);
    pollingInterval = null;
    lastUpdate = null;
  }
}
