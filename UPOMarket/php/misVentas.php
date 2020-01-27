<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

include "./utils/manejadorBD.php";
$data = json_encode(obtenerMisVentas($_SESSION["email"]));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Mis Ventas - UPOMarket</title>
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
                $('#ventas').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    "columns": [
                        {"data": "id"},
                        {"data": "email_cliente"},
                        {"data": "num_productos"},
                        {"data": "importe"},
                        {"data": "fecha"}
                    ],
                    "drawCallback": function () {
                        var table = $('#ventas').DataTable();

                        $('#ventas tbody').on('click', 'tr', function () {
                            var value = table.row(this).data().id;
                            var input = $("<input type='text' name='idVenta'/>");
                            $(input).val(value);
                            $("#formVenta").append(input);

                            value = table.row(this).data().email_cliente;
                            input = $("<input type='text' name='cliente'/>");
                            $(input).val(value);
                            $("#formVenta").append(input);

                            value = table.row(this).data().importe;
                            input = $("<input type='text' name='importe'/>");
                            $(input).val(value);
                            $("#formVenta").append(input);

                            value = table.row(this).data().fecha;
                            input = $("<input type='text' name='fecha'/>");
                            $(input).val(value);
                            $("#formVenta").append(input);

                            $("#formVenta").submit();
                        });
                    }
                });
            });
        </script>
    </head>

    <body>
        <form id="formVenta" action="venta.php" method="post" hidden>
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
                            <li><a href="misProductos.php" class="list-group-item">Mis Productos</a></li>
                            <li><a href="misVentas.php" class="list-group-item active">Mis Ventas</a></li>
                            <li><a href="misReclamaciones.php" class="list-group-item">Mis Reclamaciones</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <table id="ventas" class="table table-striped table-bordered dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email Cliente</th>
                                <th>Número de productos</th>
                                <th>Importe(&euro;)</th>
                                <th>Fecha</th>
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

function obtenerMisVentas($email) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "SELECT p.id as 'id', p.email_cliente, count(p.id) as 'num_productos', sum(lp.cantidad*prod.precio) as 'importe', p.fecha FROM pedidos p, lineas_de_pedido lp, productos prod WHERE prod.email_vendedor='$email' AND lp.id_pedido = p.id AND lp.id_producto = prod.id GROUP BY lp.id_pedido";
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