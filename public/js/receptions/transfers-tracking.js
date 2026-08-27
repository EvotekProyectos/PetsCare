// Modal de tracking de traslados (ver resources/views/reception/partials/transfers-tracking-modal.blade.php),
// abierto desde receptions.index (por reception_id, un solo episodio) y
// pet_history (por pet_id, todos los episodios de esa mascota con traslados).
// Reutiliza el mismo lenguaje visual .timeline-* ya usado en la bitácora de
// Hospitalización (public/css/redsheets/timeline.css, createredsheets.js).

// No existe hoy un mapa reception_type_id -> color/ícono en el proyecto
// (serviceTypeColors en appointments/create.js es por NOMBRE de servicio,
// areas en receptions/index.js es por área) — se define aquí.
const RECEPTION_TYPE_META = {
  1: { icon: "fas fa-stethoscope", color: "#0455A0", bg: "#E6F1FB", label: "Consulta" },
  2: { icon: "fas fa-hospital", color: "#F54245", bg: "#FDE8E8", label: "Hospitalización" },
  3: { icon: "fas fa-cut", color: "#16A34A", bg: "#E8F6EC", label: "Grooming" },
  4: { icon: "fas fa-hotel", color: "#ff7855", bg: "#FFEFE9", label: "Hotel" },
  5: { icon: "fas fa-fire", color: "#8C65B5", bg: "#F1EAF8", label: "Cremación" },
};
const DEFAULT_RECEPTION_TYPE_META = {
  icon: "fas fa-circle",
  color: "#6c757d",
  bg: "#F1F3F6",
  label: "Recepción",
};

async function openTransfersTrackingModal(params) {
  $("#transfersTrackingLoader").show();
  $("#transfersTrackingContent").hide().empty();
  $("#transfersTrackingEmpty").hide();

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("transfersTrackingModal"),
  ).show();

  try {
    const query = new URLSearchParams(params).toString();
    const resp = await fetch(
      route("receptions.transfers-tracking") + "?" + query,
    );

    if (!resp.ok) {
      throw new Error("request failed");
    }

    const data = await resp.json();

    $("#transfersTrackingLoader").hide();

    if (!data.episodes || data.episodes.length === 0) {
      $("#transfersTrackingEmpty").show();
      return;
    }

    const showEpisodeHeading = data.episodes.length > 1;
    const html = data.episodes
      .map((episode) => renderEpisodeTimeline(episode, showEpisodeHeading))
      .join("");

    $("#transfersTrackingContent").html(html).show();
  } catch (error) {
    $("#transfersTrackingLoader").hide();
    Swal.fire({
      icon: "error",
      title: "No se pudo cargar el tracking de traslados",
    });
    console.error("Error al cargar tracking de traslados:", error);
  }
}

function renderEpisodeTimeline(episode, showHeading) {
  const items = episode.chain
    .map((step) => {
      const meta = RECEPTION_TYPE_META[step.reception_type_id] || DEFAULT_RECEPTION_TYPE_META;
      const iconMarkup = `<i class="${meta.icon}"></i>`;

      const transferInfo = step.transfer_out
        ? `
            <div class="timeline-desc">
                Trasladado por ${step.transfer_out.created_by ?? "Desconocido"}
                ${step.transfer_out.reason ? " — " + step.transfer_out.reason : ""}
            </div>
            <div class="timeline-meta">${formatDate(step.transfer_out.created_at, true)}</div>
          `
        : "";

      return `
            <li class="timeline-item">
                <span class="timeline-icon" style="color:${meta.color}; background-color:${meta.bg};">${iconMarkup}</span>
                <div class="timeline-content">
                    <div class="timeline-title" style="color:${meta.color};">
                        ${meta.label} — ${formatDate(step.entry_date, true)}
                    </div>
                    <div class="timeline-meta">${step.vet ? "M.V.Z./Colaborador: " + step.vet : ""}</div>
                    ${transferInfo}
                </div>
            </li>
        `;
    })
    .join("");

  const heading = showHeading
    ? `<div class="timeline-heading">Episodio #${episode.episode_id}</div>`
    : "";

  return `<div class="timeline-wrapper mb-4">${heading}<ul class="timeline-list">${items}</ul></div>`;
}
