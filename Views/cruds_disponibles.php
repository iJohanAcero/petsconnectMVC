
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>prueba</title>
</head>
<body>
<h1> CRUD disponibles</h1>
                <div class="botones-crud">
                    <button class="button-crud">
                        Usuarios
                    </button>
                    <button class="button-crud">
                        Mascotas
                    </button>
                    <button onclick="cargarCrudProductos()" class="button-crud">
                        Productos
                    </button>
                    <button onclick="cargarCrudVacunas()" class="button-crud">
                        Vacunas
                    </button>
                    <button onclick="cargarCrudProcesos()" class="button-crud">
                        Procesos de adopción
                    </button>
                    <button class="button-crud">
                        Tipo de mascota
                    </button>
                </div>


                
                <div id="crud">
                    <!-- AQUI SE CARGARAN LAS CRUDS CUANDO SE ACTIVE EL ONCLICK DE CADA BOTON POR EL FETCH EN JAVASCRIPT -->
                </div>
</body>
</html>

