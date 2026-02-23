<?php
require '../../../vendor/autoload.php';
require('conexion.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$idFactura = $_GET['id'] ?? null;
if (!$idFactura) die("Factura no especificada.");

// Datos de la empresa (MODIFICÁ A TU GUSTO)
$empresa = [
    "nombre" => "INFORMÁTICA",
    "direccion" => "Fotheringham 4850",
    "telefono" => "(370) 402-6388",
    "correo" => "cjinformaticafsa@gmail.com",
    "logo" => "http://localhost/PROYECTOS/Servicio_system_ecommerce/vista/empresa/img/logo-dd.png"
];

// Consultar factura
$stmt = $pdo->prepare("
    SELECT 
        f.id_factura, f.num_venta, f.fecha_fact, f.monto_total,
        CONCAT(pers.nombre_pers,' ', pers.apellido_pers) AS empleado
    FROM factura f
    INNER JOIN empleado e ON f.rela_empleado = e.id_empleado
    INNER JOIN persona pers ON e.rela_pers = pers.id_persona
    WHERE f.id_factura = ?
");
$stmt->execute([$idFactura]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$factura) die("Factura no encontrada.");

// Consultar detalle productos y servicios
$stmt = $pdo->prepare("
    SELECT p.nombre_product AS nombre, df.cant_venta_product AS cantidad, 
           df.precio_unit_product AS precio, df.subtotal_product AS subtotal, 'Producto' AS tipo
    FROM detalle_factura df
    INNER JOIN producto p ON df.rela_producto = p.id_producto
    WHERE df.rela_factura = ?
    UNION ALL
    SELECT s.nombre_serv AS nombre, ds.cant_vent_serv AS cantidad,
           ds.precio_unit_serv AS precio, ds.subtotal_serv AS subtotal, 'Servicio' AS tipo
    FROM detalle_fact_serv ds
    INNER JOIN servicio s ON ds.rela_servicio = s.id_servicio
    WHERE ds.rela_factura = ?
");
$stmt->execute([$idFactura, $idFactura]);
$detalle = $stmt->fetchAll(PDO::FETCH_ASSOC);


// HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">


    <title>Factura <?= htmlspecialchars($factura['num_venta']) ?></title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 25px;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header img {
            width: 90px;
            margin-right: 18px;
        }

        .empresa-info h2 {
            margin: 0;
            font-size: 22px;
            color: #005bb5;
        }

        .empresa-info p {
            margin: 2px 0;
        }

        .factura-info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #0066cc;
            color: white;
            padding: 8px;
            border: 1px solid #999;
            text-align: center;
        }

        td {
            padding: 6px;
            border: 1px solid #999;
            text-align: center;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }

        /* Marca de agua */
        .marca-agua {
            position: fixed;
            top: 30%;
            left: 18%;
            font-size: 180px;
            color: rgba(180, 0, 0, 0.13);
            transform: rotate(-45deg);
            z-index: -1;
        }
    </style>


    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 25px;
        }

        /* Barra superior */
        .top-bar {
            width: 100%;
            background: #f0f0f0;
            padding: 12px 0;
            text-align: center;
            border-bottom: 1px solid #ccc;
            position: relative;
        }

        .top-box {
            width: 80px;
            height: 80px;
            background: #e6e6e6;
            border-radius: 6px;
            text-align: center;
            padding-top: 10px;
            border: 1px solid #ccc;
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
        }

        .big-x {
            font-size: 32px;
            font-weight: bold;
        }

        .code {
            font-size: 12px;
            margin-top: -5px;
        }

        /* HEADER */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 12px;
            margin-top: 40px;
        }

        .header img {
            width: 90px;
            margin-right: 18px;
        }

        .empresa-info h2 {
            margin: 0;
            font-size: 22px;
            color: #005bb5;
        }

        .empresa-info p,
        .factura-info p {
            margin: 3px 0;
        }

        /* TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        thead th {
            background: #0066cc !important;
            color: white !important;
            padding: 8px;
            border: 1px solid #666;
            text-align: center;
            font-weight: bold;
        }

        tbody td {
            padding: 6px;
            border: 1px solid #999;
            text-align: center;
        }

        /* TOTAL */
        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }

        /* FOOTER */
        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 11px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>

</head>

<body>

    <!-- Barra superior + recuadro X -->
    <div class="top-bar">
        <div class="top-box">
            <div class="big-x">X</div>
            <div class="code">Comprobante</div>
        </div>
    </div>
    <div style="height:10px;"></div>

    <div style="width:100%; margin-top:20px; border:1px solid #999;">

        <!-- COLUMNA IZQUIERDA - EMPRESA -->
        <div style="
        display:inline-block;
        width:49%;
        vertical-align:top;
        padding-right:10px;
        border-right:1px solid #999;
    ">
            <div style="text-align:center; margin-bottom:10px;">
                <img src="<?= $empresa['logo'] ?>" style="width:90px; display:block; margin:0 auto 8px auto;">
                <h3 style="margin:0; font-size:20px; color:#005bb5;">
                    <?= htmlspecialchars($empresa['nombre']) ?>
                </h3>
            </div>

            <div style="padding:12px; border-radius:4px;">
                <p style="margin:4px 0;">
                    <strong>Dirección:</strong> <?= htmlspecialchars($empresa['direccion']) ?>
                </p>

                <p style="margin:4px 0;">
                    <strong>Tel:</strong> <?= htmlspecialchars($empresa['telefono']) ?>
                </p>

                <p style="margin:4px 0;">
                    <strong>Email:</strong> <?= htmlspecialchars($empresa['correo']) ?>
                </p>
            </div>

        </div>

        <!-- COLUMNA DERECHA - INFORMACIÓN DE VENTA -->
        <div style="
        display:inline-block;
        width:48%;
        vertical-align:top;
            ">
            <div style="height:50px;"></div>
            <div style=" padding:15px; border-radius:4px;">
                <h3 style="margin:0 0 10px 0; color:#005bb5; font-size:18px;">
                    Información de la Venta
                </h3>

                <p style="margin:6px 0;"><strong>N° de Venta:</strong> <?= $factura['num_venta'] ?></p>
                <p style="margin:6px 0;"><strong>Fecha:</strong> <?= $factura['fecha_fact'] ?></p>
                <p style="margin:6px 0;"><strong>Vendedor:</strong> <?= $factura['empleado'] ?></p>
                <p style="margin:6px 0;"><strong>Condición IVA:</strong> <?= $cliente ?? "Consumidor Final" ?></p>
                <p style="margin:6px 0;"><strong>Condición de Venta:</strong> <?= $condicion ?? "Contado" ?></p>
            </div>
        </div>

    </div>



<!-- BLOQUE INFORMACIÓN DEL CLIENTE -->
<div style="width:100%; margin-top:25px;">

    <div style="
        border:1px solid #999; 
        padding:15px; 
        border-radius:4px;
        background:#fafafa;
    ">
        <h3 style="margin:0 0 12px 0; color:#005bb5; font-size:18px;">
            Información del Cliente
        </h3>

        <!-- COLUMNA IZQUIERDA -->
        <div style="
            display:inline-block;
            width:48%;
            vertical-align:top;
        ">
            <p style="margin:6px 0;"><strong>Cliente:</strong> <?= $cliente['nombre'] ?? '' ?></p>
            <p style="margin:6px 0;"><strong>DNI / CUIT:</strong> <?= $cliente['dni_cuit'] ?? '' ?></p>
            <p style="margin:6px 0;"><strong>Dirección:</strong> <?= $cliente['direccion'] ?? '' ?></p>
        </div>

        <!-- COLUMNA DERECHA -->
        <div style="
            display:inline-block;
            width:48%;
            vertical-align:top;
        ">
            <p style="margin:6px 0;"><strong>Teléfono:</strong> <?= $cliente['telefono'] ?? '' ?></p>
            <p style="margin:6px 0;"><strong>Email:</strong> <?= $cliente['email'] ?? '' ?></p>
            <p style="margin:6px 0;"><strong>Condición de Venta:</strong> <?= $condicion ?? "Contado" ?></p>
        </div>

    </div>

</div>







    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nombre']) ?></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td><?= number_format($d['precio'], 2) ?></td>
                    <td><?= number_format($d['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">TOTAL: $<?= number_format($factura['monto_total'], 2) ?></p>

    <div class="footer">
        <p>Gracias por su compra — CJ <?= $empresa['nombre'] ?></p>
        <p>Documento NO válido como factura fiscal — <?= date('d/m/Y') ?></p>
    </div>

</body>

</html>




<?php
$html = ob_get_clean();

// Generar PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Factura_' . $factura['num_venta'] . '.pdf', ['Attachment' => false]);
?>