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
                $index = -1;
                foreach ($_SESSION['carrito'] as $indice => $productoSes) {
                    if ($productoSes['id'] == $producto['id']) {
                        $index = $indice;
                    }
                }
                if ($index != -1) {
                    alert("El producto ya se encontraba en el carrito");
                    header('Location: ../producto.php?idProducto=' . $id);
                }
                $index = count($_SESSION['carrito']);
                $_SESSION['carrito'][$index] = $producto;
            }
            header('Location: ../producto.php?idProducto=' . $id);
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
            if (isset($_POST['btnEliminarCarritoProducto'])) {
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
                if (isset($errores)) {
                    alert("Parece que algo ha ido mal...");
                    header('Location: ../principal.php');
                } else {
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if ($producto['id'] == $id) {
                            unset($_SESSION['carrito'][$indice]);
                        }
                    }
                    header('Location: ../producto.php?idProducto=' . $id);
                }
            } else {
                if (isset($_POST['procesarCompra'])) {
                    if (isset($_POST['direccion'])) {
                        $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_MAGIC_QUOTES);
                    } else {
                        header("Location: ../principal.php");
                    }
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if (isset($_POST['cantidad' . $indice])) {
                            $cant = filter_var($_POST['cantidad' . $indice], FILTER_SANITIZE_NUMBER_INT);
                            $_SESSION['carrito'][$indice]['cantidad'] = $cant;
                        }
                    }
                    $_SESSION['direccion'] = $direccion;
                    header('Location: ../procesarCompra.php');
                } else {
                    header('Location: ../principal.php');
                }
            }
        }
    }
} else {
    alert("No existe ninguna sesión activa.");
    header('Location: ../principal.php');
}
?>

