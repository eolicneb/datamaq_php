<?php
/*
Path: api/v1/cameras.php
Description: Endpoint v1 para exponer los feeds de camaras
*/

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Mostrar errores (para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar el controlador de Clean Architecture
require_once __DIR__ . '/../../interface_adapters/controller/cameras_controller_v1.php';

$controller = new CamerasController();
echo $controller->handle();
