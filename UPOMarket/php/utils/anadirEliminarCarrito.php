<?php

session_start();
if (isset($_SESSION['email'])) {
    include './encriptar.php';
    if (isset($_POST['btnAgregarCarrito'])) {
        if (isset($_POST['id'])) {
            $idProv = desencriptar($_POST['id']);
            if (is_numeric($idProv)) {
                $id = $idProv;
            } else {
                $errores[] = "Parece que algo ha ido mal...";
            }
        } else {
            $errores[] = "Parece que algo ha ido mal...";
        }
        if (isset($_POST['nombre'])) {
            $nombreProv = desencriptar($_POST['nombre']);
            $nombre = filter_var($nombreProv, FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Parece que algo ha ido mal...";
        }

        if (isset($errores)) {
            alert("Parece que algo ha ido mal...");
            header('Location: ../principal.php');
        } else {
            $producto = array(
                'id' => $id,
                'nombre' => $nombre,
                'cantidad' => 1
            );
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'][0] = $producto;
            } else {
                $index = count($_SESSION['carrito']);
                $_SESSION['carrito'][$index] = $producto;
            }
            //Volver a la página del produco--------------------------------------------------------------------
            header('Location: ../principal.php'); //Cambiar por volver a la página del producto del que venimos
            //--------------------------------------------------------------------------------------------------
        }
    } else {
        if (isset($_POST['btnEliminarCarrito'])) {
            $valorBtn = filter_var($_POST['btnEliminarCarrito'], FILTER_SANITIZE_NUMBER_INT);
            if (is_numeric($valorBtn)) {
                $index = 0;
                $enc = false;
                while (!$enc) {
                    if ($index == $_POST['btnEliminarCarrito']) {
                        $enc = true;
                    } else {
                        $index++;
                    }
                }
                $idProductoProv = desencriptar($_POST['idProducto' . $index]);
                $idProducto = filter_var($idProductoProv, FILTER_SANITIZE_NUMBER_INT);
                if (is_numeric($idProducto)) {
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if ($producto['id'] == $idProducto) {
                            unset($_SESSION['carrito'][$indice]);
                        }
                    }
                    header('Location: ../carrito.php');
                } else {
                    $errores[] = "Parece que algo ha ido mal...";
                }
            } else {
                $errores[] = "Parece que algo ha ido mal...";
            }
            if (isset($errores)) {
                alert("Parece que algo ha ido mal...");
                header('Location: ../principal.php');
            }
        } else {
            header('Location: ../principal.php');
        }
    }
} else {
    alert("No existe ninguna sesión activa.");
    header('Location: ../principal.php');
}
?>

