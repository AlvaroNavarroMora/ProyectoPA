<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo'])) {
    header("location: ./principal.php");
}

if (isset($_POST["idVenta"]) && isset($_POST["vendedor"]) && isset($_POST["importe"]) && isset($_POST["fecha"])) {
    $vendedor = filter_var($_POST["vendedor"], FILTER_SANITIZE_EMAIL);
    $importe = filter_var($_POST["importe"], FILTER_SANITIZE_NUMBER_FLOAT);
    $fecha = filter_var($_POST["fecha"], FILTER_SANITIZE_MAGIC_QUOTES);
    include './utils/manejadorBD.php';

    $idVenta = filter_var($_POST["idVenta"], FILTER_SANITIZE_NUMBER_INT);
    $venta = obtenerVenta($_SESSION["email"], $idVenta);
    if (empty($venta)) {
        header("location:principal.php");
    }
} else {
    header("location:principal.php");
}

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
    <script src="../frameworks/jquery/jquery.min.js"></script>
    <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

</head>

<body>
    <?php
    include './header.php';
    include './utils/encriptar.php';
    ?>

    <!-- Page Content -->
    <main class="container">
        <h3>Datos del pedido</h3>
        <hr>
        <div class="row">
            <?php
            echo "<div class='col'><p><strong>ID Venta:</strong> " . $idVenta . "</p>";
            echo "<p><strong>Email del vendedor:</strong> " . $vendedor . "</p></div>";
            echo "<div class='col'><p><strong>Fecha:</strong> " . $fecha . "</p>";
            echo "<p><strong>Importe:</strong> " . number_format($importe, 2) . "&euro;</p></div>";
            ?>
        </div>
        <hr>
        <h3>Productos</h3>
        <form id="actualizarLineasPedido" method="post" action="#" style="width:100%">
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
                        echo "<td class='text-center'>" . $v['estado'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </main>
    <!-- /.container -->
    <?php
    include '../html/footer.html';
    ?>
</body>
</html>