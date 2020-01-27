<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$html = "<h1>Vender en UPOMarket</h1><p>Vender en <strong>UPOMarket</strong> es muy sencillo, tan solo tienes que crear una cuenta, registrarte como Vendedor y comenzar a subir tus productos.</p><p>Si ya tienes una cuenta y a&uacute;n no eres vendedor, accede a la aplicaci&oacute;n, entra en tu perfil y selecciona la opci&oacute;n <em>Convertirse en vendedor</em>.</p><p>Ya est&aacute;s listo para comenzar esta fant&aacute;stica experiencia.</p><img src='../img/upomarket_nav.png' alt='Logo de UPOMarket'/>";

$pdf = new DOMPDF();

// Definimos el tamaño y orientaci&oacute;n del papel que queremos.
$pdf->set_paper("letter", "portrait");
//$pdf->set_paper(array(0,0,104,250));
// Cargamos el contenido HTML.



$pdf->load_html(utf8_decode($html));

// Renderizamos el documento PDF.
$pdf->render();

// Enviamos el fichero PDF al navegador.
$pdf->stream('reportePdf.pdf');

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
?>