<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Ejemplo: enviar por correo (podés usar PHPMailer más adelante)
    $destino = "admin@tusitio.com";
    $asunto = "Nueva solicitud de cuenta - CJ Informatica";
    $contenido = "
    Nombre: $nombre
    Email: $email
    Teléfono: $telefono
    Mensaje: $mensaje
    ";

    mail($destino, $asunto, $contenido);

    echo "<script>alert('Solicitud enviada correctamente. Nos pondremos en contacto.');window.location='index.php';</script>";
}
?>
