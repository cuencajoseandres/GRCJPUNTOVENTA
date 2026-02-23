<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'] ?? '';
  $telefono = $_POST['telefono'] ?? '';
  $tipo_equipo = $_POST['tipo_equipo'] ?? '';
  $modelo = $_POST['modelo'] ?? '';
  $descripcion = $_POST['descripcion'] ?? '';

  $linea = "🧍 $nombre | 📞 $telefono | 💻 $tipo_equipo $modelo | ⚙️ $descripcion\n";
  file_put_contents('solicitudes.txt', $linea, FILE_APPEND);

  echo "<script>alert('✅ Solicitud enviada correctamente. Te contactaremos pronto.');window.location='servicio-tecnico.php';</script>";
}
?>
