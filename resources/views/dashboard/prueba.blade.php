@extends('layouts.app')

@section('content')

<section>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Veterinaria</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .service-card {
            width: 250px;
            height: 120px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .service-card:hover {
            transform: scale(1.05);
        }

        .service-card.selected {
            background-color: #007bff !important;
            color: white !important;
        }

        .icon-container {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Colores de fondo de los íconos */
        .consultas .icon-container { background-color: #bfeeff; }
        .hospital .icon-container { background-color: #d9ffdc; }
        .grooming .icon-container { background-color: #ffe4df; }
        .hotel .icon-container { background-color: #c5d2ff; }
        .cremacion .icon-container { background-color: #fffbcb; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="row d-flex flex-wrap justify-content-center gap-3">
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="service-card consultas d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="consultas">
                <div>
                    <p class="mb-1 fs-6 text-dark">Consultas</p>
                    <h5 class="fw-bold">3</h5>
                </div>
                <div class="icon-container">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="service-card hospital d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hospital">
                <div>
                    <p class="mb-1 fs-6 text-dark">Hospital</p>
                    <h5 class="fw-bold">3</h5>
                </div>
                <div class="icon-container">
                    <i class="fas fa-hospital"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="service-card grooming d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="grooming">
                <div>
                    <p class="mb-1 fs-6 text-dark">Grooming</p>
                    <h5 class="fw-bold">3</h5>
                </div>
                <div class="icon-container">
                    <i class="fas fa-cut"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="service-card hotel d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="hotel">
                <div>
                    <p class="mb-1 fs-6 text-dark">Hotel</p>
                    <h5 class="fw-bold">3</h5>
                </div>
                <div class="icon-container">
                    <i class="fas fa-bed"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="service-card cremacion d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm" data-type="cremacion">
                <div>
                    <p class="mb-1 fs-6 text-dark">Cremación</p>
                    <h5 class="fw-bold">3</h5>
                </div>
                <div class="icon-container">
                    <i class="fas fa-fire"></i>
                </div>
            </div>
        </div>
    </div>
</div>

</section>
@endsection

@push('scripts')
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
</script>
@endpush 

</body>
</html>