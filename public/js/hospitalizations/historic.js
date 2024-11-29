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

function renderData(data) {
    
    const groupedData = data.reduce((acc, item) => {
        acc[item.day_count] = acc[item.day_count] || [];
        acc[item.day_count].push(item);
        return acc;
    }, {});

    let grandTotal = 0;
    
    $('#table-container').empty(); 

    for (const [dayCount, entries] of Object.entries(groupedData)) {
        const dayHeader = `<h5>Día ${dayCount}</h5>`;
        $('#table-container').append(dayHeader);

        let total = 0;
        entries.forEach(entry => {
            if (entry.lab && entry.lab.price) {
                total += parseFloat(entry.lab.price);
            }
            if (entry.service && entry.service.price) {
                total += parseFloat(entry.service.price);
            }
            if (entry.imaging && entry.imaging.price) {
                total += parseFloat(entry.imaging.price);
            }
            if (entry.surgery && entry.surgery.price) {
                total += parseFloat(entry.surgery.price);
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
    `;
    $('#table-container').append(totalFinalSection);
}


function renderEntryRow(entry) {
    let rows = '';

    if (entry.lab) {
        rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.lab.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.lab.price}</td>
            </tr>
        `;
    }

    if (entry.imaging) {
        rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.imaging.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.imaging.price}</td>
            </tr>
        `;
    }

    if (entry.service) {
        rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.service.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.service.price}</td>
            </tr>
        `;
    }
    if (entry.surgery) {
        rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surgery.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.surgery.price}</td>
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

