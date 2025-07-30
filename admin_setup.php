<?php

use App\Model\Conexion;
// 🔒 Protección inicial
if (file_exists(__FILE__) && basename($_SERVER['PHP_SELF']) === 'admin_setup.php') {
    echo "<h3>⚠️ Ejecutando script de creación de administrador...</h3>";
}

// Conexión a la BD
$db = (new Conexion())->getConexion();

try {
    // Datos del nuevo admin
    $nombre = "Admin";
    $apellido = "pets";
    $email = "admin@gmail.com";
    $passwordPlano = "admin";
    $direccion = "Bogotá";
    $telefono = "123456";

    // Hashear contraseña
    $passHash = password_hash($passwordPlano, PASSWORD_BCRYPT);

    // Insertar usuario base
    $stmt = $db->prepare("INSERT INTO t_usuario (nombre, apellido, contrasena, email, direccion, telefono)
                        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellido, $passHash, $email, $direccion, $telefono]);

    $id_usuario = $db->lastInsertId();
    echo "✔️ Usuario creado con ID: $id_usuario<br>";

    // Insertar registro
    $db->query("INSERT INTO t_registro (fecha, tipo_usuario) VALUES (CURDATE(), 'ADMIN')");
    $id_registro = $db->lastInsertId();

    // Insertar en tabla de administrador
    $stmt = $db->prepare("INSERT INTO t_administrador (n_documento,id_usuario, id_registro) VALUES (1001,?, ?)");
    $stmt->execute([$id_usuario, $id_registro]);

    echo "<h3 style='color:green;'>✅ Administrador '$nombre' creado con éxito.</h3>";
    echo "<p>🛑 Ahora <strong>borra este archivo</strong> (admin_setup.php) para evitar accesos no autorizados.</p>";
} catch (PDOException $e) {
    echo "<h3 style='color:red;'>❌ Error al crear el administrador: " . $e->getMessage() . "</h3>";
}
