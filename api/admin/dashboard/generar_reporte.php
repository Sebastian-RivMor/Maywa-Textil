<?php
ob_start(); // ✅ Evita que los warnings arruinen el PDF

require_once __DIR__ . '/../../../vendor/autoload.php';

// ===== CONFIGURACIÓN BASE =====
$pdf = new TCPDF();
$pdf->SetCreator('Dashboard Admin');
$pdf->SetAuthor('Maywa Textil');
$pdf->SetTitle('Reporte General del Dashboard');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// ==== ENCABEZADO CON LOGO ====
date_default_timezone_set('America/Lima');
$fecha = date('d/m/Y H:i');

// Ruta del logo (desde este archivo hasta public/assets/img/logo.png)
$logo = __DIR__ . '/../../../public/assets/img/logo.jpg';

// Dibuja el logo si existe (ancho 28 mm)
if (file_exists($logo)) {
    // Colócalo a la izquierda, un poco por encima del margen superior
    $pdf->Image($logo, 15, 10, 28); // x=15, y=10, width=28mm (alto proporcional)
}

// Título y fecha centrados
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte General del Dashboard', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Generado el: ' . $fecha, 0, 1, 'C');
$pdf->Ln(8);


// ===== URL BASE =====
// ⚠️ Como “api” está en la raíz, esta es la ruta correcta:
$BASE_URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
           . '://' . $_SERVER['HTTP_HOST'] . '/MAYWATEXTIL/api/admin/dashboard/';

// ===== FUNCIÓN AUXILIAR =====
function getData($endpoint, $base) {
    $json = @file_get_contents($base . $endpoint);
    return $json ? json_decode($json, true) : [];
}

// ===== OBTENER DATOS DE ENDPOINTS =====
$ventas_mes    = getData('ventas_mes.php', $BASE_URL);
$clientes_top  = getData('clientes_top.php', $BASE_URL);
$productos_top = getData('productos_top.php', $BASE_URL);
$pedidos_dep   = getData('pedidos_departamento.php', $BASE_URL);

// --------------------------------------------------
// 🧾 SECCIÓN 1: Ventas por Mes
// --------------------------------------------------
$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 10, '1. Ventas por Mes', 0, 1, 'L', true);
$pdf->SetFont('helvetica', '', 10);

if (!empty($ventas_mes)) {
    $tbl = '<table border="1" cellpadding="4">
              <thead>
                <tr style="background-color:#f2f2f2;">
                    <th><b>Mes</b></th>
                    <th><b>Total Ventas (S/)</b></th>
                </tr>
              </thead><tbody>';
    foreach ($ventas_mes as $row) {
        $mes = $row['mes'] ?? $row['nombre_mes'] ?? '';
        $total = $row['total'] ?? $row['total_ventas'] ?? 0;
        $tbl .= "<tr>
                    <td>{$mes}</td>
                    <td>{$total}</td>
                 </tr>";
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
} else {
    $pdf->Write(0, 'No hay datos de ventas disponibles.', '', 0, '', true);
}
$pdf->Ln(5);

// --------------------------------------------------
// 👥 SECCIÓN 2: Clientes con más Compras
// --------------------------------------------------
$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 10, '2. Clientes con más Compras', 0, 1, 'L', true);
$pdf->SetFont('helvetica', '', 10);

if (!empty($clientes_top)) {
    $tbl = '<table border="1" cellpadding="4">
              <thead>
                <tr style="background-color:#f2f2f2;">
                    <th><b>Cliente</b></th>
                    <th><b>Total Compras (S/)</b></th>
                </tr>
              </thead><tbody>';
    foreach ($clientes_top as $row) {
        $nombre = $row['nombre_cliente'] ?? $row['cliente'] ?? '';
        $compras = $row['total_pedidos'] ?? $row['total'] ?? 0;
        $tbl .= "<tr>
                    <td>{$nombre}</td>
                    <td>{$compras}</td>
                 </tr>";
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
} else {
    $pdf->Write(0, 'No hay datos de clientes disponibles.', '', 0, '', true);
}
$pdf->Ln(5);

// --------------------------------------------------
// 📦 SECCIÓN 3: Productos más Vendidos
// --------------------------------------------------
$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 10, '3. Productos más Vendidos', 0, 1, 'L', true);
$pdf->SetFont('helvetica', '', 10);

if (!empty($productos_top)) {
    $tbl = '<table border="1" cellpadding="4">
              <thead>
                <tr style="background-color:#f2f2f2;">
                    <th><b>Producto</b></th>
                    <th><b>Vendidos</b></th>
                    <th><b>%</b></th>
                </tr>
              </thead><tbody>';
    foreach ($productos_top as $row) {
        $producto  = $row['nombre_producto'] ?? $row['producto'] ?? '';
        $vendidos  = $row['total_vendidos'] ?? 0;
        $porcentaje = $row['porcentaje'] ?? '';
        $tbl .= "<tr>
                    <td>{$producto}</td>
                    <td>{$vendidos}</td>
                    <td>{$porcentaje}</td>
                 </tr>";
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
} else {
    $pdf->Write(0, 'No hay datos de productos disponibles.', '', 0, '', true);
}
$pdf->Ln(5);

// --------------------------------------------------
// 🗺️ SECCIÓN 4: Pedidos por Departamento
// --------------------------------------------------
$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 10, '4. Pedidos por Departamento', 0, 1, 'L', true);
$pdf->SetFont('helvetica', '', 10);

if (!empty($pedidos_dep)) {
    $tbl = '<table border="1" cellpadding="4">
              <thead>
                <tr style="background-color:#f2f2f2;">
                    <th><b>Departamento</b></th>
                    <th><b>Total Pedidos</b></th>
                </tr>
              </thead><tbody>';
    foreach ($pedidos_dep as $row) {
        $dep = $row['departamento'] ?? '';
        $total = $row['total_pedidos'] ?? $row['total'] ?? 0;
        $tbl .= "<tr>
                    <td>{$dep}</td>
                    <td>{$total}</td>
                 </tr>";
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
} else {
    $pdf->Write(0, 'No hay datos de pedidos disponibles.', '', 0, '', true);
}

// ===== PIE DE PÁGINA =====
$pdf->Ln(8);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->Cell(0, 10, 'Reporte generado automáticamente desde el panel Dashboard.', 0, 1, 'C');

// ===== SALIDA =====
ob_end_clean(); // ✅ Limpia el buffer antes de enviar el PDF
$pdf->Output('reporte_dashboard.pdf', 'I');
?>
