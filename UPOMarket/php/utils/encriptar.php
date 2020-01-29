<?php

/* En este fichero tenemos métodos para encriptar/desencriptar cadenas. Lo usamos principalmente cuando hay que pasar parámetros por GET */

function encriptar($dato) {
    return openssl_encrypt($dato, "AES-128-ECB", "pmkt321");
}

function desencriptar($datosEncriptados) {
    return openssl_decrypt($datosEncriptados, "AES-128-ECB", "pmkt321");
}

?>