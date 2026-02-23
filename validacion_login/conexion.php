<?php 
// Conexion a la base de datos
$scon  = "mysql:host=localhost;dbname=db_cj_informatica;charset=utf8mb4";
$suser = "root";    // Usuario
$spass = "";        // Contraseña
$msg   = "";

try {
    // Crear conexión PDO
    $pdo = new PDO($scon, $suser, $spass, [
        PDO::ATTR_PERSISTENT => true,              // Conexión persistente
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Resultados como array asociativo
        PDO::ATTR_EMULATE_PREPARES => false        // Desactivar emulación para seguridad
    ]);

    $msg = "✅ Conexión a la base de datos exitosa";

} catch (PDOException $e) {
    $msg = "❌ Error al conectar a la base de datos: " . $e->getMessage();
    die($msg); // Detener ejecución si falla la conexión
}
?>
