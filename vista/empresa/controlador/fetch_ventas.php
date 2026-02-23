<?php
require('conexion.php');
header('Content-Type: application/json');

$sql = "SELECT v.fecha_venta, 
               CONCAT(pcli.nombre_pers, ' ', pcli.apellido_pers) AS cliente,
               pr.nombre_product AS producto,
               v.cantidad, 
               v.total,
               CONCAT(pusr.nombre_pers, ' ', pusr.apellido_pers) AS vendedor
        FROM venta v
        JOIN persona pcli ON v.rela_cliente = pcli.id_persona
        JOIN producto pr ON v.rela_producto = pr.id_producto
        JOIN usuario u ON v.rela_user = u.id_usuario
        JOIN persona pusr ON u.rela_pers = pusr.id_persona
        ORDER BY v.fecha_venta DESC";

$stmt = $pdo->query($sql);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
