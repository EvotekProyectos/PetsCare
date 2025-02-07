@extends('layouts.app')

{{-- @section('content')
<head>
    <style>
        .service-card {
            width: 100%;
            max-width: 250px;
            height: 100px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .service-card:hover {
            transform: scale(1.05);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .close {
            float: right;
            cursor: pointer;
        }
    </style>
</head>

<section class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card bg-primary-soft border-0 p-3">
                <div class="card-header bg-transparent border-0 text-center">
                    <h4 class="text-primary text-uppercase">DASHBOARD</h4>
                </div>
                
                <h5 class="text-uppercase text-muted text-center">DATOS GENERALES DEL DÍA</h5>
                
                <div class="row justify-content-center gap-3">
                    <div class="service-card consulta d-flex bg-white p-3 rounded-4 shadow-sm col-5 col-md-3 col-lg-2" data-type="consultas">
                        <div>
                            <p class="text-muted small">Consultas</p>
                            <h5 id="total-appointment" class="fw-bold">0</h5>
                        </div>
                    </div>
                    <div class="service-card hospital d-flex bg-white p-3 rounded-4 shadow-sm col-5 col-md-3 col-lg-2" data-type="hospital">
                        <div>
                            <p class="text-muted small">Hospital</p>
                            <h5 id="total-hospital" class="fw-bold">0</h5>
                        </div>
                    </div>
                    <div class="service-card grooming d-flex bg-white p-3 rounded-4 shadow-sm col-5 col-md-3 col-lg-2" data-type="grooming">
                        <div>
                            <p class="text-muted small">Grooming</p>
                            <h5 id="total-grooming" class="fw-bold">0</h5>
                        </div>
                    </div>
                    <div class="service-card hotel d-flex bg-white p-3 rounded-4 shadow-sm col-5 col-md-3 col-lg-2" data-type="hotel">
                        <div>
                            <p class="text-muted small">Hotel</p>
                            <h5 id="total-hotel" class="fw-bold">0</h5>
                        </div>
                    </div>
                </div>

                <h5 class="text-uppercase text-muted text-center mt-3">DATOS ESPECÍFICOS</h5>
                
                <div class="row mt-3 justify-content-center">
                    <div class="col-12 col-md-8 bg-white p-3 rounded-4 shadow-sm">
                        <canvas id="lineChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                </div>
                
                <div class="row mt-4 justify-content-center">
                    <div class="col-12 col-md-7 bg-white p-3 rounded-4 shadow-sm">
                        <canvas id="consultasChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                    <div class="col-12 col-md-4 bg-white p-3 rounded-4 shadow-sm mt-3 mt-md-0">
                        <canvas id="consultasMedicoChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection --}}


@section('content')
<head>
    <style>
        .service-card {
         width: 100%;
            max-width: 255px;
            height: 100px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .service-card:hover {
            transform: scale(1.05);
        }

        .service-card.consulta.selected {
            background-color: #74b7ff !important;
            color: white !important;
        }

        .service-card.hospital.selected  {
            background-color: #aefab4 !important;
            color: white !important;
        }
        .service-card.grooming.selected  {
            background-color: #ffad9e !important;
            color: white !important;
        }
        .service-card.hotel.selected  {
            background-color: #d1adff !important;
            color: white !important;
        }
        .service-card.cremacion.selected  {
            background-color: #fff8af !important;
            color: white !important;
        }
        .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    text-align: center;
}

.close {
    float: right;
    cursor: pointer;
}

    </style>
</head>

<section class="container-fluid">
   <div class="row justify-content-center">
        <div class="col-12">
        <div class="card bg-primary-soft border-0 p-3">
            <div class="card-header  bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <span class="mage--dashboard" style="font-size: 20px; "></span> DASHBOARD
                        </h4>
                 </div>
                       
                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                    DATOS GENERALES DEL DÍA
                </h5>

                
                <div class="row  gap-4">
                        <div class="service-card consulta d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="consultas">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Consultas</p>
                                <h5 id="total-appointment" style="font-size: 25px; font-weight: bold;">0</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #bfeeff; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="doctor" style="font-size: 18px;"></span>
                            </div>
                        </div>
                   
                  
                        <div class="service-card hospital d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hospital">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Hospital</p>
                                <h5  id="total-hospital" style="font-size: 25px; font-weight: bold;">0</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #d9ffdc; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="--hospital" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    
                        <div class="service-card grooming d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="grooming">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Grooming</p>
                                <h5  id="total-grooming" style="font-size: 25px; font-weight: bold;">0</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #ffe4df; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="scissors" style="font-size: 18px;"></span>
                            </div>
                        </div>
                   
                        <div class="service-card hotel d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hotel">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Hotel</p>
                                <h5  id="total-hotel" style="font-size: 25px; font-weight: bold;">0</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #dec5ff; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="--hotel" style="font-size: 18px;"></span>
                            </div>
                        </div>
                  
                        <div class="service-card cremacion d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="cremacion">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Cremación</p>
                                <h5  id="total-cremation" style="font-size: 25px; font-weight: bold;">0</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #fffbcb; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="urn" style="font-size: 18px;"></span>
                            </div>
                        </div>
                </div>


                <div class="row">
                    <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE; margin-top: 25px;">
                        DATOS ESPECÍFICOS
                    </h5>
                </div>
               

                <div style="display: flex; justify-content: flex-end; margin-bottom: 10px; margin">
                    <label for="name" class="form-label">FILTRAR POR: </label>
                        <div class="input-group mb-3" style="width: auto;">
                             <span class="input-group-text bg-primary-subtle" id="basic-addon1">
                                <span class="vaadin--lines-list"></span>
                            </span>
                            <select id="timeFilter">
                                <option value="week">Semana</option>
                                 <option value="month">Mes</option>
                                 <option value="year">Año</option>
                            </select>
                        </div>
                </div>


                <div class="row mt-4 justify-content-between align-items-center"> 
                    <div class="bg-white p-3 rounded-4 shadow-sm" style="width: 100%; height: 300px;">
                        <canvas id="lineChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>

                   
                </div>
           

                <div class="row mt-4 justify-content-between align-items-center">
                    <div class="bg-white p-3 rounded-4 shadow-sm" style="width: 70%; height: 300px;">
                        <canvas id="consultasChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
    
                    <div class="bg-white p-3 rounded-4 shadow-sm d-flex justify-content-center align-items-center" style="width: 30%; height: 300px;">
                        <canvas id="consultasMedicoChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

                
                <div id="myModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span style="text-align:right;"class="close">&times;</span>
                        <h6>onsultas por  </h6>
                        <table id="modalTable" class="table table-striped table-hover responsive w-100">
                            <thead class="thead table-primary text-uppercase">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Familia</th>
                                    <th>Mascota</th>
                                </tr>
                            </thead>
                            <tbody id="modalTableBody">
                            </tbody>
                        </table>
                        <a href="{{ route('receptions.index') }}">Ver más info</a>

                    </div>
                </div>

                
                
            </div>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

fetch("{{ route('dash.appointments') }}")
    .then(response => response.json())
    .then(data => {
        
        document.querySelector('#total-appointment').innerText = data.total_appointment;
        document.querySelector('#total-hospital').innerText = data.total_hospital;
        document.querySelector('#total-grooming').innerText = data.total_grooming;
        document.querySelector('#total-hotel').innerText = data.total_hotel;
        document.querySelector('#total-cremation').innerText = data.total_cremation;
    })
    .catch(error => console.error('Error:', error));


 document.addEventListener("DOMContentLoaded", function () {
         const serviceCards = document.querySelectorAll(".service-card");

         serviceCards.forEach(card => {
             card.addEventListener("click", function () {
                 // Remover la clase 'selected' de todos
                 serviceCards.forEach(c => c.classList.remove("selected"));
                
                 // Agregar la clase 'selected' al clickeado
                 this.classList.add("selected");
             });
         });
    });

    //Gráfica 1 semana,mes,año 
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('lineChart').getContext('2d');
    const timeFilter = document.getElementById('timeFilter');
    let chartInstance = null;

    function fetchData(time) {
        fetch(`{{ route('dash.appointmentsDays') }}?time=${time}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data.labels, data.current, data.last);
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    function updateChart(labels, currentData, lastData) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Periodo Actual',
                        data: currentData,
                        borderColor: 'rgba(18, 18, 18 , 1)',
                        backgroundColor: 'rgba(18, 18, 18 , 0.2)',
                        borderWidth: 1,
                        tension: 0.2,
                        pointRadius: 4
                    },
                    {
                        label: 'Periodo Anterior',
                        data: lastData,
                        borderColor: 'rgba(172,196,236, 1)',
                        backgroundColor: 'rgba(172,196,236, 0.2)',
                        borderWidth: 1,
                        borderDash: [5, 5],
                        tension: 0.2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Consultas realizadas',
                        font: { size: 18, weight: 'bold' },
                        padding: { top: 10, bottom: 20 }
                    },
                    legend: {
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { display: false } }
                }
            }
        });
    }

    timeFilter.addEventListener("change", function () {
        fetchData(this.value);
    });

    fetchData('week');
});


 //Gráfica 2 reasons de consultas
    document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('consultasChart').getContext('2d');
    const timeFilter = document.getElementById('timeFilter');

    const reasonLabels = {
        1: 'General',
        2: 'Seguimientos',
        3: 'Medicina Preventiva',
        4: 'Especialidad',
        5: 'Curación',
        6: 'Retiro de Sutura',
        7: 'Servicios externos',
        8: 'Estudios laboratorio'
    };

    let chartInstance = null;

    function fetchData(time) {
        fetch(`{{ route('dash.appointmentsReasons') }}?time=${time}`)
            .then(response => response.json())
            .then(data => {
                const datasetValues = Array(8).fill(0);
                
                data.forEach(item => {
                    if (reasonLabels[item.reason_id]) {
                        datasetValues[item.reason_id - 1] = item.total; 
                    }
                });

                updateChart(datasetValues);
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    function updateChart(data) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.values(reasonLabels),
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#5EB1E4', '#8C70AE', '#FF8160', '#FFF176',
                        '#82EAD1', '#FF6FB0', '#FF426D', '#C1F387'
                    ],
                    borderColor: [
                        '#5EB1E4', '#8C70AE', '#FF8160', '#FFF176',
                        '#82EAD1', '#FF6FB0', '#FF426D', '#C1F387'
                    ],
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                            font: { size: 12 },
                            callback: function(value) {
                                return this.getLabelForValue(value).split(' ').join('\n'); 
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { display: false }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Tipo de consulta',
                        font: { size: 18, weight: 'bold' },
                        padding: { top: 10, bottom: 20 }
                    },
                    legend: { display: false }
                }
            }
        });
    }

    // Cargar datos iniciales (por defecto semana)
    fetchData('week');

    // Evento para actualizar datos cuando cambia el filtro
    timeFilter.addEventListener('change', function() {
        fetchData(this.value);
    });
});
        

    //Gráfica 3 consultas por medico
    document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('consultasMedicoChart').getContext('2d');
    let chartInstance = null;
    const timeFilter = document.getElementById('timeFilter');

    function fetchData(time) {
        fetch(`{{ route('dash.appointmentsVets') }}?time=${time}`)
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.veterinarian);
                const values = data.map(item => item.total);

                updateChart(labels, values);
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    function updateChart(labels, values) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#9f9ff8', '#96e2d6', '#8c8c8c', '#92bfff', '#f39c12', '#e74c3c'].slice(0, labels.length),
                    borderColor: ['#9f9ff8', '#96e2d6', '#8c8c8c', '#92bfff', '#f39c12', '#e74c3c'].slice(0, labels.length),
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Consultas por médico',
                        font: { size: 18, weight: 'bold' },
                        padding: { top: 10, bottom: 20 }
                    },
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            font: { size: 14 },
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    },
                    tooltip: { enabled: true }
                },
                cutout: '60%',
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chartInstance.data.labels[index];
                        const value = chartInstance.data.datasets[0].data[index];

                        openModal3(label, value);
                    }
                }
            }
        });
    }

    // Escuchar cambios en el filtro
    timeFilter.addEventListener("change", function () {
        fetchData(this.value);
    });

    // Cargar datos iniciales (semana por defecto)
    fetchData('week');
});


function openModal3(veterinarianName, value) {
    fetch(`{{ route('dash.appointmentsVets') }}?time=week`)
        .then(response => response.json())
        .then(data => {
            const veterinarianData = data.find(item => item.veterinarian === veterinarianName);

            if (veterinarianData) {
                const modalTableBody = document.getElementById('modalTableBody');
                modalTableBody.innerHTML = ''; // Limpiar tabla antes de insertar nuevos datos

                document.querySelector('#myModal h6').innerText = `Consultas por ${veterinarianName}`;


                // Insertar los datos de las citas en la tabla del modal
                veterinarianData.appointments.forEach(appointment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${appointment.date}</td>
                        <td>${appointment.family}</td>
                        <td>${appointment.pet}</td>
                    `;
                    modalTableBody.appendChild(row);
                });

                // Mostrar el modal
                document.getElementById('myModal').style.display = 'block';
            }
        })
        .catch(error => console.error('Error al obtener los detalles de las citas:', error));
}

// Cerrar el modal al hacer clic en el botón de cierre
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('myModal').style.display = "none";
});

// Cerrar el modal si el usuario hace clic fuera de la ventana modal
window.onclick = function(event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = "none";
    }
};



</script>
@endpush 
