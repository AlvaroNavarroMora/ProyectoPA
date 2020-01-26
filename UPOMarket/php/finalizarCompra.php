<?php
include "./utils/sesionUtils.php";
include './utils/encriptar.php';
include './utils/utilsProductos.php';
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_POST["email"]) && isset($_POST["direccion"])) {
        $email = desencriptar(base64_decode($_POST['email']));
        $email = filter_var($email, FILTER_SANITIZE_MAGIC_QUOTES);
        if ($email == $_SESSION['email']) {
            $direccionId = desencriptar(base64_decode($_POST['direccion']));
            $direccionId = filter_var($direccionId, FILTER_SANITIZE_MAGIC_QUOTES);
            $i = 0;
            while (isset($_POST['producto' . $i]) && isset($_POST['cantidad' . $i])) {
                $productos[] = Array("id" => filter_var(desencriptar(base64_decode($_POST['producto' . $i])), FILTER_SANITIZE_NUMBER_INT),
                    "cantidad" => filter_var(desencriptar(base64_decode($_POST['cantidad' . $i])), FILTER_SANITIZE_NUMBER_INT));
                $i++;
            }
            $query = "INSERT INTO pedidos (email_cliente, id_direccion) VALUES('$email','$direccionId')";
            $link = openCon();
            $result = mysqli_query($link, $query);
            $idPedido = mysqli_insert_id($link);
            foreach ($productos as $producto) {
                $query = "INSERT INTO lineas_de_pedido (id_pedido, id_producto, cantidad) "
                        . "VALUES('$idPedido', '" . $producto['id'] . "', '" . $producto['cantidad'] . "')";
                $result = mysqli_query($link, $query);
            }
            mysqli_close($link);
            unset($_SESSION['carrito']);
            unset($_SESSION['direccion']);
            
        }
    }
}

if (!isset($idPedido)) {
    echo "Parece que algo ha ido mal";
} else {
    header('Location: ./mostrarPedido.php?idPedido='.$idPedido);
}
?>