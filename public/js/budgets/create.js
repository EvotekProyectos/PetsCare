$(document).ready(function () {
    $('#service_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });

    $('#lab_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });

    $('#img_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });
});

async function getprice(id){
    let url = route("budget-details.price", id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        let respuesta = await peticion.json()
        document.getElementById("price").value = respuesta.PRECIO
        // console.log(respuesta);
        

    }
}

async function getimgprice(id){
    let url = route("budget-details.price", id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        let respuesta = await peticion.json()
        document.getElementById("priceimg").value = respuesta.PRECIO
        

    }
}

async function getLabprice(id){
    let url = route("budget-details.price", id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        let respuesta = await peticion.json()
        document.getElementById("pricelab").value = respuesta.PRECIO
        

    }
}

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('budget-details.list', Budget_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.serv ? 'Médico': data.img ? 'Imageneologia': data.lab ? 'Laboratorio': '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.serv ? data.serv.NOMBRE: data.img ? data.img.NOMBRE: data.lab ? data.lab.NOMBRE: '';
                }
            },
            {
                data: 'notes',
            },
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.price) || 0;
                    return `$${precio.toFixed(2)}`;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteBudgetDetail(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },

        ],
    });
    table.on('draw', function () {
        calculateTotal(table);
    });
});

function calculateTotal(table) {
    let total = 0;

    table.rows({ page: 'all' }).every(function () {
        const data = this.data();
        if ( data.price) {
            total += parseFloat(data.price);
        }
    });
    total += parseFloat(Base_Price);
    
    document.getElementById("total").value = total
    $('#total-price').text(`Gran Total: $${total.toFixed(2)}`);
}

async function NewEntry() {
    event.preventDefault();
    let url = route('budget-details.store');
    let form = new FormData(document.getElementById("details"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se registraron los servicios con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
        $('#service_id').val('').trigger('change')
        $('#price').val('')
        $('#notes').val('')
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function NewImg() {
    event.preventDefault();
    let url = route('budget-details.store');
    let form = new FormData(document.getElementById("imgs"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se registraron los servicios con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
        $('#img_id').val('').trigger('change')
        $('#priceimg').val('')
        $('#notesimg').val('')
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function NewLab() {
    event.preventDefault();
    let url = route('budget-details.store');
    let form = new FormData(document.getElementById("labs"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se registraron los servicios con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
        $('#lab_id').val('').trigger('change')
        $('#pricelab').val('')
        $('#noteslab').val('')
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function generate(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espera mientras procesamos la solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        let url = route('budget.new.total', Budget_Id);
        let form = new FormData(document.getElementById("budget"));
        let pet = await fetch(url, { method: "POST", body: form });
        let resp = await pet.json();

        if (pet.ok) {
           Swal.close(); 
           window.location.href = route('budget.sign', Budget_Id);
        }
        

    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "Por favor, intenta nuevamente."
        });
    } finally {
        Swal.close();
    }
}