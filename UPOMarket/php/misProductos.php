<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

include "./utils/utilsProductos.php";
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
        <link href="../css/misProductos.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        <script>
            $(document).ready(function () {
                var data = <?php echo $data ?>;
                $('#productos').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    columnDefs: [{
                            targets: [2],
                            render: function (data, type, row) {
                                return data.length > 20 ?
                                        data.substr(0, 20) + '…' :
                                        data;
                            }
                        }],
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
                        var imgs = table.column(5).data();
                        var rows = $("tbody tr");
                        /*modif acp*/
                        for (var i = 0; i < imgs.length; i++) {
                            var aux = $(rows[i]).children()[5];

                            path = data[i]['imagen'];
                            var imagen = document.createElement("img");
                            $(imagen).attr("src", path);
                            $(imagen).attr("alt", "No disponible");
                            $(imagen).attr("onerror", "reemplazarImg(this)");
                            $(imagen).addClass("mostrarImagen");
                            aux.replaceChild(imagen, aux.firstChild);
                        }/*Fin modif acp*/
                        var disponibles = table.column(6).data();
                        for (var i = 0; i < disponibles.length; i++) {
                            var aux = $(rows[i]).children()[6];
                            var disponible = data[i]['disponible'];
                            if (disponible == 1) {
                                var text = document.createTextNode("Disponible");
                            } else {
                                var text = document.createTextNode("No disponible");
                            }
                            aux.replaceChild(text, aux.firstChild);
                        }

                    }
                });
            });
            function reemplazarImg(img) {
                $(img).attr("src", "../img/productDefaultImage.jpg");
            }
        </script>
    </head>

    <body>
        <form id="formProducto" action="editarProducto.php" method="POST" hidden>
        </form>
        <?php
        include './header.php';
        ?>
        <!-- Page Content -->
        <main class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav class="list-group">
                        <ul class="list-unstyled">
                            <li><a href="misProductos.php" class="list-group-item active">Mis Productos</a></li>
                            <li><a href="misVentas.php" class="list-group-item">Mis Ventas</a></li>
                            <li><a href="misReclamaciones.php" class="list-group-item">Mis Reclamaciones</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <a id="btn-aniadir-producto" class="btn btn-sm btn-primary" href="./aniadirProducto.php" role="button">Añadir Producto</a>
                    <table id="productos" class="table table-striped table-bordered dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio(&euro;)</th>
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