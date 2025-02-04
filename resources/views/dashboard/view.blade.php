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
    </style>
</head>

<section class="container-fluid">
    <div class="row">
        <div class="col-12 d-flex flex-column align-items-center justify-content-center"> 
            <div class="card bg-primary-soft border-0 p-3">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 id="card_title" class="text-primary text-uppercase">DASHBOARD</h4>
                    </div>
                </div>

                <div class="row d-flex flex-wrap justify-content-center gap-4">
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <div class="service-card consulta d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="consultas">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Consultas</p>
                                <h5 style="font-size: 25px; font-weight: bold;">3</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #bfeeff; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="doctor" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <div class="service-card hospital d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hospital">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Hospital</p>
                                <h5 style="font-size: 25px; font-weight: bold;">3</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #d9ffdc; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="--hospital" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <div class="service-card grooming d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="grooming">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Grooming</p>
                                <h5 style="font-size: 25px; font-weight: bold;">3</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #ffe4df; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="scissors" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <div class="service-card hotel d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hotel">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Hotel</p>
                                <h5 style="font-size: 25px; font-weight: bold;">3</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #dec5ff; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="--hotel" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <div class="service-card cremacion d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="cremacion">
                            <div>
                                <p style="margin-bottom: 5px; font-size: 15px; color: #333;">Cremación</p>
                                <h5 style="font-size: 25px; font-weight: bold;">3</h5>
                            </div>
                            <div style="width: 50px; height: 50px; background-color: #fffbcb; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="urn" style="font-size: 18px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div style="width: 100%; height: 400px;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-center mt-4">
                    <div class="col-lg-6 d-flex justify-content-center">
                        <canvas id="consultasChart"></canvas>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-center">
                        <canvas id="consultasMedicoChart"></canvas>
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

   
var ctx = document.getElementById('lineChart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
        datasets: [
            {
                label: 'Semana actual',
                data: [50, 100, 150, 200, 25, 30,70],
                borderColor: 'rgba(18, 18, 18 , 1)',
                backgroundColor: 'rgba(18, 18, 18 , 0.2)',
                borderWidth: 1,
                tension: 0.2,  // 🔹 Suaviza las curvas
               //pointStyle: 'circle', // 🔹 Puntos en forma de círculo
                pointRadius: 1, // 🔹 Tamaño de los puntos
                // pointBackgroundColor: 'rgba(75, 192, 192, 1)'
            },
            {
                label: 'Semana anterior',
                data: [30, 136, 150, 70, 250, 45,80],
                borderColor: 'rgba(172,196,236, 1)',
                backgroundColor: 'rgba(172,196,236, 0.2)',
                borderWidth: 1, // 🔹 Línea más delgada
                borderDash: [5, 5], // 🔹 Línea punteada
                tension: 0.2, // 🔹 Suaviza las curvas
                //pointStyle: 'circle', // 🔹 Puntos en forma de círculo
                pointRadius: 1,
                // pointBackgroundColor: 'rgba(192, 75, 75, 1)'
            }
        ]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true // 🔹 Hace que los indicadores sean círculos en la leyenda
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false // 🔹 Oculta las líneas de fondo en el eje X
                }
            },
            y: {
                grid: {
                    display: false // 🔹 Oculta las líneas de fondo en el eje Y
                }
            }
        }
    }
});

// document.addEventListener("DOMContentLoaded", function() {
//             const ctx = document.getElementById('consultasChart').getContext('2d');

           
//             const consultasChart = new Chart(ctx, {
//                 type: 'bar',
//                 data: {
//                     labels: ['General', 'Seguimientos', 'Medicina Preventiva', 'Especialidad','Curación', 'Retiro de Sutura', 'Servicios externos', 'Estudios laboratorio'],
//                     datasets: [{
//                         // label: 'Número de Consultas',
//                         data: [12, 8, 15, 5, 7,3,15,10], // Cambia estos valores dinámicamente con Blade
//                         backgroundColor: [
//                             '#5EB1E4',
//                             '#8C70AE',
//                             '#FF8160',
//                             '#FFF176',
//                             '#82EAD1',
//                             '#FF6FB0',
//                             '#FF426D',
//                             '#C1F387'
//                         ],
//                         borderColor: [
//                             '#5EB1E4',
//                             '#8C70AE',
//                             '#FF8160',
//                             '#FFF176',
//                             '#82EAD1',
//                             '#FF6FB0',
//                             '#FF426D',
//                             '#C1F387'
//                     ],
//                     borderWidth: 1,
//                     borderRadius: 10, // 🔹 Hace las barras redondeadas
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 scales: {
//                     x: {
//                 grid: {
//                     display: false // 🔹 Oculta las líneas de fondo en el eje X
//                 },
//                 ticks: {
//                     autoSkip: false, // No salta ningún tick (etiqueta)
//                     maxRotation: 0,  // 🔹 Asegura que los labels estén horizontales
//                     minRotation: 0,  // Evita rotar los labels
//                     font: {
//                         size: 12 // 🔹 Ajusta el tamaño de la fuente de los labels
//                     }
//                 }
//             },
                
//                     y: {
//                         beginAtZero: true,
//                         grid: {
//                             display: false // 🔹 Oculta las líneas de fondo en el eje Y
//                         }
//                     }
//                 },
//                 plugins: {
//                     legend: {
//                         display: true, // Muestra la leyenda
//                         labels: {
//                             font: {
//                                 size: 14
//                             }
//                         }
//                     }
//                 }
//             }
//         });
//     });

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

            // Obtener los datos desde Laravel
            fetch("{{ route('dash.info') }}")
                .then(response => response.json())
                .then(data => {
                    // Mapear los datos obtenidos y asignarlos a su índice correspondiente
                    data.forEach(item => {
                        if (reasonLabels[item.reason_id]) {
                            datasetValues[item.reason_id - 1] = item.total; // Asigna la cantidad al índice correcto
                        }
                    });

                    // Crear la gráfica con los datos dinámicos
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.values(reasonLabels),
                            datasets: [{
                                label: 'Número de Consultas',
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
                            scales: {
                                x: {
                                    grid: { display: false }, // Oculta líneas de fondo en X
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        font: { size: 12 }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: { font: { size: 14 } }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los datos:', error));
        });


    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('consultasMedicoChart').getContext('2d');

      

        const consultasMedicoChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Dr. Humberto', 'Dra. Leticia', 'Dr. Emilio', 'Dra. Alejandra'],
                datasets: [{
                    // label: 'Consultas por Médico',
                    data: [12, 8, 15, 5],
                    backgroundColor: [
                        '#9f9ff8',
                            '#96e2d6',
                            '#8c8c8c',
                            '#92bfff'   // Verde
                    ],
                    borderColor: [
                        '#9f9ff8',
                            '#96e2d6',
                            '#8c8c8c',
                            '#92bfff'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right', // 🔹 Coloca la leyenda a la derecha
                        labels: {
                            font: {
                                size: 14
                            },
                            usePointStyle: true, // 🔹 Usar círculos en lugar de cuadros
                            pointStyle: 'circle', // 🔹 Estilo de los puntos (círculos)
                            padding: 20 // 🔹 Espaciado de los elementos
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                cutout: '60%' // 🔹 Hace que el gráfico tenga forma de anillo
            }
        });
    });
</script>
@endpush 
