<?php
require '../../../vendor/autoload.php';
require('conexion.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$idFactura = $_GET['id'] ?? null;
if (!$idFactura) {
    die("Factura no especificada.");
}

// ======= DATOS EMPRESA =======
$empresa = [
    "nombre" => "CJ INFORMÁTICA",
    "direccion" => "Fotheringham 4850",
    "telefono" => "(370) 402-6388",
    "correo" => "cjinformaticafsa.com",
    "logo" => "../../img/logo_empresa.png"
];

// ======= CONSULTAR FACTURA =======
$stmt = $pdo->prepare("
    SELECT 
        f.id_factura, f.num_venta, f.fecha_fact, f.monto_total,
        CONCAT(pers.nombre_pers,' ',pers.apellido_pers) AS empleado
    FROM factura f
    INNER JOIN empleado e ON f.rela_empleado = e.id_empleado
    INNER JOIN persona pers ON e.rela_pers = pers.id_persona
    WHERE f.id_factura = ?
");
$stmt->execute([$idFactura]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("Factura no encontrada.");
}

// ======= CONSULTAR DETALLE PRODUCTOS Y SERVICIOS (UNIFICADO) =======
$detalle = [];

// Productos
$stmt = $pdo->prepare("
    SELECT 
        p.nombre_product AS nombre,
        df.cant_venta_product AS cantidad,
        df.precio_unit_product AS precio,
        df.subtotal_product AS subtotal
    FROM detalle_factura df
    INNER JOIN producto p ON df.rela_producto = p.id_producto
    WHERE df.rela_factura = ?
");
$stmt->execute([$idFactura]);
$detalleProd = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Servicios
$stmt = $pdo->prepare("
    SELECT 
        s.nombre_serv AS nombre,
        ds.cant_vent_serv AS cantidad,
        ds.precio_unit_serv AS precio,
        ds.subtotal_serv AS subtotal
    FROM detalle_fact_serv ds
    INNER JOIN servicio s ON ds.rela_servicio = s.id_servicio
    WHERE ds.rela_factura = ?
");
$stmt->execute([$idFactura]);
$detalleServ = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Unificar resultados
$detalle = array_merge($detalleProd, $detalleServ);

// ======= HTML DEL TICKET =======
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket <?= htmlspecialchars($factura['num_venta']) ?></title>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        margin: 10px;
        color: #000;
    }
    .header {
        text-align: center;
        border-bottom: 1px solid #000;
        margin-bottom: 5px;
    }
    .header img {
        width: 60px;
    }
    .empresa-info {
        text-align: center;
        font-size: 10px;
        line-height: 1.2;
    }
    .factura-info {
        font-size: 10px;
        margin-top: 5px;
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
        padding: 3px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-top: 3px;
    }
    th, td {
        padding: 2px 0;
        text-align: left;
    }
    th {
        border-bottom: 1px solid #000;
        text-align: center;
    }
    td {
        border-bottom: 1px dashed #ccc;
    }
    .total {
        text-align: right;
        font-weight: bold;
        margin-top: 5px;
    }
    .footer {
        text-align: center;
        font-size: 9px;
        margin-top: 5px;
        border-top: 1px dashed #000;
        padding-top: 3px;
    }
</style>
</head>
<body>

<div class="header">
    <img src="<?= $empresa['logo'] ?>" alt="Logo Empresa">
</div>

<div class="empresa-info">
    <strong><?= htmlspecialchars($empresa['nombre']) ?></strong><br>
    <?= htmlspecialchars($empresa['direccion']) ?><br>
    Tel: <?= htmlspecialchars($empresa['telefono']) ?><br>
    <?= htmlspecialchars($empresa['correo']) ?>
</div>

<div class="factura-info">
    <p><strong>Ticket N°:</strong> <?= htmlspecialchars($factura['num_venta']) ?><br>
    <strong>Fecha:</strong> <?= htmlspecialchars($factura['fecha_fact']) ?><br>
    <strong>Vendedor:</strong> <?= htmlspecialchars($factura['empleado']) ?></p>
</div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Subt.</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalle as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['nombre']) ?></td>
            <td style="text-align:center;"><?= $d['cantidad'] ?></td>
            <td style="text-align:right;">$<?= number_format($d['precio'], 2) ?></td>
            <td style="text-align:right;">$<?= number_format($d['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p class="total">TOTAL: $<?= number_format($factura['monto_total'], 2) ?></p>

<div class="footer">
    Gracias por su compra — <?= htmlspecialchars($empresa['nombre']) ?><br>
    <?= date('d/m/Y H:i') ?>
</div>

</body>
</html>
<?php
$html = ob_get_clean();

// ======= GENERAR PDF =======
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 210, 600], 'portrait'); // ancho tipo ticket
$dompdf->render();
$dompdf->stream('Ticket_'.$factura['num_venta'].'.pdf', ['Attachment' => false]);
?>
