<?php

/* Si no existe la cookie recarga la página que le pasemos por parámetro */

function comprobarCookie($pagina) {
    if ($_GET['establecida'] != 'si') {
        // Establecemos la cookie
        setcookie('establecida', 'si', time() + 60);
        // Obligamos al navegador a recargar la página
        header("Location: $pagina?establecida=si");
    }
}

?>
