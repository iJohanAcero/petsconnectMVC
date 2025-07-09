<!-- <div class="container"> Contenedor principal -->
<!-- <div class="row"> Fila que contendrá las tarjetas -->
<!-- <div class="row"> -->
<!-- <div class="col-sm-6 col-md-4 col-lg-3"> -->
<!-- <div class="col-sm-6 col-md-4 mb-4"> columna + margen inferior -->
<!-- <div class="card">
                        <img src="perro.jpg" class="card-img-top" alt="Perro en adopción">
                        <div class="card-body">
                            <h5 class="card-title">Firulais</h5>
                            <p class="card-text">Perro juguetón de 2 años. ¡Busca hogar!</p>
                            <a href="#" class="btn btn-primary">Adoptar</a>
                        </div>
                    </div>
                </div>
            </div> -->
<!-- <div class="col-sm-6 col-md-4 col-lg-3"> <!-- Tarjeta 2  </div>-->
<!--<div class="col-sm-6 col-md-4 col-lg-3">  Tarjeta 3 </div>-->
<!--<div class="col-sm-6 col-md-4 col-lg-3"> <!-- Tarjeta 4 </div>-->
<!--<div class="col-sm-6 col-md-4 col-lg-3"> <!-- Tarjeta 5 </div>-->
<!--<div class="col-sm-6 col-md-4 col-lg-3"> <!-- Tarjeta 6 </div> -->
<!-- </div>
    </div>
</div> -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas en Adopción</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../../Public/css/PortafoleoMascotas.css">
</head>

<body>

    <div class="container my-5">
        <h2 class="text-center mb-4">Mascotas en Adopción</h2>

        <!-- Carrusel de Tarjetas -->
        <div class="row">
            <div class="col-12">
                <div id="petCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carousel-content">
                        <?php
                        // Aquí se cargarán las tarjetas dinámicamente
                        ?>                          
                    </div>

                    <!-- Controles del Carrusel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Detalles -->
    <div class="modal fade" id="petModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="petModalTitle">Detalles de la Mascota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="petModalBody">
                    <!-- Aquí se carga la info dinámica -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Adoptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS Personalizado -->
    <script src="../../Public/js/PortafoleoMascota.js"></script>
</body>

</html>