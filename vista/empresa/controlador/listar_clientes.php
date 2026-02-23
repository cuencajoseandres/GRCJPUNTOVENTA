<?php
require 'conexion.php';

$sql = $pdo->query("
    SELECT 
        c.id_clientes,
        p.nombre_pers AS nombre,
        p.apellido_pers AS apellido,
        t.num_tel AS telefono
    FROM clientes c
    INNER JOIN persona p ON c.rela_persona = p.id_persona
    LEFT JOIN telefono t ON t.rela_persona = p.id_persona
    ORDER BY c.id_clientes DESC
");


$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);
?>