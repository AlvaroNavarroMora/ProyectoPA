<?php
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";
session_start();
if (isset($_SESSION['email'])) {
    $data = json_encode(obtenerConflictos());
} else {
    header('Location: ./principal.php');
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Conflictos</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <link href="../css/perfil.css" rel="stylesheet" type="text/css"/>
    <script src="../frameworks/jquery/jquery.min.js"></script>
    <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

    <script>
        $(document).ready(function () {
            var data = <?php echo $data ?>;
            $('#conflictos').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "data": data,
                "paging": true,
                "ordering": true,
                "columns": [
                    {"data": "id_pedido"},
                    {"data": "id_producto"},
                    {"data": "email_vendedor"},
                    {"data": "email_cliente"},
                    {"data": "descripcion"},
                    {"data": "estado"},
                    {"data": "fecha"},
                    {"data": "decision"}

                ],
                "drawCallback": function () {
                    var table = $('#conflictos').DataTable();

                    /* $('#conflictos tbody').on('click', 'tr', function () {
                     var id = table.row(this).data().id;
                     var input = $("<input type='text' name='idProducto'/>");
                     $(input).val(id);
                     $("#formProducto").append(input);
                     $("#formProducto").submit();
                     });*/

                    var decisiones = table.column(7).data();
                    var rows = $("tbody tr");
                    /*modif acp*/
                    for (var i = 0; i < decisiones.length; i++) {

                        var aux = $(rows[i]).children()[7];
                        $(aux).empty();

                        var btnDarRazonCliente = document.createElement("button");
                        $(btnDarRazonCliente).text("CLIENTE");
                        $(btnDarRazonCliente).attr("class", "btn btn-success");
                        $(btnDarRazonVendedor).attr("onclick", "darRazonCliente()");
                        aux.append(btnDarRazonCliente);

                        var btnDarRazonVendedor = document.createElement("button");
                        $(btnDarRazonVendedor).text("VENDEDOR");
                        $(btnDarRazonVendedor).attr("class", "btn btn-danger");
                        $(btnDarRazonVendedor).attr("onclick", "darRazonVendedor()");
                        aux.append(btnDarRazonCliente);
                        aux.append(btnDarRazonVendedor);

                        //aux.replaceChild(btnDar, aux.firstChild);
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
                <table id="conflictos" class="table table-striped table-bordered dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Producto</th>
                            <th>Vendedor</th>
                            <th>Cliente</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Decision</th>
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

function obtenerConflictos() {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "SELECT r.`id_pedido`, r.`id_producto`,p.`nombre`, p.`email_vendedor`, v.`email_cliente`, r.`descripcion`, r.`estado`, r.`fecha` "
            . "FROM `reclamaciones` as r,`productos` as p, `pedidos` as v "
            . "WHERE r.`id_producto`=p.`id` "
            . "AND r.`id_pedido`=v.`id` "
            . "and r.`estado`='No Resuelto'";
    $result = mysqli_query($con, $query);
    $conflictos = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $conflictos[] = $row;
        }
    }

    closeCon($con);
    $conflictos[0]['decision'] = "";
    return $conflictos;
}
?>


