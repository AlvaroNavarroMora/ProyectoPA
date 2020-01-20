<?php
session_start();
include './utils/utilsProductos.php';
$categorias = listarCategorias();

if (!isset($_GET["busqueda"])) {
    header("location:principal.php");
}
$busca = trim(filter_var($_GET["busqueda"], FILTER_SANITIZE_STRING));
$productos = buscarProductos($busca);
if (empty($productos)) {
    $errores[] = "No se ha encontrado ningún producto";
} else {
    $data = json_encode($productos);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Inicio-UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/misproductos.css" rel="stylesheet">

        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function () {
                var data = <?php if (isset($data)) echo $data;
else echo "null" ?>;
                if (data == null) {
                    $("#contenedorTablaProductos").hide();
                }
                $('#productos').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    "searching": false,
                    "order": [[1, "asc"]],
                    "columns": [
                        {"data": "id"},
                        {"data": "nombre"},
                        {"data": "descripcion"},
                        {"data": "precio"}
                    ],
                    "drawCallback": function () {
                        var table = $('#productos').DataTable();

                        $('#productos tbody').on('click', 'tr', function () {
                            var id = table.row(this).data().id;
                            var input = $("<input type='text' name='idProducto'/>");
                            $(input).val(id);
                            $("#formProducto").append(input);
                            $("#formProducto").submit();
                        });
                    }
                });
            });
        </script>

    </head>

    <body>
        <form id="formProducto" action="producto.php" method="GET" hidden>
        </form>
        <?php
        include './header.php';
        ?>
        <!-- Page Content -->
        <main class="container">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav id='categorias' class="list-group">
                        <ul class="list-unstyled">
                            <?php
                            foreach ($categorias as $c) {
                                echo '<li><a href="#" class="list-group-item">' . $c[0] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <!-- /.col-lg-9 -->
                    <!-- Search form -->
                    <form id='searchForm' class="form-inline md-form mr-auto mb-4" action='buscaProductos.php' method="GET">
                        <div class="input-group">
                            <input id='searchBar' type="text" class="form-control" placeholder="Buscar productos" name='busqueda'>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="subimt">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <h4>
                        <?php
                        if (empty($errores)) {
                            echo 'Se han econtrado ' . count($productos) . " coincidencias para la búsqueda '" . $busca . "':";
                        } else {
                            foreach ($errores as $e) {
                                echo $e;
                            }
                        }
                        ?>
                    </h4>
                    <div id='contenedorTablaProductos' class='container'>
                        <table id="productos" class="table table-striped table-bordered dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.row -->
            </div>

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>