<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function cerrarSesion() {
    session_destroy();
    header('Location: ../login.php');
}

function existeSesion() {
    if (!isset($_SESSION['usuario'])) {
        cerrarSesion();
    }
}
