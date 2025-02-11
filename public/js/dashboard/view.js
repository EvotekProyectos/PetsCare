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
                           legend: {
                               labels: {
                                   usePointStyle: true
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
                               grid: { display: false }, 
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

fetch("{{ route('dash.appointmentsVets') }}")
   .then(response => response.json())
   .then(data => {
       const labels = data.map(item => item.veterinarian);
       const values = data.map(item => item.total);

       const backgroundColors = ['#9f9ff8', '#96e2d6', '#8c8c8c', '#92bfff', '#f39c12', '#e74c3c'];
       const borderColors = backgroundColors.map(color => color); 

       new Chart(ctx, {
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
               cutout: '60%'
           }
       });
   })
   .catch(error => console.error('Error al obtener los datos:', error));
});
