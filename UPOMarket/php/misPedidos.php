<?php
/*
  En esta página mostramos los distintos pedidos realizados por un usuario
 */

session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo'])) {
    header("location: ./principal.php");
}

include "./utils/manejadorBD.php";
$data = json_encode(obtenerMisPedidos($_SESSION["email"]));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Mis Pedidos - UPOMarket</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/misProductos.css" rel="stylesheet">
        <link href="../css/misReclamaciones.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        <script>
            //Creación del data Table
            $(document).ready(function () {
                var data = <?php echo $data ?>;
                $('#pedidos').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "order": [[1, "desc"]],
                    "ordering": true,
                    "columns": [
                        {"data": "idPedido"},
                        {"data": "fecha"},
                        {"data": "precio"},
                        {"data": "estado"}
                    ],
                    "drawCallback": function () {
                        var table = $('#pedidos').DataTable();

                        $('#pedidos tbody').on('click', 'tr', function () {
                            var id = table.row(this).data().idPedido;
                            var input = $("<input type='text' name='idPedido'/>");
                            $(input).val(id);
                            $("#formPedido").append(input);
                            $("#formPedido").submit();
                        });
                    }
                });
            });
            function reemplazarImg(img) {
                $(img).attr("src", "../img/productDefaultImage.jpg");
            }
        </script>
    </head>

    <body>
        <form id="formPedido" action="mostrarPedido.php" method="get" hidden>
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
                        <h4 class="text-center">Gestión de Compras</h4>
                        <ul class="list-unstyled">
                            <li><a href="misPedidos.php" class="list-group-item active">Mis Compras</a></li>
                            <li><a href="reclamacionesRealizadas.php" class="list-group-item">Mis Reclamaciones</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9 table-responsive-sm">
                    <table id="pedidos" class="table table-striped table-bordered dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Importe(&euro;)</th>
                                <th>Estado</th>
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

function obtenerMisPedidos($email) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "SELECT * FROM pedidos WHERE email_cliente='$email'";
    $result = mysqli_query($con, $query);
    $pedidos = Array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $total = 0;
            $estado = "Entregado";
            $fecha = $row['fecha'];
            $idPedido = $row['id'];
            $query = "SELECT cantidad, estado, precio FROM lineas_de_pedido, productos WHERE id_producto = id and id_pedido='$idPedido'";
            $result2 = mysqli_query($con, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $subtotal = $row2['cantidad'] * $row2['precio'];
                    $estado2 = $row2['estado'];
                    $total += $subtotal;
                    if ($estado2 == "Procesado" || ($estado == "Entregado" && $estado2 == "Enviado")) {
                        $estado = $estado2;
                    }
                }
                $pedidos[] = Array('idPedido' => $idPedido, 'fecha' => $fecha, 'estado' => $estado, 'precio' => $total);
            }
        }
    }

    closeCon($con);

    return $pedidos;
}
?>