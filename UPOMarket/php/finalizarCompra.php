<?php
$clientID = "Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug";
$secret = "EGLPpU61Vabi5eKBq24_3A0YufctYwuDDMudoj38vU6hgKxEW1OF2zWhuRjsG__FOOW2VBjBP7oAk3E-";

$login = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($login, CURLOPT_RETURNTRANSFER, True);
curl_setopt($login, CURLOPT_USERPWD, $clientID.":".$secret);
curl_setopt($login, CURLOPT_RETURNTRANSFER, True);
$respuesta = curl_exec();
$objRespuesta = json_decode($respuesta);

$accessToken = $objRespuesta->access_token;

$venta = curl_init("https://api.sandbox.paypal.com/v1/payments/payment/".$_GET['paymentID']);

curl_setopt($venta, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$accessToken));

$respuestaVenta = curl_exec($venta);
print_r($respuestaVenta);
?>