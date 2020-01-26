<?php
session_start();

include "./utils/manejadorBD.php";
include "./utils/utilsConflicto.php";
if (isset($_GET['datos'])) {
    $datos = filter_var($_GET['datos'], FILTER_SANITIZE_MAGIC_QUOTES);
    $aux = explode(";", $_GET['datos']);
    $idPedido = $aux[0];
    $idProducto = $aux[1];
    $marca = $aux[2];

    if (existeConflicto($idPedido, $idProducto)) {
        if ($marca == "S") {
            administrarConflicto($idPedido, $idProducto, "Devolucion");
        } elseif ($marca == "N") {
            administrarConflicto($idPedido, $idProducto, "No Resuelto");
        }
    }
    header("Location: ./misReclamaciones.php");
}

if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}

$data = json_encode(obtenerMisReclamaciones($_SESSION["email"]));
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
        <link href="../css/misReclamaciones.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        <script>
            $(document).ready(function () {
                var data = <?php echo $data ?>;
                $('#reclamaciones').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    "columns": [
                        {"data": "id_pedido"},
                        {"data": "id_producto"},
                        {"data": "nombre"},
                        {"data": "email_vendedor"},
                        {"data": "email_cliente"},
                        {"data": "descripcion"},
                        {"data": "estado"},
                        {"data": "fecha"},
                        {"data": "decision"}
                    ],
                    "drawCallback": function () {
                        var table = $('#reclamaciones').DataTable();

                        $("table tr").find('td:eq(1)').each(function () {
                            $(this).css("display", "none");
                        });
                        $("table tr").find('th:eq(1)').css("display", "none");


                        /*$('#reclamaciones tbody').on('click', 'tr', function () {
                         var value = table.row(this).data().id_pedido;
                         var input = $("<input type='text' name='idVenta'/>");
                         $(input).val(value);
                         $("#formReclamaciones").append(input);
                         
                         value = table.row(this).data().email_cliente;
                         input = $("<input type='text' name='cliente'/>");
                         $(input).val(value);
                         $("#formReclamaciones").append(input);
                         
                         value = table.row(this).data().importe;
                         input = $("<input type='text' name='importe'/>");
                         $(input).val(value);
                         $("#formReclamaciones").append(input);
                         
                         value = table.row(this).data().fecha;
                         input = $("<input type='text' name='fecha'/>");
                         $(input).val(value);
                         $("#formReclamaciones").append(input);
                         
                         $("#formReclamaciones").submit();
                         });*/


                        var decisiones = table.column(7).data();
                        var rows = $("tbody tr");

                        for (var i = 0; i < decisiones.length; i++) {

                            var aux = $(rows[i]).children()[8];
                            var idPedido = $($(rows[i]).children()[0]).text();
                            var idProducto = $($(rows[i]).children()[1]).text();
                            var idVendedor = $($(rows[i]).children()[3]).text();
                            var idCliente = $($(rows[i]).children()[4]).text();
                            $(aux).empty();
                            //
                            var txtBtn1 = idPedido + ";" + idProducto + ";S";
                            var txtBtn2 = idPedido + ";" + idProducto + ";N";
                            //
                            var btnDarRazonCliente = document.createElement("button");
                            $(btnDarRazonCliente).text("ACEPTAR");
                            $(btnDarRazonCliente).attr("class", "btn btn-success");
                            $(btnDarRazonCliente).attr("onclick", "administrarReclamacion('" + txtBtn1 + "');");
                            aux.append(btnDarRazonCliente);


                            var btnDarRazonVendedor = document.createElement("button");
                            $(btnDarRazonVendedor).text("RECHAZAR");
                            $(btnDarRazonVendedor).attr("class", "btn btn-danger");
                            $(btnDarRazonVendedor).attr("onclick", "administrarReclamacion('" + txtBtn2 + "');");


                            aux.append(btnDarRazonVendedor);

                        }
                    }
                });
            });
            function administrarReclamacion(datosReclamacion) {
                location.href = "./misReclamaciones.php?datos=" + datosReclamacion;

            }
        </script>
    </head>

    <body>
        <form id="formReclamaciones" action="reclamacion.php" method="post" hidden>
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
                            <li><a href="misProductos.php" class="list-group-item">Mis Productos</a></li>
                            <li><a href="misVentas.php" class="list-group-item">Mis Ventas</a></li>
                            <li><a href="misReclamaciones.php" class="list-group-item active">Mis Reclamaciones</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9">
                    <table id="reclamaciones" class="table table-striped table-bordered dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>id Producto</th>
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

function obtenerMisReclamaciones($email) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");

    $query = "SELECT r.`id_pedido`, r.`id_producto`,p.`nombre`, p.`email_vendedor`, v.`email_cliente`, r.`descripcion`, r.`estado`, r.`fecha`
                 FROM `reclamaciones` as r,`productos` as p, `pedidos` as v 
                 WHERE r.`id_producto`=p.`id` 
                 AND r.`id_pedido`=v.`id` 
                 AND p.`email_vendedor`='$email'
                 and r.`estado`='Pendiente'";

    $result = mysqli_query($con, $query);
    $conflictos = Array();
    $i = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $conflictos[] = $row;
            $conflictos[$i]['decision'] = "";
            $i++;
        }
    }

    closeCon($con);

    return $conflictos;
}
?>