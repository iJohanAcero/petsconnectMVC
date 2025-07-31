<?php
namespace App\config;

// -------------------------------------
// CONFIGURACIÓN GENERAL DEL PROYECTO
// -------------------------------------

// Ruta absoluta a la raíz del proyecto (desde el sistema de archivos)
define('BASE_PATH', realpath(__DIR__ . '/../'));

// Carpetas comunes (backend)
define('MODEL_PATH', BASE_PATH . '/Model');
define('CONTROLLER_PATH', BASE_PATH . '/controller');
define('VIEW_PATH', BASE_PATH . '/view');
define('PUBLIC_PATH', BASE_PATH . '/Public');

// Opcional: Ruta base para acceder vía navegador (frontend)
// Asegurar que coincide con el nombre del proyecto en tu hosting o localhost
define('BASE_URL', '/petsconnectmvc');

// Carpetas públicas para CSS, JS e imágenes
define('CSS_URL', BASE_URL . '/Public/css');
define('JS_URL', BASE_URL . '/Public/js');
define('IMG_URL', BASE_URL . '/Public/images');



?>