window.onload = function () {
    fetchAndRenderData()
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}
let paymentHistory = [];

function fetchAndRenderData() {
    $.ajax({
        url: route('red-sheets.recap', Reception_Id),
        method: 'GET',
        success: function (response) {
            const Data = response.data.flat(); 
            const normalizedData = normalizeData(Data); 
            renderData(normalizedData); 
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        }
    });
}

function normalizeData(data) {
    return data.map(entry => ({
        ...entry,
        lab: entry.lab || null,
        imaging: entry.imaging || null,
        service: entry.service || null,
        surgery: entry.surgery || null,
        observations: entry.observations || 'Sin observaciones',
        vet: entry.vet || { name: 'Desconocido' },
    }));
}

let grandTotal = 0; 

function renderData(data) {
    const groupedData = data.reduce((acc, item) => {
        acc[item.day_count] = acc[item.day_count] || [];
        acc[item.day_count].push(item);
        return acc;
    }, {});

    grandTotal = 0;
    $('#table-container').empty(); 

    for (const [dayCount, entries] of Object.entries(groupedData)) {
        const dayHeader = `<h5>Día ${dayCount}</h5>`;
        $('#table-container').append(dayHeader);

        let total = 0;
        entries.forEach(entry => {
            if (entry.lab && entry.lab.PRECIO) {
                total += parseFloat(entry.lab.PRECIO);
            }
            if (entry.service && entry.service.PRECIO) {
                total += parseFloat(entry.service.PRECIO);
            }
            if (entry.imaging && entry.imaging.PRECIO) {
                total += parseFloat(entry.imaging.PRECIO);
            }
            if (entry.surgery && entry.surgery.PRECIO) {
                total += parseFloat(entry.surgery.PRECIO);
            }
        });
        grandTotal += total;

        const table = `
            <table class="table table-striped table-hover responsive w-100" style="background-color: #2596be;">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Observaciones</th>
                        <th>M.V.Z.</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    ${entries.map(entry => renderEntryRow(entry)).join('')}
                    <!-- Row for total price -->
                    <tr>
                        <td colspan="4"><strong>SubTotal de día</strong></td>
                        <td><strong>$${total.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
        $('#table-container').append(table);
    }

     const totalFinalSection = `
     <div class="total-final" style="margin-top: 20px; text-align: right; font-weight: bold;">
         <h4>Total Final: $${grandTotal.toFixed(2)}</h4>
     </div>
     <div id="payment-history-container" style="margin-top: 20px;">
         <h5>Historial de pagos</h5>
         <table id="payment-history" class="table table-striped table-hover responsive w-100" style="background-color: #2596be;">
             <thead>
                 <tr>
                       <th>Fecha</th>
                    
                       <th>Método de Pago</th>
                        <th>Monto</th>
                        <th>Comentarios</th>
                 </tr>
             </thead>
             <tbody>
             </tbody>
         </table>
     </div>
 `;
$('#table-container').append(totalFinalSection);
 }

 function handlePayment() {
 const modal = `
     <div id="payment-modal" class="modal" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Realizar pago</h5>
                    </div>
                    <div class="modal-body">
                        <h3>Total pendiente: <strong>$${grandTotal.toFixed(2)}</strong></h3>
                        
                        <div class="form-group">
                            <label for="payment-method">Método de pago:</label>
                            <select id="payment-method" class="form-control">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment-amount">Ingrese el monto a pagar:</label>
                            <input type="number" id="payment-amount" class="form-control" min="0" max="${grandTotal}" step="0.01" placeholder="Monto a pagar">
                        </div>
                        <div class="form-group">
                            <label for="payment-comments">Comentarios:</label>
                            <input type="text" id="payment-comments" class="form-control" placeholder="Comentarios del pago">
                        </div>
                    </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-primary" id="confirm-payment">Pagar</button>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                 </div>
             </div>
         </div>
     </div>
 `;
 $('body').append(modal);
 $('#payment-modal').modal('show');


 $('#confirm-payment').on('click', function () {
     const paymentAmount = parseFloat($('#payment-amount').val());
     const paymentMethod = $('#payment-method').val();
     const paymentComments = $('#payment-comments').val();


     if (isNaN(paymentAmount) || paymentAmount <= 0 || paymentAmount > grandTotal) {
         alert("Ingrese un monto válido.");
         return;
     }

     grandTotal -= paymentAmount; 

     const paymentDate = new Date().toLocaleString(); 

     paymentHistory.push({
            date: paymentDate,
            method: paymentMethod,
            amount: paymentAmount,
           
            comments: paymentComments || 'Sin comentarios',
        });

     $('#payment-history tbody').append(`
        <tr>
            <td>${paymentDate}</td>
            <td>${paymentMethod}</td>
            <td>$${paymentAmount.toFixed(2)}</td>
            
            <td>${paymentComments || 'Sin comentarios'}</td>
        </tr>
    `);

     $('#payment-modal').modal('hide');
     $('#payment-modal').remove();
     $('.total-final h4').text(`Total Final: $${grandTotal.toFixed(2)}`);

    async function showPaymentCompleted() {
        await Swal.fire({
            title: '¡Pago completado!',
            text: 'Gracias por su pago.',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
    }

    async function showPartialPayment(paymentAmount, grandTotal) {
        await Swal.fire({
            title: 'Pago realizado',
            text: `Pago de $${paymentAmount.toFixed(2)} realizado con éxito. Total pendiente: $${grandTotal.toFixed(2)}.`,
            icon: 'info',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
    });
}

// Ejemplo de uso
if (grandTotal === 0) {
    showPaymentCompleted();
} else {
    showPartialPayment(paymentAmount, grandTotal);
}
 });

 $('#payment-modal').on('hidden.bs.modal', function () {
     $('#payment-modal').remove();
 });
 }

 $(document).on('click', '.btn-primary.btn-sm', function () {
 handlePayment();
 });



function renderEntryRow(entry) {
    let rows = '';

    if (entry.lab) {
        rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.laboratory.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.lab.PRECIO}</td>
            </tr>
        `;
    }

    if (entry.imaging) {
        rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.img.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.imaging.PRECIO}</td>
            </tr>
        `;
    }

    if (entry.service) {
        rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.serv.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.service.PRECIO}</td>
            </tr>
        `;
    }
    if (entry.surgery) {
        rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surg.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.surgery.PRECIO}</td>
            </tr>
        `;
    }

    return rows;
}



var followtable = undefined;
$(document).ready(function () {
    followtable = $('#follow-ups').DataTable({
        ajax: route('followup.entry', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data) {
                    if (data) {
                        let date = new Date(data);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        return `${formattedDate} `;
                    }
                    return '';
                }
            },

            {
                data: 'time',
            },

            {
                data: 'details',
            },

            {
                data: 'temperature'
            },
            {
                data: 'systolic',
            },
            {
                data: 'diastolic',
            },
            {
                data: 'average',
            },
            {
                data: 'glycemia_level',
            },
            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },
        ],
    });
});

