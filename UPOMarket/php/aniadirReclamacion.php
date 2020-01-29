<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] == "vendedor")) {
    header("location: ./principal.php");
}
/* Desde esta página el cliente puede crear una nueva reclamación */
include './utils/utilsProductos.php';
include './utils/sesionUtils.php';
include './utils/utilsConflicto.php';

/* Filtrado y saneamiento del formulario que se usa para añadir una nueva reclamación */
if (isset($_POST['btnAddReclamacion'])) {
    //print_r($_POST);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    $producto = filter_var($_POST['producto'], FILTER_SANITIZE_MAGIC_QUOTES);
    $pedido = filter_var($_POST['pedido'], FILTER_SANITIZE_MAGIC_QUOTES);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_MAGIC_QUOTES);

    if ($password === false || $email === false || $descripcion === false || $producto === false || $pedido === false) {
        $errores[] = "Error con los datos del formulario";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El campo email esta mal rellenado.";
    }
    if (strlen(trim($email)) < 1) {
        $errores[] = "El campo email es obligatorio.";
    }
    if ($email !== $_SESSION['email']) {
        $errores[] = "El campo email esta mal rellenado.";
    }
    if (strlen(trim($password)) < 1) {
        $errores[] = "El campo contrasenia es obligatorio.";
    }
    if (strlen(trim($pedido)) < 1) {
        $errores[] = "Error en el formulario Campo pedido";
    }
    if (strlen(trim($producto)) < 1) {
        $errores[] = "El campo producto es obligatorio.";
    }

    if (strlen(trim($descripcion)) < 1) {
        $errores[] = "El campo descripcion es obligatorio.";
    }

    if (!comprobarSesionActual($email) || comprobarUsuarioContraseña($email, $password)) {
        $errores[] = "Credenciales incorrectas";
    } else {
        if (comprobarUsuarioProducto($email, $producto)) {
            $errores[] = "Ya tiene un producto con ese nombre";
        }
    }

    if (empty($errores)) {

        crearReclamacion($pedido, $producto, $descripcion);
        header('Location: ./reclamacionesRealizadas.php');
    }
}

if (isset($_POST['submitReclamacion'])) {
    $idPedido = filter_var($_POST['idPedido'], FILTER_SANITIZE_EMAIL);

    if ($idPedido === false) {
        $errores[] = "Error con los datos del formulario";
    }

    if (strlen(trim($idPedido)) < 1) {
        $errores[] = "Error con los datos del formulario";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Añadir Reclamacion - UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
        <link href="../css/producto.css" rel="stylesheet" type="text/css"/>
        <link href="../css/aniadirProducto.css" rel="stylesheet" type="text/css"/>
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" type="text/css"/>
        <script>



        </script>
    </head>

    <body>
        <?php
        include './header.php';
        ?>  

        <!-- Page Content -->
        <main class="container">
            <div class="row">

                <div class="col-lg-9">
                    <?php
                    if (isset($errores)) {
                        echo "<div class = 'alert alert-danger'><ul>";
                        echo "<h6>Upss, parece que algo ha salido mal.</h6>";
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                        echo "</div>";
                    }
                    ?>  

                    <form enctype="multipart/form-data" action="#" method="post">
                        <input type="hidden" class="form-control" name="pedido" required="true" value="<?php echo $idPedido ?>" >
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">Introduce tú Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" placeholder="Contraseña" required="true">
                            </div>
                        </div>
                        <div class="form-row">

                            <div id="miSelect" class=" form-group col-md-12 ">
                                <label>Seleccione un producto</label>
                                <select name="producto" data-placeholder="Seleccione el producto" class="form-control chosen-select" tabindex="-1" >
                                    <option value=""></option>
                                    <?php
                                    $productos = listarProductosPedido($idPedido);
                                    foreach ($productos as $v) {
                                        echo "<option type='checkbox' value='" . $v[1] . "'>$v[0]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class=" form-group col-md-12 ">
                                <label for="descripcion">Descripción</label>
                                <textarea name="descripcion" class="form-control" placeholder="Escriba una descripción del problema" rows="5" required="true"></textarea><!--Controlar numero de palabras JS? -->
                            </div>  </div>

                        <button name="btnAddReclamacion" type="submit" class="btn btn-primary">Reclamar</button>
                    </form>
                </div>
        </main>
        <!--/.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>