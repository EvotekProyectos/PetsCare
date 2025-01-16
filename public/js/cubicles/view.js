function showCubicleInfo(state,exit) {
    const status = state ? 'Ocupado' : 'Disponible';
    const color = state ? 'gray' : 'blue';

    Swal.fire({
        title: `Información del Cubículo`,
        html: `
           
            <p><strong>Estado:</strong> <span style="color:">${state}</span></p>
              <p><strong>Nombre:</strong> ${exit}</p>
        `,
        icon: 'info', 
        confirmButtonText: 'Cerrar'
    });
}


function showInfo(event, name, status, exit) {
    const infoBox = document.getElementById('info-box');
    const infoExit = document.getElementById('info-exit');
    const infoStatus = document.getElementById('info-status');

    const offsetX = -300; 
    const offsetY = -50; 

    infoBox.style.display = 'block';
    infoBox.style.left = event.pageX + offsetX + 'px';
    infoBox.style.top = event.pageY + offsetY + 'px';

    infoBox.innerHTML = `<strong>Nombre:</strong> ${name}
    <br><strong>Estado:</strong> ${status}
    <br><strong>Salida:</strong> ${exit ? exit : 'N/A'}`;
}

function hideInfo() {
    const infoBox = document.getElementById('info-box');
    infoBox.style.display = 'none';
}

