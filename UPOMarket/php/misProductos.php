<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

include "./utils/manejadorBD.php";
$data = json_encode(obtenerMisProductos($_SESSION["email"]));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Mis Productos - UPOMarket</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/misproductos.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        <script>
            $(document).ready(function () {
                var data = <?php echo $data ?>;
                $('#productos').DataTable({
                    "language":{
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    "columns": [
                        {"data": "id"},
                        {"data": "nombre"},
                        {"data": "descripcion"},
                        {"data": "precio"},
                        {"data": "stock"},
                        {"data": "imagen"},
                        {"data": "disponible"}
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
        <form id="formProducto" action="producto.php" method="get" hidden>
        </form>
        <?php
        include './header.php';
        ?>
        <!-- Page Content -->
        <main class="container">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav class="list-group">
                        <ul class="list-unstyled">
                            <li><a href="aniadirProducto.php" class="list-group-item">Añadir Producto</a></li>
                            <li><a href="#" class="list-group-item">Category 2</a></li>
                            <li><a href="#" class="list-group-item">Category 3</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <table id="productos" class="table table-striped table-bordered dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Disponible</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.col-lg-9 -->
            </div>
            <!-- /.row -->
        </main>
        <?php
        include '../html/footer.html';
        ?>
    </body>
</html>
<?php

function obtenerMisProductos($email) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "SELECT * from productos where email_vendedor='$email'";
    $result = mysqli_query($con, $query);
    $productos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }

    closeCon($con);

    return $productos;
}
?>