<?php
session_start();

include "./utils/manejadorBD.php";
include "./utils/utilsConflicto.php";


if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "admin")) {
    header("location: ./principal.php");
}
//Parseamos los datos a JSON para que los entienda el dataTable
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
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
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
                    columnDefs: [{
                            targets: [2, 5],
                            render: function (data, type, row) {
                                return data.length > 20 ?
                                        data.substr(0, 20) + '…' :
                                        data;
                            }
                        }],
                    "columns": [
                        {"data": "id_pedido"},
                        {"data": "id_producto"},
                        {"data": "nombre"},
                        {"data": "email_vendedor"},
                        {"data": "email_cliente"},
                        {"data": "descripcion"},
                        {"data": "estado"},
                        {"data": "fecha"},
                    ],
                    "drawCallback": function () {
                        var table = $('#reclamaciones').DataTable();

                        $("table tr").find('td:eq(1)').each(function () {
                            $(this).css("display", "none");
                        });
                        $("table tr").find('th:eq(1)').css("display", "none");


                        $('#reclamaciones tbody').on('click', 'tr', function () {
                            var value = table.row(this).data().id_pedido;
                            var input = $("<input type='text' name='idReclamacion'/>");
                            $(input).val(value);
                            $("#formReclamaciones").append(input);
                            var value = table.row(this).data().id_producto;
                            var input = $("<input type='text' name='idProducto'/>");
                            $(input).val(value);
                            $("#formReclamaciones").append(input);
                            $("#formReclamaciones").submit();
                        });


                        var decisiones = table.column(7).data();
                        var rows = $("tbody tr");

                        for (var i = 0; i < decisiones.length; i++) {

                            var aux = $(rows[i]).children()[9];
                            var idPedido = $($(rows[i]).children()[0]).text();
                            var idProducto = $($(rows[i]).children()[1]).text();
                            //var idCliente = $($(rows[i]).children()[5]).text();
                            $(aux).empty();


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
        <main class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav class="list-group">
                        <h4 class="text-center">Gestión de Reclamaciones</h4>
                        <ul class="list-unstyled">
                            <li><a href="vistaAdministrador.php" class="list-group-item">Reclamaciones abiertas</a></li>
                            <li><a href="reclamacionesResueltasAdmin.php" class="list-group-item active">Reclamaciones resueltas</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->
                <div class="col-lg-9 table-responsive-xl">
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

//Recogemos los datos que se visualizarán en el dataTable
function obtenerMisReclamaciones() {
    $con = openCon();

    $query = "SELECT r.`id_pedido`, r.`id_producto`,p.`nombre`, p.`email_vendedor`, v.`email_cliente`, r.`descripcion`, r.`estado`, r.`fecha`
                 FROM `reclamaciones` as r,`productos` as p, `pedidos` as v , `lineas_de_pedido` as lp
                 WHERE r.`id_producto`=p.`id` 
                 AND r.`id_pedido`=v.`id` 
                 AND (r.`estado`='Declinada' OR r.`estado`='Devolucion')
                 AND lp.`id_pedido` = v.`id` AND lp.`id_producto` = p.`id`";

    $result = mysqli_query($con, $query);
    $conflictos = Array();
    $i = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $conflictos[] = $row;
            $i++;
        }
    }

    closeCon($con);

    return $conflictos;
}
?>