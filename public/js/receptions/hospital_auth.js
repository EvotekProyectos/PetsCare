const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const colorFondo = "white";

let dibujando;

const oMousePos = (elmnt, e) => {
    let Client = elmnt.getBoundingClientRect();
    e = e.touches ? e.touches[0] : e;

    return {
        x: Math.round(e.clientX - Client.left),
        y: Math.round(e.clientY - Client.top),
    };
};

const onStart = function(e) {
    m = oMousePos(this, e);
    ctx.beginPath();

    dibujando = true;
};

const onMove = function(e) {
    if (dibujando) {
        ctx.moveTo(m.x, m.y);
        m = oMousePos(this, e);
        ctx.lineTo(m.x, m.y);
        ctx.stroke();
    }
};

const onEnd = function(e) {
    dibujando = false;
}

canvas.onmousedown = onStart;
canvas.ontouchstart = onStart;
canvas.onmousemove = onMove;
canvas.ontouchmove = onMove;
canvas.onmouseup = onEnd;
canvas.onmouseout = onEnd;
canvas.ontouchend = onEnd;

document.querySelector(".btnLimpiar[data-target=canvas]").onclick = function() {
    ctx.fillStyle = colorFondo;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
};

document.querySelector(".btnEnviar[data-target=canvas]").click();

//     // $(document).ready(function(){
 $("form").on("submit", function(e) {
     const formData = new FormData(this);
      formData.append("signature", canvas.toDataURL());
 
      $.ajax({
        url:  "{{ route('hospital.list', $reception->id)}}",
        type: "post",
         headers: {
             "X-CSRF-Token": $("#csrf").attr("content"),
          },
          dataType: "json",
          contentType: false,
         processData: false,
         data: formData,
         beforeSend: () => {
//         // Esto se ejecuta antes de enviar la solicitud al back
//         // Si esta función devuelve false (estricto), la solicitud se cancela sin enviar
},
//   success: d => {
//      //if (d.success) { // Asegúrate de que la respuesta tenga esta propiedad
// //         // Redirigir a la nueva vista con la firma
//          window.location.href = "/reception/hospital/pdf/" + $reception->id;
// //      } else {
// //         // Manejar el caso en que no fue exitoso, quizás mostrar un mensaje
// //         alert("La operación no fue exitosa. Por favor, intenta de nuevo.");
// //      }
//   },
  error: d => {
     console.error("Error:", d); // Loguear el error
      alert("Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.");
 },
 complete: () => {
     // Esto se ejecuta después de success o error
},
});
});