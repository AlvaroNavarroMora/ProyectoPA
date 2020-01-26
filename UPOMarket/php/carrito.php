<?php
include "./utils/sesionUtils.php";
include "./utils/utilsProductos.php";
include './utils/encriptar.php';
session_start();
if (isset($_SESSION['email'])) {
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "stock_no_disponible") {
            $error = "Uno más productos de su cesta no tienen stock disponible para la cantidad deseada.";
        } else {
            $error = "Algo ha ido mal, revise su carrito.";
        }
    }
    if (isset($_SESSION["carrito"])) {
        $ids = Array();
        foreach ($_SESSION["carrito"] as $p) {
            $ids[] = $p["id"];
        }
    }
    if (!empty($ids)) {
        $direcciones = listarMisDirecciones($_SESSION["email"]);
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
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>MiCesta - UPOMarket</title>
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
            <script src="../js/carrito.js" type="text/javascript"></script>
            <script>
                $(document).ready(function () {
                    $("#inputDireccion").change(function () {
                        var error = $("#error-direccion");
                        if (error != null) {
                            $(error).remove();
                        }
                    });
                    $("input.cantidad").change(function () {
                        var cantidad = parseInt($(this).val());
                        if(isNaN(cantidad) || cantidad <= 0) {
                            cantidad = 1;
                            $(this).val(cantidad);
                        }
                        var id = $(this).attr("id");
                        var index = parseInt(id.split("-")[1]);
                        var rows = $("tr.producto");
                        var modificada = $(rows)[index];
                        var cells = $(modificada).children();
                        var precio = parseFloat($(cells[2]).text());
                        $(cells[4]).text(precio * cantidad);
                        var total = 0;
                        for (var i = 0; i < rows.length; i++) {
                            var cell = $(rows[i]).children()[4];
                            total += parseFloat($(cell).text());
                        }
                        $("#precioTotalCarrito").text(total.toFixed(2));

                    });
                });
                function validaDireccion() {
                    var direccion = $("#inputDireccion");
                    var val = $("#inputDireccion option:selected").val();
                    if (val == "") {
                        var a = document.getElementById("error-direccion");
                        if (a == null) {
                            direccion.parent().after($("<div id='error-direccion' class='alert alert-danger' role='alert'>Debe seleccionar una dirección para continuar con la compra</div>"));
                        }
                        return false;
                    } else {
                        $("#formCarrito").submit();
                    }
                }
            </script>
        </head>

        <body>
            <script src="https://www.paypal.com/sdk/js?client-id=Aag_BV9saCzCn3jZU7nRT-_qMd-sJuXnc9VKSeM5li-IXLAGDi2zUsiRtPpTu3Tvr46fIq9Ce6KSjkug"></script>

            <?php
            include './header.php';
            ?>
            <!-- Page Content -->
            <main class="container">
                <form id="formCarrito" method="post" action="./utils/anadirEliminarCarrito.php">
                    <div class="divCarrito">
                        <?php
                        if (!empty($error)) {
                            echo '<div class="alert alert-warning">' . $error . "</div>";
                        }
                        ?>
                        <h3>Mi carrito</h3>
                        <hr>
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
                                        <th class='text-center'>Precio(&euro;)</th>
                                        <th class='text-center'>Cantidad</th>
                                        <th class='text-center'>Subtotal(&euro;)</th>
                                        <th class='text-center'>Eliminar </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal = 0;
                                    foreach ($productos as $index => $producto) {
                                        echo "<tr class='producto'>";
                                        echo "<td>" . $producto['nombre'] . "</td>";
                                        echo "<td>" . $producto['descripcion'] . "</td>";
                                        echo "<td class='text-center'>" . $producto['precio'] . "</td>";
                                        echo "<td class='text-center'><input name='cantidad" . $index . "' type='number' id='cantidad-" . $index . "' value='" . $producto['cantidad'] . "' class='form-control cantidad' min='1'/></td>";
                                        $subtotal = $producto['precio'] * $producto['cantidad'];
                                        echo "<td id ='subtotal" . $index . "' class='text-center'>$subtotal</td>";
                                        echo '<input type="hidden" name="idProducto' . $index . '" value="' . encriptar($producto['id']) . '">';
                                        echo "<td class='text-center'><button  id ='btnEliminarCarrito" . $index . "' name='btnEliminarCarrito' class='btn btn-sm btn-danger' type='submit' value='" . $index . "' >Eliminar</button></td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="5"><strong>Total:</strong></td>
                                        <td class="text-center"><span id="precioTotalCarrito"><?php echo number_format($total, 2); ?></span>€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <div class="divCarrito">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for='inputDireccion'><strong>Dirección de envio:</strong></label>
                                </div>
                                <select name="direccion" id="inputDireccion" class="custom-select">
                                    <option value="" disabled selected>--Seleccionar--</option>
                                    <?php
                                    foreach ($direcciones as $d) {
                                        echo "<option value='" . $d["id"] . "'>" . $d["nombre"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <a class="btn btn-sm btn-secondary" href="./aniadirDireccion.php" role="button">Añadir una dirección nueva</a>
                        </div>
                        <hr>

                        <div class="divCarrito">
                            <input id="btnProcesarCompra" class="btn btn-md btn-primary btn-block text-uppercase form-control" type="submit" onclick="return validaDireccion()" value="Procesar Compra" name="procesarCompra">
                        </div>
                        <?php
                    }
                    ?>
                </form>
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
?>