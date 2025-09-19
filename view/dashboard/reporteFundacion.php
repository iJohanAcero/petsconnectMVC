<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use App\Model\dashboard\Dashboard;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

// ======================
// 1️⃣ Verificar sesión
// ======================
if (session_status() === PHP_SESSION_NONE) session_start();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
if (!$id_usuario) die("Usuario no autenticado");

$esAdmin = Roles::esAdmin($id_usuario);
if ($esAdmin) die("Los administradores no pueden generar este reporte");

$nit_fundacion = Fundacion::obtenerNitPorUsuario($id_usuario);
if (!$nit_fundacion) die("Usuario sin fundación asignada");

// ======================
// 2️⃣ Instanciar modelo
// ======================
$model = new Dashboard();

// ======================
// 3️⃣ Obtener datos para las gráficas
// ======================
$donacionesPorMes         = $model->getDonacionesPorMesFundacion($nit_fundacion);
$mascotasAdoptadasPorMes  = $model->getMascotasAdoptadasPorMes($nit_fundacion);
$publicacionesPorMes      = $model->getPublicacionesPorMesFundacion($nit_fundacion);
$adopcionesPorEspecie     = $model->getAdopcionesPorEspecie($nit_fundacion);
$causasActivasPorTipo      = $model->getCausasActivasPorTipo($nit_fundacion);
$donacionesPorCausa       = $model->getDonacionesPorCausa($nit_fundacion);
$mascotasPorEstado        = $model->getMascotasPorEstado($nit_fundacion); // Suponiendo que exista este método

// ======================
// 4️⃣ Función para generar tablas HTML
// ======================
function generarTablaHTML($titulo, $datos, $cols)
{
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
// 5️⃣ Construir HTML dinámico para PDF
// ======================
// Logo como Base64
$logoPath = __DIR__ . '/../../Public/images/logo/logo-oscuro.png';
$logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

$htmlPDF = '
<div style="text-align:center; margin-bottom:20px;">
    <img src="data:image/png;base64,' . $logoBase64 . '" style="width:120px;">
    <h2>Reporte Fundación - PetsConnect</h2>
</div>
';

// Tablas con todos los datos de las gráficas
$htmlPDF .= generarTablaHTML('Donaciones por Mes', array_map(fn($x) => (array)$x, $donacionesPorMes), ['mes', 'total_recaudado']);
$htmlPDF .= generarTablaHTML('Mascotas Adoptadas por Mes', array_map(fn($x) => (array)$x, $mascotasAdoptadasPorMes), ['mes', 'total_adoptadas']);
$htmlPDF .= generarTablaHTML('Publicaciones por Mes', array_map(fn($x) => (array)$x, $publicacionesPorMes), ['mes', 'total_publicaciones']);
$htmlPDF .= generarTablaHTML('Adopciones por Especie', array_map(fn($x) => (array)$x, $adopcionesPorEspecie), ['especie', 'total_adopciones']);
$htmlPDF .= generarTablaHTML('Causas Activas por Tipo', array_map(fn($x) => (array)$x, $causasActivasPorTipo), ['tipo_causa', 'total_causas']);
$htmlPDF .= generarTablaHTML('Donaciones por Causa', array_map(fn($x) => (array)$x, $donacionesPorCausa), ['causa', 'total_recaudado', 'meta']);
$htmlPDF .= generarTablaHTML('Mascotas por Estado', array_map(fn($x) => (array)$x, $mascotasPorEstado), ['estado', 'total_mascotas']);

// ======================
// 6️⃣ Generar PDF con Dompdf
// ======================
$dompdf = new Dompdf();
$dompdf->loadHtml($htmlPDF);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Reporte_Fundacion_PetsConnect.pdf", ["Attachment" => true]);
