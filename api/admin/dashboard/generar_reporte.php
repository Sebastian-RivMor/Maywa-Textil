<?php
ob_start(); // Evita que warnings arruinen el PDF

session_start();

// Solo Admin
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'Admin') {
    http_response_code(401);
    exit('No autorizado');
}

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/db.php'; // ⚠️ Asegúrate que esta ruta es correcta

// Parámetro "tipo" de reporte
$tipo = $_GET['tipo'] ?? 'ventas_producto'; // por defecto HU13

// Filtros opcionales
$fechaInicio   = $_GET['fecha_inicio']   ?? null;
$fechaFin      = $_GET['fecha_fin']      ?? null;
$idComunidad   = $_GET['id_comunidad']   ?? null;
$clienteNombre = $_GET['cliente']        ?? null;
$umbralStock   = isset($_GET['umbral']) ? (int)$_GET['umbral'] : 3;

// Config PDF
$pdf = new TCPDF();
$pdf->SetCreator('Dashboard Admin');
$pdf->SetAuthor('Maywa Textil');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

date_default_timezone_set('America/Lima');
$fecha = date('d/m/Y H:i');
$logo  = __DIR__ . '/../../../public/assets/img/logo.jpg';

if (file_exists($logo)) {
    $pdf->Image($logo, 15, 10, 28);
}

$pdf->SetFont('helvetica', 'B', 15);

// Título según tipo
switch ($tipo) {
    case 'ventas_producto':
        $titulo = 'Reporte de ventas por producto';
        break;
    case 'pedidos_estado':
        $titulo = 'Reporte de pedidos por estado';
        break;
    case 'stock_bajo':
        $titulo = 'Reporte de productos con stock bajo';
        break;
    default:
        $titulo = 'Reporte';
        break;
}

$pdf->Cell(0, 10, $titulo, 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Generado el: ' . $fecha, 0, 1, 'C');
$pdf->Ln(5);

// Mostrar resumen de filtros si hay
$filtros = [];
if ($fechaInicio) $filtros[] = "Desde: $fechaInicio";
if ($fechaFin)    $filtros[] = "Hasta: $fechaFin";
if ($idComunidad) $filtros[] = "Comunidad ID: $idComunidad";
if ($clienteNombre && $tipo === 'pedidos_estado') $filtros[] = "Cliente: $clienteNombre";
if ($tipo === 'stock_bajo') $filtros[] = "Umbral stock: $umbralStock";

if (!empty($filtros)) {
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->MultiCell(0, 5, 'Filtros aplicados: ' . implode(' | ', $filtros), 0, 'L');
    $pdf->Ln(3);
}
$pdf->SetFont('helvetica', '', 10);


/**
 * HU13 – Ventas totales por producto
 */
if ($tipo === 'ventas_producto') {
    $where   = [];
    $params  = [];

    // Filtros de fecha
    if ($fechaInicio) {
        $where[] = 'p.fecha_pedido >= :fini';
        $params[':fini'] = $fechaInicio;
    }
    if ($fechaFin) {
        $where[] = 'p.fecha_pedido <= :ffin';
        $params[':ffin'] = $fechaFin;
    }
    if ($idComunidad) {
        $where[] = 'pr.id_comunidad = :idc';
        $params[':idc'] = $idComunidad;
    }

    // Aseguramos que solo se sumen productos de pedidos con estado "pendiente", "procesando", "enviado", "entregado"
    $sql = "
        SELECT 
            pr.nombre_producto,
            c.nombre_comunidad,
            SUM(dp.cantidad) AS unidades_vendidas,
            SUM(dp.cantidad * dp.precio_unitario) AS total_venta
        FROM tb_detalle_pedido dp
        INNER JOIN tb_producto pr ON dp.id_producto = pr.id_producto
        INNER JOIN tb_pedido p ON dp.id_pedido = p.id_pedido
        LEFT JOIN tb_comunidad c ON pr.id_comunidad = c.id_comunidad
        WHERE p.estado_pedido IN ('pendiente', 'procesando', 'enviado', 'entregado')";  // Filtra solo los pedidos en los estados válidos

    // Aplicar los filtros si es necesario
    if ($where) {
        $sql .= ' AND ' . implode(' AND ', $where);
    }

    $sql .= ' GROUP BY pr.id_producto, pr.nombre_producto, c.nombre_comunidad
              ORDER BY total_venta DESC';

    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        $pdf->Write(0, 'No hay datos de ventas para los filtros seleccionados.', '', 0, '', true);
    } else {
        $totalGeneral = 0;
        $tbl = '<table border="1" cellpadding="4">
                  <thead>
                    <tr style="background-color:#f2f2f2;">
                      <th><b>Producto</b></th>
                      <th><b>Comunidad</b></th>
                      <th><b>Unidades vendidas</b></th>
                      <th><b>Total (S/)</b></th>
                    </tr>
                  </thead><tbody>';

        foreach ($rows as $r) {
            $producto  = $r['nombre_producto'];
            $comu     = $r['nombre_comunidad'] ?? '—';
            $unidades = (int)$r['unidades_vendidas'];
            $total    = (float)$r['total_venta'];
            $totalGeneral += $total;

            $tbl .= '<tr>
                        <td>' . htmlspecialchars($producto) . '</td>
                        <td>' . htmlspecialchars($comu) . '</td>
                        <td align="right">' . $unidades . '</td>
                        <td align="right">' . number_format($total, 2) . '</td>
                     </tr>';
        }

        $tbl .= '<tr style="background-color:#f9f9f9;">
                    <td colspan="3" align="right"><b>Total general</b></td>
                    <td align="right"><b>' . number_format($totalGeneral, 2) . '</b></td>
                 </tr>';

        $tbl .= '</tbody></table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');
    }
}

/**
 * HU14 – Reporte de pedidos por estado
 */
elseif ($tipo === 'pedidos_estado') {
    $where  = [];
    $params = [];

    if ($fechaInicio) {
        $where[] = 'p.fecha_pedido >= :fini';
        $params[':fini'] = $fechaInicio;
    }
    if ($fechaFin) {
        $where[] = 'p.fecha_pedido <= :ffin';
        $params[':ffin'] = $fechaFin;
    }
    if ($clienteNombre) {
        $where[] = "CONCAT(per.nombre, ' ', per.apellido) LIKE :cli";
        $params[':cli'] = '%' . $clienteNombre . '%';
    }
    if ($idComunidad) {
        // filtrar por comunidad relacionada a productos del pedido
        $where[] = 'EXISTS (
            SELECT 1
            FROM tb_detalle_pedido dp2
            INNER JOIN tb_producto pr2 ON dp2.id_producto = pr2.id_producto
            WHERE dp2.id_pedido = p.id_pedido
              AND pr2.id_comunidad = :idc
        )';
        $params[':idc'] = $idComunidad;
    }

    $sql = "
        SELECT 
            p.estado_pedido,
            COUNT(DISTINCT p.id_pedido) AS cantidad_pedidos,
            SUM(p.total) AS total_monto
        FROM tb_pedido p
        LEFT JOIN tb_usuario u ON p.id_usuario = u.id_usuario
        LEFT JOIN tb_persona per ON u.id_persona = per.id_persona
    ";

    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' GROUP BY p.estado_pedido ORDER BY p.estado_pedido';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        $pdf->Write(0, 'No hay pedidos para los filtros seleccionados.', '', 0, '', true);
    } else {
        $totalPedidos = 0;
        $totalMonto   = 0;

        $tbl = '<table border="1" cellpadding="4">
                  <thead>
                    <tr style="background-color:#f2f2f2;">
                      <th><b>Estado</b></th>
                      <th><b>Cantidad de pedidos</b></th>
                      <th><b>Total (S/)</b></th>
                    </tr>
                  </thead><tbody>';

        foreach ($rows as $r) {
            $estado   = $r['estado_pedido'] ?: '—';
            $cant     = (int)$r['cantidad_pedidos'];
            $monto    = (float)$r['total_monto'];

            $totalPedidos += $cant;
            $totalMonto   += $monto;

            $tbl .= '<tr>
                        <td>' . htmlspecialchars($estado) . '</td>
                        <td align="right">' . $cant . '</td>
                        <td align="right">' . number_format($monto, 2) . '</td>
                     </tr>';
        }

        $tbl .= '<tr style="background-color:#f9f9f9;">
                    <td align="right"><b>Total</b></td>
                    <td align="right"><b>' . $totalPedidos . '</b></td>
                    <td align="right"><b>' . number_format($totalMonto, 2) . '</b></td>
                 </tr>';

        $tbl .= '</tbody></table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');
    }
}

/**
 * HU15 – Reporte de stock bajo
 */
elseif ($tipo === 'stock_bajo') {
    $sql = "
        SELECT 
            p.nombre_producto,
            p.stock,
            c.nombre_comunidad
        FROM tb_producto p
        LEFT JOIN tb_comunidad c ON p.id_comunidad = c.id_comunidad
        WHERE p.stock < 3
        ORDER BY p.stock ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        $pdf->Write(0, 'No hay productos con stock menor a 3 unidades.', '', 0, '', true);
    } else {
        $tbl = '<table border="1" cellpadding="4">
                  <thead>
                    <tr style="background-color:#f2f2f2;">
                      <th><b>Producto</b></th>
                      <th><b>Comunidad</b></th>
                      <th><b>Stock actual</b></th>
                    </tr>
                  </thead><tbody>';

        foreach ($rows as $r) {
            $nombre = $r['nombre_producto'];
            $comu   = $r['nombre_comunidad'] ?? '—';
            $stock  = (int)$r['stock'];

            $tbl .= '<tr>
                        <td>' . htmlspecialchars($nombre) . '</td>
                        <td>' . htmlspecialchars($comu) . '</td>
                        <td align="right">' . $stock . '</td>
                     </tr>';
        }

        $tbl .= '</tbody></table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');
    }
} else {
    $pdf->Write(0, 'Tipo de reporte no reconocido.', '', 0, '', true);
}

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->Cell(0, 10, 'Reporte generado automáticamente desde el panel administrativo de Maywa Textil.', 0, 1, 'C');

ob_end_clean();
$pdf->Output('reporte_' . $tipo . '.pdf', 'I');
