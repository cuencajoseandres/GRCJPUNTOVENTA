<?php
require 'conexion.php';

$id = $_GET["id"] ?? null;

if (!$id) {
    echo json_encode(["error" => true, "mensaje" => "ID no recibido"]);
    exit;
}

$sql = $pdo->prepare("
    SELECT c.id_clientes, p.nombre_pers AS nombre, p.apellido_pers AS apellido, t.num_tel AS telefono
    FROM clientes c
    INNER JOIN persona p ON c.rela_persona = p.id_persona
    LEFT JOIN telefono t ON t.rela_persona = p.id_persona
    WHERE c.id_clientes = ?
");
$sql->execute([$id]);

echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
?>
