<?php

include 'manejadorBD.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function listarCategorias() {
    $query = "SELECT nombre FROM categorias";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

/* Lista las categorías que tiene un producto */

function listarCategoriasDeProducto($producto) {
    $query = "SELECT * FROM `categorias` as c, `categorias_productos` as ca WHERE c.nombre=ca.nombre_categoria AND ca.id_producto=$producto;";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

/* Lista los productos que pertenecen a una categoría X */

function listarProductosDeCategoria($categoria) {
    $query = "SELECT * FROM `productos` as p, `categorias_productos` as ca WHERE p.id=ca.id_producto AND ca.nombre_categoria='$categoria';";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

/* order Type debe ser ASC (ascendente) o DESC (Descendente) */

function listarProductosPorPrecio($orderType = "ASC") {
    $query = "SELECT * FROM `productos` ORDER by `precio` $orderType";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

/* order Type debe ser ASC (ascendente) o DESC (Descendente) */

function listarProductosPorPrecioCategoria($orderType = "ASC") {
    $query = "SELECT * FROM `productos` as p, `categorias_productos` as ca WHERE p.id=ca.id_producto AND ca.nombre_categoria='$categoria' ORDER by `precio` $orderType";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

/* Email de usuario que queremos buscar */

function productosDeUsuario($email) {
    $query = "SELECT * FROM `productos` WHERE `email_vendedor`=$email";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}

function insertarProducto($email, $nombre, $descripcion, $precio, $stoc, $imagen, $categorias) {
    $queryProducto = "INSERT INTO `productos`(`email_vendedor`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`) VALUES ('$email','$nombre','$descripcion','$precio','$stoc','$imagen')";
    $result = ejecutarConsulta($query);

    $row = mysqli_fetch_row($result);
    if ($row) {
        $id = $row[0];
        foreach ($categorias as $v) {
            $queryProductoCategorias = "INSERT INTO `categorias_productos`(`nombre_categoria`, `id_producto`) VALUES ('$id','$v')";
            ejecutarConsulta($query);
        }
    }
}

function listarCaracteristicasProducto($idProducto) {
    $query = "SELECT * from caracteristicas_productos where id_producto=$idProducto";
    $result = ejecutarConsulta($query);
    $caracteristicas = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $caracteristicas[] = $row;
        }
    }
    return $caracteristicas;
}

function obtenerProducto($idProducto) {

    $query = "SELECT * from productos where id=$idProducto";
    $result = ejecutarConsulta($query);
    $producto = null;
    if (mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
    }
    return $producto;
}

function listarValoracionesProcucto($idProducto) {
    $query = "SELECT * from valoraciones where id_producto=$idProducto";
    $result = ejecutarConsulta($query);
    $valoraciones = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $valoraciones[] = $row;
        }
    }
    return $valoraciones;
}

function buscarProductos($busca) {
    $string = strtolower($busca);
    $query = "SELECT * FROM productos where LOWER(nombre) LIKE '%$string%' or LOWER(descripcion) LIKE '%$string%' and disponible=1";
    $result = ejecutarConsulta($query);
    $productos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }
    return $productos;
}
function valorarProducto($email, $idProducto, $puntuacion, $valoracion) {
    $query = "INSERT INTO valoraciones(email_cliente,id_producto,puntuacion,descripcion) VALUES('$email',$idProducto, $puntuacion, '$valoracion')";
    ejecutarConsulta($query);
}

function obtenerPuntuacionProducto($idProducto) {
    $query = "SELECT AVG(puntuacion) FROM valoraciones where id_producto=$idProducto";
    $result = ejecutarConsulta($query);
    
    return mysqli_fetch_all($result)[0][0];
}
