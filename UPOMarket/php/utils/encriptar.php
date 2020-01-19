<?php
function encriptar($dato){
    return openssl_encrypt($dato, "AES-128-ECB", "pmkt321");
}

function desencriptar($datosEncriptados){
    return openssl_decrypt($datosEncriptados, "AES-128-ECB", "pmkt321");
}
?>