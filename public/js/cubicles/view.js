function showCubicleInfo(state, exit, pet, collar) {
    const status = state ? "Ocupado" : "Disponible";
    const color = state ? "gray" : "blue";

    if (state == "Ocupado") {
        Swal.fire({
            title: `Información del Cubículo`,
            html: `
           
            <p><strong>Estado:</strong> <span style="color:">${state}</span></p>
              <p><strong>Salida:</strong> ${exit}</p>
              <p><strong>Mascota:</strong> ${pet}</p>
               <p><strong>No.Collar:</strong> ${collar}</p>
        `,
            icon: "info",
            confirmButtonText: "Cerrar",
        });
    } else {
        Swal.fire({
            title: `Información del Cubículo`,
            html: `
           
            <p><strong>Estado:</strong> <span style="color:">${state}</span></p>
        `,
            icon: "info",
            confirmButtonText: "Cerrar",
        });
    }
}

// function showInfo(event, status, exit) {
//     const infoBox = document.getElementById("info-box");
//     const infoExit = document.getElementById("info-exit");
//     const infoStatus = document.getElementById("info-status");

//     const offsetX = -300;
//     const offsetY = -50;

//     infoBox.style.display = "block";
//     infoBox.style.left = event.pageX + offsetX + "px";
//     infoBox.style.top = event.pageY + offsetY + "px";

//     if (status == "Ocupado") {
//         infoBox.innerHTML = `
//     <br><strong>Estado:</strong> ${status}
//     <br><strong>Salida:</strong> ${exit ? exit : "N/A"}`;
//     } else {
//         infoBox.innerHTML = `
//     <br><strong>Estado:</strong> ${status}`;
//     }
// }

function showInfo(event, status, exit) {
    const infoBox = document.getElementById("info-box");
    const infoExit = document.getElementById("info-exit");
    const infoStatus = document.getElementById("info-status");

    const offsetX = -300;
    const offsetY = -50;

    infoBox.style.display = "block";
    infoBox.style.left = event.pageX + offsetX + "px";
    infoBox.style.top = event.pageY + offsetY + "px";

    if (status == "Ocupado") {
        infoBox.innerHTML = `
    <br><strong>Estado:</strong> ${status}
    <br><strong>Salida:</strong> ${exit ? exit : "N/A"}`;
    } else {
        infoBox.innerHTML = `
    <br><strong>Estado:</strong> ${status}`;
    }
}

function hideInfo() {
    const infoBox = document.getElementById("info-box");
    infoBox.style.display = "none";
}
