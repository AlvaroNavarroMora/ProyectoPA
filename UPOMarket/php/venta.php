<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "vendedor")) {
    header("location: ./principal.php");
}
/*Esta clase muestra al vendedor los datos de una venta en concreto, desde aquí puede modificar el estado de la misma*/
if (isset($_POST["idVenta"]) && isset($_POST["cliente"]) && isset($_POST["importe"]) && isset($_POST["fecha"])) {
    include './utils/manejadorBD.php';

    $idVenta = filter_var($_POST["idVenta"], FILTER_SANITIZE_NUMBER_INT);
    $cliente = trim(filter_var($_POST["cliente"], FILTER_SANITIZE_EMAIL));
    $importe = filter_var($_POST["importe"], FILTER_SANITIZE_NUMBER_FLOAT);
    $fecha = trim(filter_var($_POST["fecha"], FILTER_SANITIZE_STRING));

    $venta = obtenerVenta($_SESSION["email"], $idVenta);
    if (empty($venta)) {
        header("location:misProductos.php");
    }
    $modificado = false;
    foreach ($venta as $v) {
        if (isset($_POST["estado-" . $v["id"]])) {
            $estado = trim(filter_var($_POST["estado-" . $v["id"]], FILTER_SANITIZE_STRING));
            if (!actualizarEstadoLinea($idVenta, $v["id"], $estado)) {
                $error = "No se pudo actualizar el estado del pedido";
            } else {
                $error = "";
                $modificado = true;
            }
        }
    }
    if ($modificado) {
        $venta = obtenerVenta($_SESSION["email"], $idVenta);
    }
} else {
    header("location:principal.php");
}
//Con este método cambia el estado del pedido realizado
function actualizarEstadoLinea($idVenta, $idProducto, $estado) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "UPDATE lineas_de_pedido SET estado='$estado' WHERE id_pedido=$idVenta and id_producto=$idProducto";
    mysqli_query($con, $query);
    $correcto = mysqli_affected_rows($con) > 0;

    closeCon($con);

    return $correcto;
}
//Esta función recoge la información que queremos mostrar de la venta
function obtenerVenta($email, $idVenta) {
    $con = openCon();
    mysqli_set_charset($con, "utf8");
    $query = "SELECT prod.id as 'id', prod.nombre, prod.precio, lp.cantidad, lp.estado FROM pedidos p, lineas_de_pedido lp, productos prod WHERE prod.email_vendedor='$email' AND lp.id_pedido = p.id AND lp.id_producto = prod.id and p.id=$idVenta";
    $result = mysqli_query($con, $query);
    $lp = Array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lp[] = $row;
        }
    }
    closeCon($con);

    return $lp;
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Venta - UPOMarket</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <link href="../css/venta.css" rel="stylesheet" type="text/css"/>
    <script src="../frameworks/jquery/jquery.min.js"></script>
    <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
    <script>
        $(document).ready(function () {
            $("#estadoPedido").change(function () {
                var valor = $(this).val();
                var lineas = $("select.estado-linea");
                for (var i = 0; i < lineas.length; i++) {
                    $(lineas[i]).val(valor);
                }
            });
            $("#btnActualizarPedido").click(function () {
                $("#actualizarLineasPedido").submit();
            });

        });
    </script>

</head>

<body>
    <?php
    include './header.php';
    include './utils/encriptar.php';
    ?>

    <!-- Page Content -->
    <main class="container">
        <?php
        if ($modificado) {
            echo "<br>";
            echo "<div class='alert alert-success'>El pedido se ha actualizado con éxito</div>";
        } else if (!empty($error)) {
            echo "<br>";
            echo '<div class="alert alert-warning">' . $error . "</div>";
        }
        ?>
        <h3>Datos del pedido</h3>
        <hr>
        <div class="row container">
            <?php
            echo "<div class='col'><div class='row'><strong>ID Venta:</strong> " . $idVenta . "</div>";
            echo "<div class='row'><strong>Email del cliente:</strong> " . $cliente . "</div></div>";
            echo "<div class='col'><div class='row'><strong>Fecha:</strong> " . $fecha . "</div>";
            echo "<div class='row'><strong>Importe:</strong> " . number_format($importe, 2) . "&euro;</div></div>";
            ?>
            <div class="col">
                <div class="form-group form-inline row">
                    <label for='estadoPedido'><strong>Estado del pedido:</strong></label>
                    <select id="estadoPedido" class="custom-select form-control" name="estado-venta">
                        <option value="" disabled selected>--Seleccionar--</option>
                        <option value="Procesado">Procesado</option>
                        <option value="Enviado">Enviado</option>
                        <option value="Entregado">Entregado</option>
                    </select>
                </div>
                <div class="row">
                    <button id="btnActualizarPedido" class="btn btn-md btn-primary btn-block form-control" type="button" value="Actualizar Pedido" name="actualizarPedido">
                        Actualizar Pedido
                    </button>
                </div>
            </div>
        </div>
        <hr>
        <h3>Productos</h3>
        <form id="actualizarLineasPedido" method="post" action="#" style="width:100%">
            <div class="table-responsive-lg">
                <table id="lineas-venta" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class='text-center'>Cantidad</th>
                            <th class='text-center'>Precio(&euro;)</th>
                            <th class='text-center'>Subtotal(&euro;)</th>
                            <th class='text-center'>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($venta as $v) {
                            echo "<tr>";
                            echo "<td><a href='./producto.php?idProducto=" . $v["id"] . "'>" . $v["nombre"] . "</a></td>";
                            echo "<td class='text-center'>" . $v["cantidad"] . "</td>";
                            echo "<td class='text-center'>" . number_format($v["precio"], 2) . "</td>";
                            echo "<td class='text-center'>" . number_format($v["cantidad"] * $v["precio"], 2) . "</td>";
                            echo "<td class='text-center'>";
                            echo "<select id='estado' class='custom-select estado-linea' name='estado-" . $v["id"] . "'>";
                            echo "<option value='Procesado' ";
                            if ($v["estado"] == "Procesado") {
                                echo "selected";
                            }
                            echo ">Procesado</option>";

                            echo "<option value='Enviado' ";
                            if ($v["estado"] == "Enviado") {
                                echo "selected";
                            }
                            echo ">Enviado</option>";

                            echo "<option value='Entregado' ";
                            if ($v["estado"] == "Entregado") {
                                echo "selected";
                            }
                            echo ">Entregado</option>";

                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <input type="text" value="<?php echo $idVenta ?>" name="idVenta" hidden>
            <input type="text" value="<?php echo $cliente ?>" name="cliente" hidden>
            <input type="text" value="<?php echo $importe ?>" name="importe" hidden>
            <input type="text" value="<?php echo $fecha ?>" name="fecha" hidden>
        </form>
    </main>
    <!-- /.container -->
    <?php
    include '../html/footer.html';
    ?>
</body>
</html>