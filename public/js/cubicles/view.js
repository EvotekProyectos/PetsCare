function showCubicleInfo(name, state) {
    const status = state ? 'Ocupado' : 'Disponible';
    const color = state ? 'gray' : 'blue';

    Swal.fire({
        title: `Información del Cubículo`,
        html: `
            <p><strong>Nombre:</strong> ${name}</p>
            <p><strong>Estado:</strong> <span style="color:">${state}</span></p>
        `,
        icon: 'info', 
        confirmButtonText: 'Cerrar'
    });
}


function showInfo(event, name, status) {
    const infoBox = document.getElementById('info-box');
     const infoName = document.getElementById('info-name');
     const infoStatus = document.getElementById('info-status');

    const offsetX = -300; 
    const offsetY = -50; 

    infoBox.style.display = 'block';
    infoBox.style.left = event.pageX + offsetX + 'px';
    infoBox.style.top = event.pageY + offsetY + 'px';


    infoBox.innerHTML = `<strong>Nombre:</strong> ${name}
    <br><strong>Estado:</strong> ${status}`;
    //infoStatus.style.color = status === 'Ocupado' ? 'red' : 'green';  
}



function hideInfo() {
    const infoBox = document.getElementById('info-box');
    infoBox.style.display = 'none';
}
