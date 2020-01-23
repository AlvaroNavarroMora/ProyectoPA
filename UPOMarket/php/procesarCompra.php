<?php
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";
include './utils/encriptar.php';
session_start();
if (isset($_SESSION['email'])) {
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />

            <title>UPOMarket-Inicio</title>
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
            <!-- Page Content -->
            <main class="container">
                <div class="row">


                    <div class="divCarrito">
                        <h3>Resumen de compra</h3>
                        <?php
                        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                            echo "<div class='alert alert-success'>El carrito está vacío.</div>";
                        } else {
                            ?>

                            <table id="tableProductos" class="table table-light">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal </th>
                                    </tr>
                                    <?php
                                    $i = 0;
                                    $total = 0;
                                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                                        $query = "SELECT nombre, descripcion, precio FROM productos WHERE id='" . $producto['id'] . "'";
                                        $result = ejecutarConsulta($query);
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_array($result);
                                            if ($row['nombre'] == $producto['nombre']) {
                                                echo "<tr>";
                                                echo "<td>" . $row['nombre'] . "</td>";
                                                echo "<td>" . $row['descripcion'] . "</td>";
                                                echo "<td>" . $row['precio'] . "</td>";
                                                echo "<td>" . $producto['cantidad'] . "</td>";
                                                $subtotal = $row['precio'] * $producto['cantidad'];
                                                $total += $subtotal;
                                                echo "<td id ='subtotal" . $i . "'>$subtotal</td>";
                                                echo "</tr>";
                                            }
                                            $i++;
                                        }
                                    }
                                    ?>
                                </thead>
                                <tr>
                                    <td colspan="3"><strong>Total:</strong></td>
                                    <td id="precioTotalCarrito" colspan="2"><?php echo number_format($total, 2); ?>€</td>
                                </tr>
                            </table>


                            <div class="row">
                                <div class="divCarrito">
                                    <strong>Dirección de envio:</strong>
                                    <br />
                                    Dirección de prueba
                                </div>
                            </div>


                            <script src="https://www.paypal.com/sdk/js?client-id=Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug"></script>

                            <div id="paypal-button-container"></div>

                            <script>paypal.Buttons().render('paypal-button-container');</script>

                            <script>
                                paypal.Buttons({
                                    createOrder: function (data, actions) {
                                        // This function sets up the details of the transaction, including the amount and line item details.
                                        return actions.order.create({
                                            purchase_units: [{
                                                    amount: {
                                                        value: '0.01'
                                                    }
                                                }]
                                        });
                                    },
                                    onApprove: function (data, actions) {
                                        // This function captures the funds from the transaction.
                                        return actions.order.capture().then(function (details) {
                                            // This function shows a transaction success message to your buyer.
                                            alert('Transaction completed by ' + details.payer.name.given_name);
                                        });
                                    }
                                }).render('#paypal-button-container');
                                //This function displays Smart Payment Buttons on your web page.
                            </script>

                            <?php
                        }
                        ?>

                    </div>



                </div>
                <!-- /.row -->

            </main>
            <!-- /.container -->
            <?php
            include '../html/footer.html';
            ?>
        </body>

    </html>

    <?php
} else {
    header('Location: ./principal.php');
    ;
}
?>