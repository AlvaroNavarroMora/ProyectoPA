<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ./principal.php");
}

include './utils/manejadorBD.php';
include './utils/sesionUtils.php';

/* Filtro sobre los datos del formulario para añadir direcciones. */
if (isset($_POST['btnAddDireccion'])) {
    $direccion1 = trim(filter_var($_POST['direccion1'], FILTER_SANITIZE_STRING));
    $direccion2 = trim(filter_var($_POST['direccion2'], FILTER_SANITIZE_STRING));
    $provincia = trim(filter_var($_POST['provincia'], FILTER_SANITIZE_STRING));
    $ciudad = trim(filter_var($_POST['ciudad'], FILTER_SANITIZE_STRING));
    $cp = filter_var($_POST['cp'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));

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
        if (aniadirDireccion($_SESSION["email"], $nombre, $direccion1, $direccion2, $provincia, $ciudad, $cp)) {
            header("location:./perfil.php");
        } else {
            $errores[] = "No se pudo aniadir la direccion";
        }
    }

    if (!empty($errores)) {
        foreach ($errores as $e) {
            echo $e . "<br>";
        }
    }
}
/*Esta función se encarga de añadir una dirección a la base de datos, preparando los datos y creando la consulta.*/
function aniadirDireccion($email, $nombre, $direccion1, $direccion2, $provincia, $ciudad, $cp) {
    $correcto = false;
    $conn = openCon();
    mysqli_set_charset($conn, "utf8");
    if (!$conn) {
        die("No se pudo conectar a la Base de Datos");
    }
    $query = "INSERT INTO direcciones (nombre,linea_1,linea_2,provincia,ciudad,cp) VALUES('$nombre','$direccion1','$direccion2','$provincia','$ciudad',$cp)";
    mysqli_query($conn, $query);
    $id_direccion = mysqli_insert_id($conn);
    if ($id_direccion > 0) {
        $query = "INSERT INTO direcciones_clientes (email_cliente, direccion_cliente) VALUES('$email','$id_direccion')";
        mysqli_query($conn, $query);
        if(mysqli_affected_rows($conn) > 0) {
            $correcto = true;
        }
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

        <title>Añadir Dirección - UPOMarket</title>
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
                            <input id="producto" name="direccion1" class="form-control" required="true" placeholder="Calle, número, piso, puerta"/>
                            <label for="direccion2">Dirección</label>
                            <input id="producto" name="direccion2" class="form-control" placeholder="Opcional"/>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="provincia">Provincia</label>
                                <input type="text" class="form-control" name="provincia" placeholder="Provincia" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" placeholder="Ciudad" required="true">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cp">Código Postal</label>
                                <input type="text" class="form-control" name="cp" placeholder="CP" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="provincia">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Guarda un nombre para esta dirección" required="true">
                            </div>
                        </div>
                        <button name="btnAddDireccion" type="submit" class="btn btn-primary">Añadir</button>
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