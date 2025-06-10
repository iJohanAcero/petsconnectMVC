<?php
// Incluimos el modelo de productos
require_once('../../Model/producto/ProductoModel.php');

// Verificamos que hay datos enviados por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Instanciamos el modelo
    $modeloProducto = new Productos();

    // 🔍 Detectamos qué operación se va a hacer
    // La clave está en verificar qué botón o dato viene en el POST

    // -------------------------------
    // 1️⃣ AGREGAR producto
    // Si el formulario de registro fue enviado (btnregistrar existe)
    if (isset($_POST['btnregistrar'])) {

        // Recogemos los datos del formulario
        $nombre        = $_POST['nombre'];
        $tipo_producto = $_POST['tipo_producto'];
        $descripcion   = $_POST['descripcion'];
        $precio        = $_POST['precio'];
        $cantidad      = $_POST['cantidad_disponible'];

        // Llamamos al método 'add' del modelo
        $modeloProducto->add($nombre, $tipo_producto, $descripcion, $precio, $cantidad);
        exit;
    }

    // -------------------------------
    // 2️⃣ ACTUALIZAR producto
    // Si se recibe un campo llamado 'actualizar' (puedes cambiar el nombre si usas otro)
    if (isset($_POST['actualizar'])) {

        $id           = $_POST['Id'];
        $nombre       = $_POST['Nombre'];
        $tipo         = $_POST['tipo_producto'];
        $descripcion  = $_POST['Descripcion'];
        $precio       = $_POST['Precio'];
        $cantidad     = $_POST['Cantidad'];

        // Llamamos al método 'update'
        $modeloProducto->update($id, $nombre, $tipo, $descripcion, $precio, $cantidad);
        exit;
    }

    // -------------------------------
    // 3️⃣ ELIMINAR producto
    // Si se recibió un ID sin más campos, asumimos que es una eliminación
    // Ahora: Si en el POST hay 'eliminar' y 'Id', eliminamos el producto
    if (isset($_POST['eliminar']) && isset($_POST['Id'])) {
        $id = $_POST['Id'];
        $modeloProducto->delete($id);

        // Después de eliminar, redirigimos para evitar problemas al recargar la página
        header("Location: /petsconnectMVC/View/producto/ProductoView.php");
        exit;
    }

    // ⚠️ Si llega hasta aquí, no se reconoce la acción
    echo "<script>alert('Acción no reconocida.'); window.location.href = '../../View/producto/ProductoView.php';</script>";
} else {
    // Si no es una petición POST, redirigimos a la vista
    header("Location: ../../View/producto/ProductoView.php");
}
