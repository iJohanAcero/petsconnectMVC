<?php

// 1. Cargar autoload de Composer
$autoloadPath = realpath(__DIR__ . '/../vendor/autoload.php');

if (!$autoloadPath || !file_exists($autoloadPath)) {
    die("❌ No se encontró el archivo autoload. Ejecuta 'composer install'.");
}

require_once $autoloadPath;

// 2. Cargar archivo de configuración
$configPath = realpath(__DIR__ . 'config.php');

if (!$configPath || !file_exists($configPath)) {
    die("❌ No se encontró el archivo de configuración.");
}

require_once $configPath;

// 3. (Opcional) Configuraciones globales adicionales
date_default_timezone_set("America/Bogota");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 4. (Opcional) Iniciar sesión si es necesario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}