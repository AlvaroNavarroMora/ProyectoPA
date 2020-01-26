<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Introducimos HTML de prueba


if (isset($_GET['idPedido'])) {
    $idPedido = filter_var($_GET['idPedido'], FILTER_SANITIZE_NUMBER_INT);

    $html = file_get_contents_curl("../php/pdfPedido.php?idPedido=$idPedido");



// Instanciamos un objeto de la clase DOMPDF.
    $pdf = new DOMPDF();

// Definimos el tamaño y orientación del papel que queremos.
    $pdf->set_paper("letter", "portrait");
//$pdf->set_paper(array(0,0,104,250));
// Cargamos el contenido HTML.
    ob_start();
    
    
    
    
    ?>


        <h1>Hellow World</h1>

    


    <?php
    $pdf->load_html(utf8_decode(ob_get_clean()));

// Renderizamos el documento PDF.
    $pdf->render();

// Enviamos el fichero PDF al navegador.
    $pdf->stream('reportePdf.pdf');
}

function file_get_contents_curl($url) {
    $crl = curl_init();
    $timeout = 5;
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}
