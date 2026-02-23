
<?php
require 'conexion.php';

$id = $_POST["id_clientes"];
$nombre = $_POST["nombre_pers"];
$apellido = $_POST["apellido_pers"];
$telefono = $_POST["num_tel"];

try {

    // actualizar persona
    $sql1 = $pdo->prepare("
        UPDATE persona p
        JOIN clientes c ON c.rela_persona = p.id_persona
        SET p.nombre_pers=?, p.apellido_pers=?
        WHERE c.id_clientes = ?
    ");
    $sql1->execute([$nombre, $apellido, $id]);

    // actualizar telefono
    $sql2 = $pdo->prepare("
        UPDATE telefono t
        JOIN clientes c ON c.rela_persona = t.rela_persona
        SET t.num_tel=?
        WHERE c.id_clientes = ?
    ");
    $sql2->execute([$telefono, $id]);

    echo json_encode(["error" => false]);

} catch (Exception $e) {

    echo json_encode([
        "error" => true,
        "mensaje" => $e->getMessage()
    ]);
}
?>
