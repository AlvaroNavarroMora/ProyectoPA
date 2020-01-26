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
    $query = "SELECT * FROM `productos` as p, `categorias_productos` as ca WHERE p.id=ca.id_producto AND ca.nombre_categoria='$categoria' and p.disponible=1;";
    $result = ejecutarConsulta($query);
    $lista = Array();
    while ($row = mysqli_fetch_assoc($result)) {
        $lista[] = $row;
    }
    return $lista;
}

/* Lista todos los productos disponibles */

function listarProductos() {
    $query = "SELECT * FROM productos WHERE disponible=1";
    $result = ejecutarConsulta($query);
    $lista = Array();
    while ($row = mysqli_fetch_assoc($result)) {
        $lista[] = $row;
    }
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

/* Devuelve true si existe un producto con ese nombre y ese usuario */

function comprobarUsuarioProducto($email, $producto) {
    $query = "SELECT * FROM `productos` WHERE `email_vendedor`='$email' and `nombre`='$producto'";
    $result = ejecutarConsulta($query);
    $salida = false;

    $aux = mysqli_fetch_all($result);
    if (sizeof($aux) > 0) {
        $salida = true;
    }
    return $salida;
}

function insertarProducto($email, $nombre, $descripcion, $precio, $stoc, $imagen, $categorias, $caracteristicaName, $caracteristicaDesc) {
    $queryProducto = "INSERT INTO `productos`(`email_vendedor`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`) VALUES ('$email','$nombre','$descripcion','$precio','$stoc','$imagen')";
    $insercion = ejecutarConsulta($queryProducto);

    if ($insercion) {
        $queryIdProducto = "SELECT `id` FROM `productos` WHERE `email_vendedor` = '$email' AND `nombre`='$nombre'";
        $result = ejecutarConsulta($queryIdProducto);

        $salida = true;
        $row = mysqli_fetch_row($result);
        if ($row) {
            $id = $row[0];
            foreach ($categorias as $v) {
                $queryProductoCategorias = "INSERT INTO `categorias_productos`(`nombre_categoria`, `id_producto`) VALUES ('$v','$id')";
                $r = ejecutarConsulta($queryProductoCategorias);
                if (!$r) {
                    $salida = false;
                    echo "here";
                }
            }

            for ($i = 0; $i < count($caracteristicaName); $i++) {
                $cn = $caracteristicaName[$i];
                $cd = $caracteristicaDesc[$i];
                $queryProductoCaracteristicas = "INSERT INTO `caracteristicas_productos`(`id_producto`, `nombre_caracteristica`, `valor`) VALUES ('$id','$cn','$cd')";
                $r = ejecutarConsulta($queryProductoCaracteristicas);
                if (!$r) {
                    $salida = false;
                    echo "herehere";
                }
            }
        } else {
            $salida = false;
        }
    } else {
        $salida = false;
    }
    return $salida;
}

function listarCaracteristicasProducto($idProducto) {
    $query = "SELECT * FROM caracteristicas_productos WHERE id_producto=$idProducto";
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
    $query = "SELECT * FROM productos WHERE id=$idProducto AND disponible=1";
    $result = ejecutarConsulta($query);
    $producto = null;
    if (mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
    }
    return $producto;
}

function listarValoracionesProcucto($idProducto) {
    $query = "SELECT * FROM valoraciones WHERE id_producto=$idProducto";
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
    $query = "SELECT * FROM productos where LOWER(nombre) LIKE '%$string%' or LOWER(descripcion) LIKE '%$string%' AND disponible=1";
    $result = ejecutarConsulta($query);
    $productos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }
    return $productos;
}

function buscarProductosCategoria($busca, $categoria) {
    $string = strtolower($busca);
    $query = "SELECT * FROM productos p, categorias_productos c WHERE p.id=c.id_producto AND c.nombre_categoria='$categoria' "
            . "AND (LOWER(p.nombre) LIKE '%$string%' or LOWER(p.descripcion) LIKE '%$string%') AND p.disponible=1";
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

function actualizarValoracion($email, $idProducto, $puntuacion, $valoracion) {
    $query = "UPDATE valoraciones SET puntuacion=$puntuacion, descripcion='$valoracion' WHERE email_cliente='$email' AND id_producto=$idProducto";
    ejecutarConsulta($query);
}

function eliminarValoracion($email, $idProducto) {
    $query = "DELETE FROM valoraciones WHERE email_cliente='$email' AND id_producto=$idProducto";
    ejecutarConsulta($query);
}

function obtenerPuntuacionProducto($idProducto) {
    $query = "SELECT AVG(puntuacion) FROM valoraciones WHERE id_producto=$idProducto";
    $result = ejecutarConsulta($query);

    return mysqli_fetch_all($result)[0][0];
}

function listarTopVentas($top) {
    $query = "SELECT p.id, p.imagen FROM lineas_de_pedido lp,productos p WHERE lp.id_producto=p.id AND p.disponible=1 GROUP BY id_producto ORDER BY count(*) DESC limit $top";
    $result = ejecutarConsulta($query);
    $productos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }
    return $productos;
}

function obtenerProductosCarrito($idProductos) {
    $query = "SELECT * from productos WHERE id in (" . implode(",", array_map('intval', $idProductos)) . ") AND disponible=1;";
    $result = ejecutarConsulta($query);
    $productos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }
    return $productos;
}

function listarMisDirecciones($email) {
    $query = "SELECT d.id, d.nombre from direcciones_clientes dc,direcciones d WHERE dc.email_cliente='$email' and dc.direccion_cliente=d.id;";
    $result = ejecutarConsulta($query);
    $direcciones = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $direcciones[] = $row;
        }
    }
    return $direcciones;
}

function obtenerDireccion($idDireccion) {
    $query = "SELECT * FROM direcciones WHERE id=$idDireccion;";
    $result = ejecutarConsulta($query);
    $direccion = null;
    if (mysqli_num_rows($result) > 0) {
        $direccion = mysqli_fetch_assoc($result);
    }
    return $direccion;
}

function compradoPorMi($email, $idProducto) {
    $query = "SELECT * FROM pedidos p, lineas_de_pedido lp WHERE p.email_cliente='$email' AND lp.id_producto=$idProducto;";
    $result = ejecutarConsulta($query);
    
    return mysqli_num_rows($result) > 0;
}
function listarProductosPedido($idPedido) {
    $query = "SELECT pr.`nombre`,pr.`id` FROM `pedidos` as p, `productos`as pr, `lineas_de_pedido` as lp WHERE p.`id`='$idPedido' AND lp.`id_pedido`=p.`id` AND lp.`id_producto`=pr.`id`";
    $result = ejecutarConsulta($query);
    $lista = mysqli_fetch_all($result);
    return $lista;
}
