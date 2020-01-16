<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function openCon() {
    //credenciales para la BD de Marcos
    $host = "85.137.192.11:3308";
    $user = "upomarket";
    $pas = "A2cMnUHsChA0DcZ8";
    $db = "upomarket";
    return mysqli_connect($host, $user, $pas, $db);
}


function closeCon($link) {
    return mysqli_close($link);
}

//Si todo va bien devuelvo el resultado de la query, sino devuelvo false
function ejecutarConsulta($query) {
    $link = openCon();
    $result = mysqli_query($link, $query);

    closeCon($link);
    return $result;
}