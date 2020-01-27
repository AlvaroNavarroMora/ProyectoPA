<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

include './utils/utilsProductos.php';
include './utils/sesionUtils.php';




/* Añadir nombre del formulario registro */
if (isset($_POST['btnUpdateProduct']) || isset($_POST['btnUpdateDisponibilidad'])) {
    if(isset($_POST['btnUpdateDisponibilidad'])){
        $disponibilidad=0;
    }else{
        $disponibilidad=1;
    }
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $id = filter_var($_POST['idProducto'], FILTER_SANITIZE_MAGIC_QUOTES);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
    $nombreProducto = filter_var($_POST['producto'], FILTER_SANITIZE_MAGIC_QUOTES);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_MAGIC_QUOTES);
    $categorias = filter_var_array($_POST['cats'], FILTER_SANITIZE_MAGIC_QUOTES);
    $caracteristicaName = filter_var_array($_POST['caracteristicaName'], FILTER_SANITIZE_MAGIC_QUOTES);
    $caracteristicaDesc = filter_var_array($_POST['caracteristicaDesc'], FILTER_SANITIZE_MAGIC_QUOTES);
    $precio = filter_var($_POST['precio'], FILTER_SANITIZE_MAGIC_QUOTES);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_MAGIC_QUOTES);


    if ($id === false || $stock === false || $password === false || $precio === false || $email === false || $descripcion === false || $nombreProducto === false || $caracteristicaName === false || $caracteristicaDesc == false) {
        $errores[] = "Error con los datos del formulario";
    }
    if (strlen(trim($id)) < 1) {
        $errores[] = "Error en los datos del formulario";
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

    if (strlen(trim($nombreProducto)) < 1) {
        $errores[] = "El campo producto es obligatorio.";
    }

    if (strlen(trim($precio)) < 1) {
        $errores[] = "El campo precio es obligatorio.";
    } elseif (floatval($precio) <= 0) {
        $errores[] = "El campo precio debe valer más de 0";
    }

    if (strlen(trim($descripcion)) < 1) {
        $errores[] = "El campo descripcion es obligatorio.";
    }
    if (strlen(trim($stock)) < 1) {
        $errores[] = "El campo stock es obligatorio.";
    }
    if (sizeof($categorias) < 1) {
        $errores[] = "Debe añadir al menos una categoría";
    } else {
        for ($i = 0; $i < count($categorias); $i++) {
            if (strlen($categorias[$i]) < 1) {
                $errores[] = "El nombre de la categoria $i debe ser más largo";
            }
        }
    }
    if (!isset($_POST['condiciones'])) {
        $errores[] = "Debe aceptar los términos y condiciones";
    }
    if (sizeof($caracteristicaName) < 1 || sizeof($caracteristicaDesc) < 1) {
        $errores[] = "Debe añadir al menos una característica";
    }
    if (sizeof($caracteristicaName) != sizeof($caracteristicaDesc)) {
        $errores[] = "Debe haber tantas características como descripciones";
    } else {
        for ($i = 0; $i < count($caracteristicaName); $i++) {
            if (strlen($caracteristicaName[$i]) < 1) {
                $errores[] = "El nombre de la característica $i debe ser más largo";
            }
            if (strlen($caracteristicaDesc[$i]) < 1) {
                $errores[] = "La descripción de la característica $i debe ser más largo";
            }
        }
    }
    if (!comprobarSesionActual($email) || comprobarUsuarioContraseña($email, $password)) {
        $errores[] = "Credenciales incorrectas";
    } else {
        if (!comprobarUsuarioIdProducto($email, $id)) {
            $errores[] = "Error en los datos";
        }
        if (comprobarSiPuedeModificarProducto($email, $id, $nombreProducto)) {
            $errores[] = "Ya tiene otro producto con ese nombre";
        }
    }

    foreach ($_FILES['files']['error'] as $k => $v) {
        if ($v != 0) {
            $errores[] = "Error en la imagen " . $_FILES['name'][$k];
        }
    }
    if (empty($errores)) {
        //Si la insercion falla $credenciales=false, sino $credenciales tendrá el nombre de usuario y su id para guardar la sesion
        $pathProductos = "../img/usrFotos/$email/products"; /* Carpeta para almacenar fotos de los productos del usuario */
        $pathThisProducto = "../img/usrFotos/$email/products/$nombreProducto"; /* Carpeta para almacenar fotos de los productos del usuario */
        if (!is_dir($pathProductos)) {
            mkdir($pathProductos);
        }
        if (!is_dir($pathThisProducto)) {
            mkdir($pathThisProducto);
        }
        foreach ($_FILES['files']['tmp_name'] as $k => $v) {
            //Nombre temporal
            $tmp_name = $_FILES['files']['tmp_name'][$k];
            //Nuevo nombre
            $newName = str_replace(".", time() . ".", $_FILES['files']['name'][$k]);
            //Ruta destino + nuevo nombre
            $newPath = $pathThisProducto . '/' . $newName;
            //Mover la imagen al destino
            move_uploaded_file($tmp_name, $newPath);
        }
        $haModificado = false;
        modificarProducto($id, $email, $nombreProducto, $descripcion, $precio, $stock, $newPath, $categorias, $caracteristicaName, $caracteristicaDesc,$disponibilidad);
        if ($haModificado) {
            header("Location: ./producto.php?idProducto=$id");
        } else {
            $errores[] = "Error al guardar los datos";
        }
    }
}

if (isset($_POST['idProducto'])) {
    $id = filter_var($_POST['idProducto'], FILTER_SANITIZE_MAGIC_QUOTES);
    if ($id === false || strlen(trim($id)) < 1) {
        $errores[] = "Errores en los datos";
    }
    $producto = obtenerProductoTotales($id);
    if ($_SESSION['email'] !== $producto['email_vendedor']) {
        $errores[] = "Errores en los datos";
    }

    if (isset($errores)) {
        header('Location: ./principal.php');
    }
} elseif (!isset($_POST['btnUpdateProduct']) || !isset($_POST['btnUpdateDisponibilidad'])) {
    header('Location: ./principal.php');
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" type="text/css"/>
        <script>

            function readURL(input) {
                $('#preview').empty();
                var readers = new Array();
                for (var i = 0; i < input.files.length; i++) {
                    readers[i] = new FileReader();
                    if (input.files && input.files[i]) {
                        readers[i].onload = function (e) {
                            $('#preview').append("<img src=" + e.target.result + " alt='your image' class='img-thumbnail' />");
                        }
                        if (input.files.length == 1) {
                            $('#imgLab').empty();
                            $('#imgLab').append(input.files[i].name);
                        }

                        readers[i].readAsDataURL(input.files[i]);
                    }
                }
            }
            $(document).ready(function () {
                $(".chosen-select").chosen({disable_search_threshold: 10});
                $("#file").change(function () {
                    readURL(this);
                });
                $('#add_field').click(function (e) {
                    e.preventDefault(); //prevenir nnuevos clicks

                    $('#caracteristicas').append(
                            "<div class='form-row'>\n\
                                <div class='col-md-4 mb-3'>\
                                    <input type='text' class='form-control' name='caracteristicaName[]' placeholder='Nombre característica'required='true'>\
                                </div>\
                                <div class='col-md-4 mb-3'>\
                                    <input type='text' class='form-control' name='caracteristicaDesc[]' placeholder='Descripción característica' required='true'>\
                                </div><a href='#' class='remover_campo'>Remover</a>\n\
                            </div>");
                });
                // Remover o div anterior
                $('#caracteristicas').on("click", ".remover_campo", function (e) {
                    e.preventDefault();
                    $(this).parent('div').remove();

                });
            });
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
                        <?php
                        $id = $_POST['idProducto'];
                        echo "<input type='hidden' class='form-control' name='idProducto' required='true' value='$id'>";
                        ?>
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
                            <label>Nombre del producto</label>
                            <input id="producto" name="producto" class="form-control" required="true"  value="<?php echo $producto['nombre']; ?>">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control"  rows="5" required="true" value="<?php echo $producto['descripcion']; ?>"><?php echo $producto['descripcion']; ?></textarea><!--Controlar numero de palabras JS? -->
                        </div>
                        <button type = "button" id = "add_field" class = "btn btn-primary">Agregar</button>
                        <div id = "caracteristicas">
                            <?php
                            $caracteristicas = listarCaracteristicasProducto($producto['id']);
                            ?>
                            <div class = "form-row">
                                <!--<div class = "form-group col-md-6"> -->
                                <div class = "col-md-4 mb-3">
                                    <label>Característica</label>
                                    <input type = "text" class = "form-control" name = "caracteristicaName[]" required = "true" value="<?php echo $caracteristicas[0]['nombre_caracteristica']; ?>">
                                </div>
                                <div class = "col-md-4 mb-3">
                                    <label>Descripción Característica</label>
                                    <input type = "text" class = "form-control" name = "caracteristicaDesc[]" required = "true" value="<?php echo $caracteristicas[0]['valor']; ?>">
                                </div>

                            </div>
                            <?php
                            for ($j = 1; $j < sizeof($caracteristicas); $j++) {
                                $name = $caracteristicas[$j]['nombre_caracteristica'];
                                $desc = $caracteristicas[$j]['valor'];
                                echo "<div class='form-row'>
                                <div class='col-md-4 mb-3'>
                                    <input type='text' class='form-control' name='caracteristicaName[]' required='true' value='$name'>
                                </div>
                                <div class='col-md-4 mb-3'>
                                    <input type='text' class='form-control' name='caracteristicaDesc[]' required='true' value='$desc'>
                                </div><a href='#' class='remover_campo'>Remover</a>
                                </div>";
                            }
                            ?>
                        </div>
                        <div id = "miSelect" class = "form-row">
                            <label>Categorias</label>
                            <select name = "cats[]" data-placeholder = "Seleccione alguna categoria" multiple class = "form-control chosen-select" tabindex = "-1" >
                                <option value = ""></option>
                                <?php
                                $categorias = listarCategorias();
                                foreach ($categorias as $v) {
                                    echo "<option type='checkbox' value='" . $v[0] . "'>$v[0]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <div>
                                <label>Añade una imagen</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="files[]"  required="true">
                                    <label  id="imgLab" class="custom-file-label" for="customFile" value=" <?php echo $producto['imagen']; ?>">Selecciona una imagen</label>
                                    <div id="filesName" >
                                    </div>
                                </div>
                                <div id="preview">
                                    <?php
                                    $img = $producto['imagen'];
                                    echo "<img src='$img' alt='your image' class='img-thumbnail' />";
                                    ?>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="precio">Indique el precio de la unidad</label>
                                        <input name="precio" class="form-control" required="true" value="<?php echo $producto['precio']; ?>"/>
                                    </div>
                                    <div class = "form-group col-md-6">
                                        <label for = "stock">Indique el stock del que dispone</label>
                                        <input name = "stock" type="text" class = "form-control" required="true" value="<?php echo $producto['stock']; ?>"/>
                                    </div>
                                </div>
                                <div class = "form-group">
                                    <div class = "form-check">
                                        <input class = "form-check-input" type="checkbox" name="condiciones" id = "condiciones" required="true">
                                        <label class = "form-check-label" for = "gridCheck">
                                            Acepto los terminos y condiciones
                                        </label>
                                    </div>
                                </div>
                                <button name="btnUpdateProduct" type="submit" class="btn btn-success" title="Al actualizar tu producto lo pondrás a la venta y cualquier usuario podrá verlo">Modificar</button>
                                <button name="btnUpdateDisponibilidad" type="submit" class="btn btn-primary" title="Al actualizar tu producto no estará a la venta">Modificar y ocultar</button>
                                <!-- /.col-lg-9 -->
                            </div>
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