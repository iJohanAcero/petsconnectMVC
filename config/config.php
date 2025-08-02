<?php
namespace App\Config;

class Config {
    private static $instance = null;
    private $config = [];

    private function __construct() {
        // Ruta absoluta a la raíz del proyecto
        $this->config['BASE_PATH'] = realpath(__DIR__ . '/../');

        // Carpetas comunes (backend)
        $this->config['MODEL_PATH'] = $this->config['BASE_PATH'] . '/Model';
        $this->config['CONTROLLER_PATH'] = $this->config['BASE_PATH'] . '/controller';
        $this->config['VIEW_PATH'] = $this->config['BASE_PATH'] . '/view';
        $this->config['PUBLIC_PATH'] = $this->config['BASE_PATH'] . '/Public';

        // Ruta base para el navegador
        $this->config['BASE_URL'] = '/petsconnectmvc';

        // Carpetas públicas
        $this->config['CSS_URL'] = $this->config['BASE_URL'] . '/Public/css';
        $this->config['JS_URL'] = $this->config['BASE_URL'] . '/Public/js';
        $this->config['IMG_URL'] = $this->config['BASE_URL'] . '/Public/images';
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function get($key) {
        $instance = self::getInstance();
        return $instance->config[$key] ?? null;
    }

    // Evitar clonación del singleton
    private function __clone() {}
}