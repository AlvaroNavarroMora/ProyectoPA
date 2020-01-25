<?php
include "./utils/sesionUtils.php";
include './utils/encriptar.php';
include './utils/utilsProductos.php';
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_GET[encriptar("clave")])) {
        if (desencriptar($_GET[encriptar("clave")]) == "ProgramacionAvanzada") {
            if (isset($_GET[encriptar("email")]) && isset($_GET[encriptar("direccion")])) {
                $email = desencriptar($_GET[encriptar("email")]);
                if ($email == $_SESSION['email']) {
                    $direccion = desencriptar($_GET[encriptar("direccion")]);
                    $i = 0;
                    while (isset($_GET[encriptar("producto" . $i)]) && isset($_GET[encriptar("cantidad" . $i)])) {
                        $productos[] = Array("id" => desencriptar($_GET[encriptar("producto" . $i)]),
                            "cantidad" => desencriptar($_GET[encriptar("cantidad" . $i)]));
                        $i++;
                    }
                    $query = "INSERT INTO pedidos (email_cliente, id_direccion) VALUES('$email','$direccion')";
                    $link = openCon();
                    $result = mysqli_query($link, $query);
                    $idPedido = mysqli_insert_id($con);
                    foreach ($productos as $producto) {
                        $query = "INSERT INTO lineas_de_pedido (id_pedido, id_producto, cantidad, estado) "
                                . "VALUES('$idPedido', '" . $producto['id'] . "', '" . $producto['cantidad'] . ")";
                        $result = mysqli_query($con, $query);
                    }
                    mysqli_close($link);
                    unset($_SESSION['carrito']);
                    unset($_SESSION['direccion']);
                }
            }
        }
    }
}
if (!isset($idPedido)) {
    header('Location: ./principal.php');
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>UPOMarket-Resumen de compra</title>
            <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
            <link href="../css/shop-homepage.css" rel="stylesheet">
            <link href="../css/header.css" rel="stylesheet">
            <link href="../css/footer.css" rel="stylesheet">
            <link href="../css/principal.css" rel="stylesheet">
            <link href="../css/carrito.css" rel="stylesheet" type="text/css"/>
            <script src="../frameworks/jquery/jquery.min.js"></script>
            <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

        </head>

        <body>

            <?php
            include './header.php';
            ?>
            <main class="container">
                <div class="row">
                    <div class="divCarrito">
                        <h3>Compra finalizada</h3>
                        <table id="tableProductos" class="table table-light">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal </th>
                                </tr>
                            </thead>
                       
                            <?php
                            $i = 0;
                            $subtotal = 0;
                            $link = openCon();
                            $total = 0;
                            foreach ($productos as $producto) {
                                $query = "SELECT nombre, descripcion, precio FROM productos WHERE id='".$producto['id']."'";
                                $result = mysqli_query($link, $query);
                                $row = mysqli_fetch_array($result);
                                echo "<tr>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['descripcion'] . "</td>";
                                echo "<td>" . $row['precio'] . "</td>";
                                echo "<td>" . $producto['cantidad'] . "</td>";
                                $subtotal = $row['precio'] * $producto['cantidad'];
                                $total += $subtotal;
                                echo "<td id ='subtotal" . $i . "'>$subtotal€</td>";
                                echo "</tr>";

                                $i++;
                            }
                            ?>

                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td id="precioTotalCarrito" colspan="2"><?php echo number_format($total, 2); ?>€</td>
                            </tr>
                        </table>

                        <div class="row">
                            <div class="divCarrito">
                                <strong>Dirección de envio:</strong>
                                <br />
                                <?php echo $direccion; ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="divCarrito">
                                <strong>Descargar PDF</strong>
                                <!-- --------Hacer que se pueda descargan un pdf------ -->
                            </div>
                        </div>

                    </div>

                </div>



            </main>
            <!-- /.container -->
            <?php
            include '../html/footer.html';
            ?>

        </body>

    </html>
    <?php
}
?>