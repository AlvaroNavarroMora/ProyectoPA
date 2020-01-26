<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function existeConflicto($idPedido, $idProducto) {
    $query = "SELECT * FROM `reclamaciones` WHERE `id_pedido`='$idPedido' AND `id_producto`='$idProducto';";
    $result = ejecutarConsulta($query);
    $salida = false;

    $aux = mysqli_fetch_all($result);
    if (sizeof($aux) > 0) {
        $salida = true;
    }

    return $salida;
}

function resolverConflicto($email) {
    $query = "UPDATE `reclamaciones` SET `estado`='$email' WHERE `id_pedido`='11' AND `id_producto`='14';";
    echo $query;
    $result = ejecutarConsulta($query);
}
