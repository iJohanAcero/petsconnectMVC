<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance($_ENV['CLOUDINARY_URL']);

// Función para subir imagen
function uploadImageToCloudinary($imagePath, $folder = 'uploads', $publicId = null) {
    try {
        $uploadApi = new UploadApi();
        
        $options = [
            'folder' => $folder,
            'use_filename' => true,
            'unique_filename' => false,
            'overwrite' => true,
            'resource_type' => 'image'
        ];
        
        if ($publicId) {
            $options['public_id'] = $publicId;
        }
        
        $result = $uploadApi->upload($imagePath, $options);
        
        return [
            'success' => true,
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
            'width' => $result['width'],
            'height' => $result['height']
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Función para eliminar imagen
function deleteImageFromCloudinary($publicId) {
    try {
        $uploadApi = new UploadApi();
        $result = $uploadApi->destroy($publicId);
        
        return [
            'success' => $result['result'] === 'ok',
            'result' => $result['result']
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}