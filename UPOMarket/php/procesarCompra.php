<?php
include "./utils/sesionUtils.php";
include './utils/encriptar.php';
include './utils/utilsProductos.php';
session_start();

/*

Esta página se encarga de realizar la compra y generar un nuevo pedido

 */

if (isset($_SESSION['email'])) {
    if (isset($_SESSION["carrito"]) && isset($_SESSION['direccion'])) {
        $direccion = obtenerDireccion(filter_var($_SESSION['direccion'], FILTER_SANITIZE_NUMBER_INT));
        $ids = Array();
        foreach ($_SESSION["carrito"] as $p) {
            $ids[] = $p["id"];
        }
        $productos = obtenerProductosCarrito($ids);
        if (validaStock($productos)) {
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
            header('Location: ./carrito.php?error=stock_no_disponible');
        }
    } else {
        header('Location: ./carrito.php');
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

            <title>UPOMarket - Procesar Compra</title>
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
                <div class="divCarrito">
                    <h3>Resumen de compra</h3>
                    <hr>
                    <?php
                    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                        echo "<div class='alert alert-success'>El carrito está vacío.</div>";
                    } else {
                        ?>
                        <div class="table-responsive-sm">
                            <table id="tableProductos" class="table table-light">
                                <form method="post" action="finalizarCompra.php" id="finalizarCompra">
                                    <input type="hidden" name="email" value="<?php echo base64_encode(encriptar($_SESSION['email'])); ?>"/>
                                    <input type="hidden" name="direccion" value="<?php echo base64_encode(encriptar($_SESSION['direccion'])); ?>"/>
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th class='text-center'>Precio(&euro;)</th>
                                            <th class='text-center'>Cantidad</th>
                                            <th class='text-center'>Subtotal(&euro;)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        foreach ($productos as $i => $producto) {
                                            $descripcion = $producto["descripcion"];
                                            if (strlen($descripcion) > 50) {
                                                $descripcion = substr($descripcion, 0, 50);
                                                $descripcion .= "...";
                                            }
                                            echo "<tr>";
                                            echo "<td>" . $producto['nombre'] . "</td>";
                                            echo "<td>" . $descripcion . "</td>";
                                            echo "<td class='text-center'>" . number_format($producto['precio'], 2) . "</td>";
                                            echo "<td class='text-center'>" . $producto['cantidad'] . "</td>";
                                            $subtotal = number_format($producto['precio'] * $producto['cantidad'], 2);
                                            echo "<td id ='subtotal" . $i . "' class='text-center'>$subtotal</td>";
                                            echo "</tr>";
                                            ?>
                                        <input type="hidden" name="producto<?php echo $i; ?>" value="<?php echo base64_encode(encriptar($producto['id'])); ?>"/>
                                        <input type="hidden" name="cantidad<?php echo $i; ?>" value="<?php echo base64_encode(encriptar($producto['cantidad'])); ?>"/>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4"><strong>Total:</strong></td>
                                        <td id="precioTotalCarrito" class='text-center'><?php echo number_format($total, 2); ?>&euro;</td>
                                    </tr>
                                    </tbody>
                                    <button type="submit" name="submitButton" value="finalizarCompra" id="botonFinalizar" hidden></button>
                                </form>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="divCarrito">
                        <h5 class="und">Dirección de envío</h5>
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

                    <script src="https://www.paypal.com/sdk/js?client-id=Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug&currency=EUR"></script>
                    <hr>
                    <div id="paypal-button-container"></div>

                    <script>
                        $(document).ready(function () {
                            paypal.Buttons();
                        });
                    </script>
                    <script>
                        /*
                         * 
                         *Aquí cargamos los datos necesarios para interactuar con la API de paypal
                         */
                        paypal.Buttons({
                            style: {
                                size: 'small',
                                color: 'gold',
                                shape: 'pill'
                            },
                            createOrder: function (data, actions) {
                                

                                return actions.order.create(<?php echo json_encode(buildRequestBody(round($total, 2), $array_productos, $direccion)); ?>);
                            },
                            onApprove: function (data, actions) {
                             
                                return actions.order.capture().then(function (details) {
                               
                                    var formCompra = document.getElementById("finalizarCompra");
                                    formCompra.submit();
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

function validaStock($productos) {
    $correcto = true;
    foreach ($productos as $p) {
        $id = $p["id"];
        $neededObject = array_filter(
                $_SESSION["carrito"], function ($e) use ($id) {
            return $e["id"] === $id;
        }
        );
        $encontrado = array_values($neededObject);
        $cantidad = $encontrado[0]["cantidad"];
        if ($cantidad > $p["stock"] || $cantidad < 1) {
            $correcto = false;
        }
    }
    return $correcto;
}

function buildRequestBody($total, $items, $direccion) {
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
                        'address_line_1' => $direccion["linea_1"],
                        'address_line_2' => $direccion["linea_2"],
                        'admin_area_2' => $direccion["ciudad"],
                        'admin_area_1' => $direccion["provincia"],
                        'postal_code' => $direccion["cp"],
                        'country_code' => 'ES',
                    ),
                ),
            ),
        ),
    );
}
?>