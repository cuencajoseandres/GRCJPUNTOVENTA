<?php
require('conexion.php');
session_start(); // <-- IMPORTANTE, iniciar la sesión siempre

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Error: usuario no logueado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            id_producto,
            nombre_product,
            cod_product,
            precio_costo_produc,
            precio_publico_product,
            precio_gremio_product,
            precio_mayorista_product,
            cant_product,
            fecha_product,
            rela_categoria,
            rela_proveedor
        FROM producto
        WHERE cant_product > 0
        ORDER BY id_producto DESC
    ");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($productos as $fila): ?>
        <tr>
            <td><?php echo $fila->id_producto; ?></td>
            <td><?php echo htmlspecialchars($fila->nombre_product); ?></td>
            <td><?php echo htmlspecialchars($fila->cod_product); ?></td>
            <td><?php echo htmlspecialchars($fila->precio_costo_produc); ?></td>
            <td><?php echo htmlspecialchars($fila->precio_publico_product); ?></td>
            <td><?php echo htmlspecialchars($fila->precio_gremio_product); ?></td>
            <td><?php echo htmlspecialchars($fila->precio_mayorista_product); ?></td>
            <td><?php echo htmlspecialchars($fila->cant_product); ?></td>
            <td><?php echo htmlspecialchars($fila->fecha_product); ?></td>
            <td><?php echo htmlspecialchars($fila->rela_categoria); ?></td>
            <td><?php echo htmlspecialchars($fila->rela_proveedor); ?></td>
            <td>
                <!-- Botón Baja -->
                <button 
                    class="btn btn-danger btn-xs btn-baja" 
                    data-id="<?php echo $fila->id_producto; ?>">
                    BAJA
                </button>

                <!-- Botón Detalle -->
                <button
                    class="btn btn-primary btn-detalle"
                    data-toggle="modal"
                    data-target="#modalVerDetalle"
                    data-nombre="<?php echo htmlspecialchars($fila->nombre_product); ?>"
                    data-codigo="<?php echo htmlspecialchars($fila->cod_product); ?>"
                    data-precio_costo="<?php echo htmlspecialchars($fila->precio_costo_produc); ?>"
                    data-precio_publico="<?php echo htmlspecialchars($fila->precio_publico_product); ?>"
                    data-precio_gremio="<?php echo htmlspecialchars($fila->precio_gremio_product); ?>"
                    data-precio_mayorista="<?php echo htmlspecialchars($fila->precio_mayorista_product); ?>"
                    data-cantidad="<?php echo htmlspecialchars($fila->cant_product); ?>"
                    data-fecha="<?php echo htmlspecialchars($fila->fecha_product); ?>"
                    data-categoria="<?php echo htmlspecialchars($fila->rela_categoria); ?>"
                    data-proveedor="<?php echo htmlspecialchars($fila->rela_proveedor); ?>">
                    VER DETALLE
                </button>
            </td>
        </tr>
    <?php endforeach;

} catch (Exception $e) {
    echo "<tr><td colspan='12'>Error: " . $e->getMessage() . "</td></tr>";
}
?>