<?php
include "./utils/sesionUtils.php";
include './utils/encriptar.php';
include './utils/utilsProductos.php';
session_start();

if (isset($_SESSION['email'])) {
    if (isset($_SESSION['direccion'])) {
        $direccion = isset($_SESSION['direccion']);
        $ids = Array();
        foreach ($_SESSION["carrito"] as $p) {
            $ids[] = $p["id"];
        }
        $productos = obtenerProductosCarrito($ids);
        $array_productos = Array();
        $total = 0;
        foreach ($productos as $key => $p) {
            $id = $p["id"];
            $neededObject = array_filter(
                    $_SESSION["carrito"], function ($e) use ($id) {
                return $e["id"] === $id;
            }
            );
            $encontrado = array_values($neededObject);
            $cantidad = $encontrado[0]["cantidad"];
            $array_productos[] = Array("name" => $p["nombre"], "description" => $p["descripcion"],
                "sku" => "sku" . $p["id"], 'unit_amount' => Array("currency_code" => "EUR", "value" => round($p["precio"], 2)),
                "quantity" => $cantidad);
            $total += round($p["precio"], 2) * $cantidad;
            $productos[$key]["cantidad"] = $cantidad;
        }
    } else {
        header('Location: ./principal.php');
    }
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

            <title>UPOMarket-Procesar Compra</title>
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
                                    $subtotal = 0;
                                    foreach ($productos as $producto) {
                                        echo "<tr>";
                                        echo "<td>" . $producto['nombre'] . "</td>";
                                        echo "<td>" . $producto['descripcion'] . "</td>";
                                        echo "<td>" . $producto['precio'] . "</td>";
                                        echo "<td>" . $producto['cantidad'] . "</td>";
                                        $subtotal = $producto['precio'] * $producto['cantidad'];
                                        echo "<td id ='subtotal" . $i . "'>$subtotal</td>";
                                        echo "</tr>";

                                        $i++;
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
                                    <?php echo $direccion; ?>
                                </div>
                            </div>

                            <script src="https://www.paypal.com/sdk/js?client-id=Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug&currency=EUR"></script>

                            <div id="paypal-button-container"></div>

                            <script>
                                $(document).ready(function () {
                                    paypal.Buttons();
                                });
                            </script>
                            <script>
                                paypal.Buttons({
                                    style: {
                                        size: 'small',
                                        color: 'gold',
                                        shape: 'pill'
                                    },
                                    createOrder: function (data, actions) {
                                        // This function sets up the details of the transaction, including the amount and line item details.
                                        return actions.order.create(<?php echo json_encode(buildRequestBody(round($total, 2), $array_productos)); ?>);
                                    },
                                    onApprove: function (data, actions) {
                                        // This function captures the funds from the transaction.
                                        return actions.order.capture().then(function (details) {
                                            alert('Transaction completed by ' + details.payer.name.given_name);
        <?php
        $parametros = "?" . encriptar("clave") . "=" . encriptar("ProgramacionAvanzada") . "&" . encriptar("email") . "=" .
                encriptar($_SESSION['email'] . "&" . encriptar("direccion") . "=" . encriptar($_SESSION['direccion']));
        $i = 0;
        foreach ($productos as $producto) {
            $parametros .= "&" . encriptar("producto" . $i) . "=" . encriptar($producto['id']) . "&" . encriptar("cantidad" . $i) . "=" . encriptar($producto['cantidad']);
            $i++;
        }
        ?>
                                            // Call your server to save the transaction
                                            window.location = "finalizarCompra.php" + <?php echo $parametros; ?>;
                                        });
                                    }
                                }).render('#paypal-button-container');
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
}

function buildRequestBody($total, $items) {
    return array(
        'intent' => 'CAPTURE',
        'application_context' =>
        array(
            'brand_name' => 'UPOMarket',
            'locale' => 'es-ES',
            'landing_page' => 'BILLING',
            'shipping_preferences' => 'SET_PROVIDED_ADDRESS',
            'user_action' => 'PAY_NOW',
        ),
        'purchase_units' =>
        array(
            0 =>
            array(
                'amount' =>
                array(
                    'currency_code' => 'EUR',
                    'value' => $total,
                    'breakdown' =>
                    array(
                        'item_total' =>
                        array(
                            'currency_code' => 'EUR',
                            'value' => $total,
                        ),
                    /* 'shipping' =>
                      array(
                      'currency_code' => 'EUR',
                      'value' => '20.00',
                      ),
                      'tax_total' =>
                      array(
                      'currency_code' => 'EUR',
                      'value' => '20.00',
                      ), */
                    ),
                ),
                'items' => $items,
                /* array(
                  0 =>
                  array(
                  'name' => 'T-Shirt',
                  'description' => 'Green XL',
                  'sku' => 'sku01',
                  'unit_amount' =>
                  array(
                  'currency_code' => 'EUR',
                  'value' => '500.00',
                  ),
                  'tax' =>
                  array(
                  'currency_code' => 'EUR',
                  'value' => '20.00',
                  ),
                  'quantity' => '1',
                  'category' => 'PHYSICAL_GOODS',
                  ),
                  ), */
                'shipping' =>
                array(
                    'method' => 'Seur',
                    'address' =>
                    array(
                        'address_line_1' => '123 Townsend St',
                        'address_line_2' => 'Floor 6',
                        'admin_area_2' => 'San Francisco',
                        'admin_area_1' => 'CA',
                        'postal_code' => '94107',
                        'country_code' => 'ES',
                    ),
                ),
            ),
        ),
    );
}
?>