<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

include './utils/utilsProductos.php';
include './utils/sesionUtils.php';

/* Añadir nombre del formulario registro */
if (isset($_POST['btnAddProduct'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    $producto = filter_var($_POST['producto'], FILTER_SANITIZE_MAGIC_QUOTES);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_MAGIC_QUOTES);
    $categorias = filter_var_array($_POST['cats'], FILTER_SANITIZE_MAGIC_QUOTES);
    $precio = filter_var($_POST['precio'], FILTER_SANITIZE_MAGIC_QUOTES);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_MAGIC_QUOTES);


    if ($stock === false || $password === false || $precio === false || $email === false || $descripcion === false || $producto === false) {
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
    }//Falta comprobar que usuario y contraseña son válidos

    if (strlen(trim($producto)) < 1) {
        $errores[] = "El campo producto es obligatorio.";
    }

    if (strlen(trim($precio)) < 1) {
        $errores[] = "El campo precio es obligatorio.";
    } /* elseif (is_float(floatval($precio))) {
      $errores[] = "El campo precio debe ser un numero.";
      } */

    if (strlen(trim($descripcion)) < 1) {
        $errores[] = "El campo descripcion es obligatorio.";
    }
    if (strlen(trim($stock)) < 1) {
        $errores[] = "El campo stock es obligatorio.";
    } /* elseif (!is_int($stock)) {
      $errores[] = "El campo stock debe ser un numero entero.";
      } */

    if (!comprobarSesionActual($email) || comprobarUsuarioContraseña($email, $password)) {
        $errores[] = "Credenciales incorrectas";
    }

    foreach ($_FILES['files']['error'] as $k => $v) {
        if ($v != 0) {
            $errores[] = "Error en la imagen " . $_FILES['name'][$k];
        }
    }
    if (empty($errores)) {
        //Si la insercion falla $credenciales=false, sino $credenciales tendrá el nombre de usuario y su id para guardar la sesion
        $pathProductos = "../img/usrFotos/$email/products"; /* Carpeta para almacenar fotos de los productos del usuario */
        $pathThisProducto = "../img/usrFotos/$email/products/$producto"; /* Carpeta para almacenar fotos de los productos del usuario */
        if (!is_dir($pathProductos)) {
            mkdir($pathProductos);
        }
        if (!is_dir($pathThisProducto)) {
            mkdir($pathThisProducto);
        }
        $paths = "";
        foreach ($_FILES['files']['tmp_name'] as $k => $v) {
            //Nombre temporal
            $tmp_name = $_FILES['files']['tmp_name'][$k];
            //Nuevo nombre
            $newName = str_replace(".", time() . ".", $_FILES['files']['name'][$k]);
            //Ruta destino + nuevo nombre
            $newPath = $pathThisProducto . '/' . $newName;
            //Todas las imagenes de un producto
            $paths = $paths . $newPath . ";";
            //Mover la imagen al destino
            move_uploaded_file($tmp_name, $newPath);
        }
        $haInsertado = insertarProducto($email, $producto, $descripcion, $precio, $stock, $paths, $categorias);
        if ($haInsertado) {
            header('Location: ./perfil.php');
        } else {
            $errores[] = "Error al guardar los datos";
        }
    }
    if (isset($errores)) {
        print_r($errores);
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

        <title>Añadir Producto - UPOMarket</title>
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
        <script>



            function addImg() {
                var lista = document.getElementsByName('files[]');
                //console.table(lista[0].files);
                var misFiles = lista[0].files;


                var $divInputs = $('#filesName');
                $divInputs.empty();

                for (var i = 0; i < misFiles.length; i++) {
                    //console.log(misFiles[i].name);
                    $divInputs.append("<label>" + misFiles[i].name + "</label><br>");
                }

            }
        </script>
    </head>

    <body>
        <?php
        include './header.php';
        ?>  

        <!-- Page Content -->
        <main class="container">
            <div class="row">
                <!-- LISTA DE CATEGORÍAS -->
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">Category 1</a>
                        <a href="#" class="list-group-item">Category 2</a>
                        <a href="#" class="list-group-item">Category 3</a>
                    </div>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <form enctype="multipart/form-data" action="#" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" placeholder="Contraseña" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="producto">Nombre del producto</label>
                            <input id="producto" name="producto" class="form-control" required="true"/>
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" class="form-control" placeholder="Escriba una descripción del producto" rows="5" required="true"></textarea><!--Controlar numero de palabras JS? -->
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Característica</label>
                                <input type="text" class="form-control" name="caracteristicaName" placeholder="Nombre característica" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Descripción Característica</label>
                                <input type="text" class="form-control" name="caracteristicaDesc" placeholder="Descripción característica" required="true">
                            </div>
                        </div>

                        <div class="form-row table-form">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Categorías seleccionadas</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $categorias = listarCategorias();
                                    foreach ($categorias as $v) {
                                        echo "<tr><td class='check-td'><input class='form-check-input' name='cats[]' type='checkbox' value='" . $v[0] . "'>$v[0]</td></tr>";
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div>
                            <label>Añade una imagen</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="files[]" onchange="addImg()" multiple required="true">
                                <label class="custom-file-label" for="customFile">Selecciona una imagen</label>

                                <div id="filesName" >

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="precio">Indique el precio de la unidad</label>
                                    <input name="precio" class="form-control" placeholder="Precio en €" required="true"/>
                                </div>
                                <div class = "form-group col-md-6">
                                    <label for = "stock">Indique el stock del que dispone</label>
                                    <input name = "stock" type="text" class = "form-control" placeholder = "Stock del producto" required="true"/>
                                </div>
                            </div>
                            <div class = "form-group">
                                <div class = "form-check">
                                    <input class = "form-check-input" type="checkbox" id = "condiciones" required="true">
                                    <label class = "form-check-label" for = "gridCheck">
                                        Acepto los terminos y condiciones
                                    </label>
                                </div>
                            </div>
                            <button name="btnAddProduct" type="submit" class="btn btn-primary">Crear</button>
                            <!-- /.col-lg-9 -->
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <!--/.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>