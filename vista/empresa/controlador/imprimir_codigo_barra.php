<?php
require '../../../vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$codigo = $_POST['codigo'] ?? '';
$nombre = strtoupper($_POST['nombre'] ?? '');

if (empty($codigo)) {
    die("Código vacío.");
}

// Tamaño etiqueta (50x30 mm) → 472 x 283 px
$ancho = 472;
$alto = 283;

$img = imagecreatetruecolor($ancho, $alto);
$blanco = imagecolorallocate($img, 255, 255, 255);
$negro  = imagecolorallocate($img, 0, 0, 0);

imagefilledrectangle($img, 0, 0, $ancho, $alto, $blanco);

// ---- Código de barras ----
$generator = new BarcodeGeneratorPNG();
$barcodePng = $generator->getBarcode($codigo, $generator::TYPE_CODE_128, 2, 70);

$barcodeImg = imagecreatefromstring($barcodePng);
$bw = imagesx($barcodeImg);
$bh = imagesy($barcodeImg);

$x = ($ancho - $bw) / 2;
$y = 65;

imagecopy($img, $barcodeImg, $x, $y, 0, 0, $bw, $bh);

// ------------ CENTRADO DEL TEXTO --------------
$font = 5; // tamaño GD básico

// Función para centrar texto con imagestring
function centrarTexto($img, $font, $y, $texto, $color, $anchoTotal) {
    $charWidth = imagefontwidth($font);
    $textWidth = strlen($texto) * $charWidth;
    $x = ($anchoTotal - $textWidth) / 2;
    imagestring($img, $font, $x, $y, $texto, $color);
}

// Título centrado arriba
centrarTexto($img, $font, 40, $nombre, $negro, $ancho);

// Código numérico centrado abajo
centrarTexto($img, $font, 140, $codigo, $negro, $ancho);

// Salida
header("Content-Type: image/png");
header("Content-Disposition: attachment; filename=etiqueta_$codigo.png");
imagepng($img);
imagedestroy($img);
?>
