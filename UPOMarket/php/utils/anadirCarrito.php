<?php
    include './encriptar.php';
    if(isset($_POST['btnAgregarCarrito'])){
        if(isset($_POST['id'])){
            $idProv = desencriptar($_POST['id']);
            if(is_numeric($idProv)){
                $id = $idProv;
            }else{
                $errores[] = "Parece que algo ha ido mal...";
            }
        }else{
            $errores[] = "Parece que algo ha ido mal...";
        }
        if(isset($_POST['nombre'])){
            $nombreProv = desencriptar($_POST['nombre']);
            $nombre = filter_var($nombreProv, FILTER_SANITIZE_MAGIC_QUOTES); 
        }else{
            $errores[] = "Parece que algo ha ido mal...";
        }
    }
    if(isset($errores)){
        alert("Parece que algo ha ido mal...");
        header('Location: ./principal.php');
    }else{
        
    }
    
?>

