<?php
include "./utils/sesionUtils.php";
include './utils/encriptar.php';
include './utils/utilsProductos.php';
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_GET['idPedido'])) {
        $idPedido = filter_var($_GET['idPedido'], FILTER_SANITIZE_NUMBER_INT);
        $query = "SELECT * FROM pedidos WHERE id='$idPedido'";
        $link = openCon();
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['email_cliente'] == $_SESSION['email']) {
                $direccion = obtenerDireccion($row['id_direccion']);
                $fecha = $row['fecha'];
                $query = "SELECT * FROM lineas_de_pedido WHERE id_pedido='$idPedido'";
                $result = mysqli_query($link, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $productoId = $row['id_producto'];
                        $cantidad = $row['cantidad'];
                        $estado = $row['estado'];
                        $producto2 = obtenerProducto($productoId);
                        $producto2['cantidad'] = $cantidad;
                        $producto2['estado'] = $estado;
                        $productos[] = $producto2;
                    }
                } else {
                    $errores[] = "El pedido está vacío";
                }
            } else {
                $errores[] = "El pedido no le pertenece";
            }
        } else {
            $errores[] = "Pedido no existente";
        }
        closeCon($link);
    }
}

if (isset($errores)) {
    header('Location: ../principal.php');
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>UPOMarket-Resumen de pedido</title>
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
                        <h3>Resumen del pedido</h3>
                        <table id="tableProductos" class="table table-light">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Precio</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Subtotal </th>
                                    <th class="text-center">Estado </th>
                                </tr>
                            </thead>

    <?php
    $subtotal = 0;
    $total = 0;
    foreach ($productos as $i=>$producto) {
        echo "<tr>";
        echo "<td>" . $producto['nombre'] . "</td>";
        echo "<td>" . $producto['descripcion'] . "</td>";
        echo "<td class='text-center'>" . $producto['precio'] . "</td>";
        echo "<td class='text-center'>" . $producto['cantidad'] . "</td>";
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $total += $subtotal;
        echo "<td id ='subtotal" . $i . "' class='text-center'>$subtotal €</td>";
        echo "<td class='text-center'>" . $producto['estado'] . "</td>";
        echo "<td class='text-center'><a href='producto.php?idProducto=".$producto["id"]."'>Valora este producto</a></td>";
        echo "</tr>";
    }
    ?>

                            <tr>
                                <td colspan="4"><strong>Total:</strong></td>
                                <td class='text-center' id="precioTotalCarrito"><?php echo number_format($total, 2); ?>€</td>
                            </tr>
                        </table>
                        <hr />
                        <div class="row">
                            <div class="divCarrito">
                                <h5 class="und">Fecha del pedido</h5>
                                <br />
    <?php echo $fecha; ?>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="divCarrito">
                                <h5 class="und">Dirección de envio</h5>
                                <br />
    <?php
    echo "<strong>Dirección:</strong> " . $direccion["linea_1"];
    if (!empty($direccion["linea_2"])) {
        echo " - " . $direccion["linea_2"];
    } else {
        $direccion["linea_2"] = "";
    }
    echo "<br>";
    echo "<strong>Provincia:</strong> " . $direccion["provincia"] . "<br>";
    echo "<strong>Ciudad:</strong> " . $direccion["ciudad"] . "<br>";
    echo "<strong>Código Postal:</strong> " . $direccion["cp"];
    ?>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="divCarrito">
                                <strong><a href='../pdf/crearPdf.php?idPedido=<?php echo $idPedido;?>'>Descargar PDF</a></strong>
                                <!-- --------Hacer que se pueda descargan un pdf------ -->
                            </div>
                        </div>

                    </div>

                </div>

                <form method="post" action="aniadirReclamacion.php">
                    <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>"/>
                    <button type='submit' name='submitReclamacion' value='submitReclamacion' class='btn btn-danger'>Poner una reclamación</button>
                </form>

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
