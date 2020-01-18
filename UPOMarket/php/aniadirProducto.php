<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
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
            // Material Select Initialization
            $(document).ready(function () {
                $('.mdb-select').materialSelect();
            });
        </script>
    </head>

    <body>
        <?php
        include './header.php';
        include './utils/utilsProductos.php'
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
                    <form enctype="multipart-form_data" >
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" class="form-control" placeholder="Escriba una descripción del producto" rows="5"></textarea><!--Controlar numero de palabras JS? -->
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Característica</label>
                                <input type="text" class="form-control" id="caracteristicaName" placeholder="Nombre característica">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Descripción Característica</label>
                                <input type="text" class="form-control" id="caracteristicaDesc" placeholder="Descripción característica">
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

                                        echo "<tr><td class='check-td'><input class='form-check-input' type='checkbox' value='" . $v[0] . "'>$v[0]</td></tr>";
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div>
                            <label>Añade una imagen</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" >
                                <label class="custom-file-label" for="customFile">Selecciona una imagen</label>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="precio">Indique el precio de la unidad</label>
                                    <input id="precio" class="form-control" placeholder="Precio en €"/>
                                </div>
                                <div class = "form-group col-md-6">
                                    <label for = "stock">Indique el stock del que dispone</label>
                                    <input id = "precio" type = "number" class = "form-control" placeholder = "Precio en €"/>
                                </div>
                            </div>
                            <div class = "form-group">
                                <div class = "form-check">
                                    <input class = "form-check-input" type = "checkbox" id = "condiciones">
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