<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use App\Model\dashboard\Dashboard;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

// Mostrar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ======================
// 1️⃣ Verificar sesión
// ======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
if (!$id_usuario) die("Usuario no autenticado");

// Verificar si es admin
$esAdmin = Roles::esAdmin($id_usuario);
$nit_fundacion = null;
if (!$esAdmin) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($id_usuario);
    if (!$nit_fundacion) die("Usuario sin fundación asignada");
}

// ======================
// 2️⃣ Instanciar modelo
// ======================
$model = new Dashboard();

// ======================
// 3️⃣ Obtener datos dinámicamente
// ======================
$datasets = [
    'Donaciones por Mes'           => $esAdmin ? $model->getDonacionesPorMes() : $model->getDonacionesPorMesFundacion($nit_fundacion),
    'Guardianes por Mes'           => $model->getGuardianesPorMes(),
    'Publicaciones por Mes'        => $esAdmin ? $model->getPublicacionesPorMes() : $model->getPublicacionesPorMesFundacion($nit_fundacion),
    'Mascotas Felinas y Caninas'   => $esAdmin ? $model->getMascotasFelinasCaninas() : $model->getAdopcionesPorEspecie($nit_fundacion),
    'Ranking de Fundaciones'       => $model->getRankingFundaciones(),
    'Tipos de Causas'              => $esAdmin ? $model->getTiposCausas() : $model->getCausasActivasPorTipo($nit_fundacion),
    'Mascotas por Estado'          => $model->getMascotasPorEstado(),
    'Usuarios Registrados'         => $model->getUsuariosRegistrados()
];

// Convertir objetos a arrays
foreach ($datasets as $key => $data) {
    $datasets[$key] = array_map(fn($item) => (array)$item, $data);
}

// ======================
// 4️⃣ Función para generar tablas HTML dinámicas
// ======================
function generarTablaHTML($titulo, $datos)
{
    if (empty($datos)) return '';

    $cols = array_keys($datos[0]); // columnas dinámicas según el dataset

    $html = '<div style="background-color:#2ecc71;color:white;padding:8px;margin-top:20px;font-weight:bold;">' . $titulo . '</div>';
    $html .= '<table style="width:100%;border-collapse: collapse;margin-bottom:15px;">';
    $html .= '<tr>';
    foreach ($cols as $col) {
        $html .= '<th style="border:1px solid #000;padding:5px;background-color:#3498db;color:white;">' . ucfirst($col) . '</th>';
    }
    $html .= '</tr>';

    foreach ($datos as $row) {
        $html .= '<tr>';
        foreach ($cols as $col) {
            $html .= '<td style="border:1px solid #000;padding:5px;">' . ($row[$col] ?? '-') . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</table>';
    return $html;
}

// ======================
// 5️⃣ Construir HTML dinámico
// ======================
$logoPath = __DIR__ . '/../../Public/images/logo/logo-oscuro.png';
$logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

$htmlPDF = '<div style="text-align:center; margin-bottom:20px;">';
if ($logoBase64) {
    $htmlPDF .= '<img src="data:image/png;base64,' . $logoBase64 . '" style="width:120px;">';
}
$htmlPDF .= '<h2>Reporte Administrativo - PetsConnect</h2></div>';

// Generar tablas dinámicamente
foreach ($datasets as $titulo => $data) {
    if (!empty($data)) {
        $htmlPDF .= generarTablaHTML($titulo, $data);
    }
}

// ======================
// 6️⃣ Generar PDF con Dompdf
// ======================
$dompdf = new Dompdf();
$dompdf->loadHtml($htmlPDF);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Reporte_Admin_PetsConnect.pdf", ["Attachment" => true]);