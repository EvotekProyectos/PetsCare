@extends('layouts.app')

@section('content')
<head>
    <style>
        .service-card {
            width: 250px;
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
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    text-align: center;
}

.close {
    float: right;
    cursor: pointer;
}

    </style>
</head>

<section class="container-fluid">
    <div class="row">
        <div class="col-12 d-flex flex-column align-items-center justify-content-center"> 
            <div class="card bg-primary-soft border-0 p-3">
                <div class="card-header bg-transparent border-0">

                    <div class="d-flex justify-content-between align-items-center">
                        
                        <h4 id="card_title" class="text-primary text-uppercase">
                            <span class="mage--dashboard" style="font-size: 20px; "></span> DASHBOARD
                        </h4>
                        
                    </div>
                </div>

                <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                    DATOS GENERALES DEL DÍA
                </h5>

                
                <div class="row ">
                   
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

                {{-- <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                    DATOS SEMANALES
                </h5> --}}

                <div class="row mt-4 justify-content-between align-items-center"> 
                    <div class="bg-white p-3 rounded-4 shadow-sm">
                        <canvas id="lineChart" style="width: 100%; height: 300px;"></canvas>
                    </div>

                   
                </div>
           

                {{-- <h5 id="card_title" class=" text-uppercase" style="color: #BEBEBE">
                    DATOS ESPECÍFICOS
                </h5> --}}

                {{-- <div class="row mt-4 justify-content-between align-items-center ">
                    <div class="bg-white p-3 rounded-4 shadow-sm align-items-center" style="width: 70%; height: 300px;">
                        <canvas id="consultasChart" style="width: 100%; "></canvas>
                    </div>
                    
                    <div class="bg-white p-3 rounded-4 shadow-sm d-flex justify-content-center align-items-center" style="width: 30%; height: 300px;">
                        <canvas id="consultasMedicoChart"></canvas>
                    </div>
                    
                  
                    
                </div> --}}

                <div class="row mt-4 justify-content-between align-items-center">
                    <!-- Gráfica de barras (70%) -->
                    <div class="bg-white p-3 rounded-4 shadow-sm" style="width: 70%; height: 300px;">
                        <canvas id="consultasChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                
                    <!-- Gráfica de médicos (30%) -->
                    <div class="bg-white p-3 rounded-4 shadow-sm d-flex justify-content-center align-items-center" style="width: 30%; height: 300px;">
                        <canvas id="consultasMedicoChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>

                <div id="myModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Detalles</h2>
                        <p id="modalText"></p>
                    </div>
                </div>
                
                {{-- <div class="row mt-4 justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm">
                    
                    <div class="col-lg-6 d-flex justify-content-center">
                        <canvas id="consultasMedicoChart"></canvas>
                    </div>
                </div> --}}
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

    //Gráfica 1 días 
    document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('lineChart').getContext('2d');

            // Mapeo de días de la semana (Lunes = 1, Domingo = 7)
            const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

            fetch("{{ route('dash.appointmentsDays') }}")
                .then(response => response.json())
                .then(data => {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: daysOfWeek, 
                            datasets: [
                                {
                                    label: 'Semana actual',
                                    data: data.current_week, 
                                    borderColor: 'rgba(18, 18, 18 , 1)',
                                    backgroundColor: 'rgba(18, 18, 18 , 0.2)',
                                    borderWidth: 1,
                                    tension: 0.2,
                                    pointRadius: 4
                                },
                                {
                                    label: 'Semana anterior',
                                    data: data.last_week,
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
                            plugins: {
                                title: {
                            display: true,
                            text: 'Consultas realizadas al día', // Título agregado aquí
                            font: { size: 18, weight: 'bold' },
                            padding: { top: 10, bottom: 20 }
                        },
                                legend: {
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        //pointRadius: 1 
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { display: false }
                                    
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));
        });   



 //Gráfica 2 reasons de consultas
document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('consultasChart').getContext('2d');

            // Definir los labels según los IDs de reasons en la base de datos
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

            // Inicializar el array con ceros para cada categoría
            const datasetValues = Array(8).fill(0);

            fetch("{{ route('dash.appointmentsReasons') }}")
                .then(response => response.json())
                .then(data => {
                    // Mapear los datos obtenidos y asignarlos a su índice correspondiente
                    data.forEach(item => {
                        if (reasonLabels[item.reason_id]) {
                            datasetValues[item.reason_id - 1] = item.total; 
                        }
                    });

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.values(reasonLabels),
                            datasets: [{
                                //label: 'Número de Consultas',
                                data: datasetValues,
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
            callback: function(value, index, values) {
                return this.getLabelForValue(value).split(' ').join('\n'); 
            }
        }
    },
    y: {
        beginAtZero: true,
        grid: { display: false }
    }
}
,
                            plugins: {
                                title: {
                            display: true,
                            text: 'Consultas por tipo', // Título agregado aquí
                            font: { size: 18, weight: 'bold' },
                            padding: { top: 10, bottom: 20 }
                        },
                                legend: {
                                    display: false,
                                    labels: { font: { size: 14 } }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));
        });

        
    //Gráfica 3 cponsultas por medico


// document.addEventListener("DOMContentLoaded", function() {
//     const ctx = document.getElementById('consultasMedicoChart').getContext('2d');
//     let myChart;

//     fetch("{{ route('dash.appointmentsVets') }}")
//         .then(response => response.json())
//         .then(data => {
//             const labels = data.map(item => item.veterinarian);
//             const values = data.map(item => item.total);

//             const backgroundColors = ['#9f9ff8', '#96e2d6', '#8c8c8c', '#92bfff', '#f39c12', '#e74c3c'];
//             const borderColors = backgroundColors.map(color => color); 

//             new Chart(ctx, {
//                 type: 'doughnut',
//                 data: {
//                     labels: labels,
//                     datasets: [{
//                         data: values,
//                         backgroundColor: backgroundColors.slice(0, labels.length),
//                         borderColor: borderColors.slice(0, labels.length),
//                         borderWidth: 2
//                     }]
//                 },
//                 options: {
//                     responsive: true,
//                     plugins: {
//                         title: {
//                             display: true,
//                             text: 'Consultas por médico a la semana', // Título agregado aquí
//                             font: { size: 18, weight: 'bold' },
//                             padding: { top: 10, bottom: 20 }
//                         },
//                         legend: {
//                             display: true,
//                             position: 'right',
//                             labels: {
//                                 font: { size: 14 },
//                                 usePointStyle: true,
//                                 pointStyle: 'circle',
//                                 padding: 20
//                             }
//                         },
//                         tooltip: { enabled: true }
//                     },
//                     cutout: '60%',
//                     onClick: (event, elements) => {
//                         if (elements.length > 0) {
//                             const index = elements[0].index;
//                             const label = myChart.data.labels[index];
//                             const value = myChart.data.datasets[0].data[index];

//                             // Llamamos a la función para abrir el modal con los datos seleccionados
//                             openModal(label, value);
//                         }
//                     }
//                 }
//             });
//         })
//         .catch(error => console.error('Error al obtener los datos:', error));
// });

// // Función para abrir el modal
// function openModal(label, value) {
//     document.getElementById('modalText').innerText = `Médico: ${label}\nConsultas: ${value}`;
//     document.getElementById('myModal').style.display = "block";
// }

// // Cerrar el modal cuando se haga clic en la "X"
// document.querySelector('.close').addEventListener('click', () => {
//     document.getElementById('myModal').style.display = "none";
// });


document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('consultasMedicoChart').getContext('2d');
    let myChart; // Declaramos la variable en un ámbito accesible

    fetch("{{ route('dash.appointmentsVets') }}")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.veterinarian);
            const values = data.map(item => item.total);

            const backgroundColors = ['#9f9ff8', '#96e2d6', '#8c8c8c', '#92bfff', '#f39c12', '#e74c3c'];
            const borderColors = backgroundColors.map(color => color); 

            myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: backgroundColors.slice(0, labels.length),
                        borderColor: borderColors.slice(0, labels.length),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Consultas por médico a la semana',
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
                        console.log("Elementos clickeados:", elements); // 🔍 Verifica si elements contiene datos

                        if (elements.length > 0) {
                            const index = elements[0].index;

                            if (typeof index !== "undefined") {
                                const label = myChart.data.labels[index];
                                const value = myChart.data.datasets[0].data[index];

                                openModal(label, value);
                            } else {
                                console.error("No se pudo obtener el índice del elemento clickeado.");
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error al obtener los datos:', error));
});

// Función para abrir el modal
function openModal(label, value) {
    document.getElementById('modalText').innerText = `Médico: ${label}\nConsultas: ${value}`;
    document.getElementById('myModal').style.display = "block";
}

// Cerrar el modal cuando se haga clic en la "X"
document.querySelector('.close').addEventListener('click', () => {
    document.getElementById('myModal').style.display = "none";
});

</script>
@endpush 
