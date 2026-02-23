<?php
require '../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$data = json_decode($_POST['data'], true);

if(!$data){
    http_response_code(400);
    echo json_encode(['error'=>true, 'mensaje'=>'Datos no recibidos']);
    exit;
}

// Configurar DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// HTML del presupuesto
//<b>Tipo:</b> '.$data['tipoVenta'].'</p>
$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
h2 { text-align:center; color:#007bff; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { border:1px solid #ccc; padding:6px; text-align:left; }
th { background:#f8f8f8; }
.text-right { text-align:right; }
</style>

<h2>PRESUPUESTO</h2>
<p><b>N°:</b> '.$data['num_presupuesto'].'<br>
<b>Fecha:</b> '.$data['fecha'].'<br>
<b>Cliente:</b> '.$data['cliente'].'<br>


<table>
<thead>
<tr>
<th>Producto/servicio</th>
<th>Cant.</th>
<th>Precio</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>';

foreach($data['detalles'] as $d){
    $html .= "<tr>
        <td>{$d['nombre']}</td>
        <td>{$d['cantidad']}</td>
        <td>\${$d['precio']}</td>
        <td>\${$d['subtotal']}</td>
    </tr>";
}

$html .= '</tbody></table>';
$html .= '<h3 class="text-right">TOTAL: $'.$data['total'].'</h3>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="presupuesto.pdf"');
echo $dompdf->output();
?>