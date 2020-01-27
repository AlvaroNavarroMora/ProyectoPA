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

function resolverConflicto($id_ped, $id_prod, $estado) {
    $query = "UPDATE `reclamaciones` SET `estado`='$estado' WHERE `id_pedido`='$id_ped' AND `id_producto`='$id_prod';";
    echo $query;
    $result = ejecutarConsulta($query);
}

function administrarConflicto($id_ped, $id_prod, $marca) {
    $query = "UPDATE `reclamaciones` SET `estado`='$marca' WHERE `id_pedido`='$id_ped' AND `id_producto`='$id_prod';";

    $result = ejecutarConsulta($query);
}

function crearReclamacion($id_ped, $id_prod, $descripcion) {
    if (!existeConflicto($idPedido, $idProducto)) {
        $query = "INSERT INTO `reclamaciones`(`id_pedido`, `id_producto`, `descripcion`, `estado`) VALUES ('$id_ped', '$id_prod', '$descripcion','Pendiente')";

        $result = ejecutarConsulta($query);
    }
}
