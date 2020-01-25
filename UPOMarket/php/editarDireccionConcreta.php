<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ./principal.php");
}

include './utils/manejadorBD.php';
include './utils/sesionUtils.php';
include './utils/encriptar.php';
$row = null;
if (isset($_GET['dir'])) {
    $dirId = filter_var(desencriptar(base64_decode($_GET['dir'])), FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($dirId)) {
        $errores[] = "Parece que algo no ha ido bien";
    } else {
        $query = "SELECT * FROM direcciones WHERE id='" . $dirId . "'";
        $result = ejecutarConsulta($query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
        }
    }
}

/* Añadir nombre del formulario registro */
if (isset($_POST['btnEditDireccion'])) {
    $direccion1 = trim(filter_var($_POST['direccion1'], FILTER_SANITIZE_STRING));
    $direccion2 = trim(filter_var($_POST['direccion2'], FILTER_SANITIZE_STRING));
    $provincia = trim(filter_var($_POST['provincia'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING));
    $cp = filter_var($_POST['cp'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $dirId = filter_var(desencriptar(base64_decode($_POST['dir'])), FILTER_SANITIZE_MAGIC_QUOTES);

    if (strlen($direccion1) < 1) {
        $errores[] = "El campo Direccion es obligatorio.";
    }
    if (strlen($provincia) < 1) {
        $errores[] = "El campo Provincia es obligatorio.";
    }
    if (strlen($ciudad) < 1) {
        $errores[] = "El campo Ciudad es obligatorio.";
    }
    if (strlen($cp) < 1) {
        $errores[] = "El campo Codigo Postal es obligatorio.";
    }
    if (strlen($nombre) < 1) {
        $errores[] = "El campo Nombre es obligatorio.";
    }

    if (empty($errores)) {
        if (editarDireccion($dirId, $_SESSION["email"], $nombre, $direccion1, $direccion2, $provincia, $ciudad, $cp)) {
            header("location:./perfil.php");
        } else {
            $errores[] = "No se pudo editar la direccion";
        }
    }

    if (!empty($errores)) {
        foreach ($errores as $e) {
            echo $e . "<br>";
        }
    }
}

function editarDireccion($dirId, $email, $nombre, $direccion1, $direccion2, $provincia, $ciudad, $cp) {
    $correcto = false;
    $conn = openCon();
    mysqli_set_charset($conn, "utf8");
    if (!$conn) {
        die("No se pudo conectar a la Base de Datos");
    }
    $query = "UPDATE direcciones SET nombre='$nombre', linea_1='$direccion1', linea_2='$direccion2', provincia='$provincia', ciudad='$ciudad', cp='$cp' WHERE id='".$dirId."'";
    mysqli_query($conn, $query);
    $affectedRows = mysqli_affected_rows($conn);
    if ($affectedRows > 0) {
        
        $correcto = true;
    }
    closeCon($conn);

    return $correcto;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Añadir Producto - UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
        <link href="../css/producto.css" rel="stylesheet" type="text/css"/>
        <link href="../css/aniadirProducto.css" rel="stylesheet" type="text/css"/>
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
    </head>

    <body>
<?php
include './header.php';
?>  

        <!-- Page Content -->
        <main class="container">
            <div class="row">
                
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <form enctype="multipart/form-data" action="#" method="post">
                        <div class="form-group">
                            <label for="direccion1">Dirección</label>
                            <input id="producto" value="<?php echo $row['linea_1']; ?>" name="direccion1" class="form-control" required="true" placeholder="Calle, número, piso, puerta"/>
                            <label for="direccion2">Dirección</label>
                            <input id="producto" value="<?php echo $row['linea_2']; ?>" name="direccion2" class="form-control" placeholder="Opcional"/>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="provincia">Provincia</label>
                                <input type="text" value="<?php echo $row['provincia']; ?>" class="form-control" name="provincia" placeholder="Provincia" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" value="<?php echo $row['ciudad']; ?>" class="form-control" name="ciudad" placeholder="Ciudad" required="true">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cp">Código Postal</label>
                                <input type="text" value="<?php echo $row['cp']; ?>" class="form-control" name="cp" placeholder="CP" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="provincia">Nombre</label>
                                <input type="text" value="<?php echo $row['nombre']; ?>" class="form-control" name="nombre" placeholder="Guarda un nombre para esta dirección" required="true">
                            </div>
                        </div>
                        <input type="hidden" name="dir" value="<?php echo $_GET['dir'];?>"/>
                        <button name="btnEditDireccion" type="submit" class="btn btn-primary">Editar</button>
                    </form>
                </div>
                <!-- /.col-lg-9 -->
            </div>
        </div>
    </div>
</main>
<!--/.container -->
<?php
include '../html/footer.html';
?>
</body>

</html>