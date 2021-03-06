<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Introducimos HTML de prueba


if (isset($_GET['idPedido'])) {
    $idPedido = filter_var($_GET['idPedido'], FILTER_SANITIZE_NUMBER_INT);

    $html = file_get_contents_curl("../php/pdfPedido.php?idPedido=$idPedido");



// Instanciamos un objeto de la clase DOMPDF.
    $pdf = new DOMPDF();

// Definimos el tamaño y orientaci&oacute;n del papel que queremos.
    $pdf->set_paper("letter", "portrait");
//$pdf->set_paper(array(0,0,104,250));
// Cargamos el contenido HTML.
    ob_start();
    ?>



    <?php
    include "../php/utils/sesionUtils.php";
    include '../php/utils/encriptar.php';
    include '../php/utils/utilsProductos.php';
    session_start();
    if (isset($_SESSION['email'])) {
        
            
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

    if (isset($errores)) {
        echo "<html><body><h1>Error</h1></body></html>";
    } else {
        ?>

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


                <main class="container">
                    <h1 class='text-center'>UPOMarket</h1>
                    <hr />
                    <div class="row">
                        <div class="divCarrito">
                            <h5 class="und">Comprador</h5>
                            <br />
                            <?php echo $_SESSION['nombre']; ?>
                            <br />
                            <?php echo $_SESSION['email']; ?>
                        </div>
                    </div>

                    <hr />
                    <div class="row">
                        <div class="divCarrito">
                            <h3>Resumen del pedido</h3>
                            <table id="tableProductos" class="table table-light">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripci&oacute;n</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal </th>
                                        <th>Estado </th>
                                    </tr>
                                </thead>

                                <?php
                                $i = 0;
                                $subtotal = 0;
                                $total = 0;
                                foreach ($productos as $producto) {
                                    echo "<tr>";
                                    echo "<td>" . $producto['nombre'] . "</td>";
                                    echo "<td>" . $producto['descripcion'] . "</td>";
                                    echo "<td>" . $producto['precio'] . "</td>";
                                    echo "<td>" . $producto['cantidad'] . "</td>";
                                    $subtotal = $producto['precio'] * $producto['cantidad'];
                                    $total += $subtotal;
                                    echo "<td id ='subtotal" . $i . "'>$subtotal &euro;</td>";
                                    echo "<td>" . $producto['estado'] . "</td>";
                                    echo "</tr>";

                                    $i++;
                                }
                                if ($i == 1) {
                                    echo "</table>";
                                    echo "Total: $total";
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3"><strong>Total:</strong></td>
                                        <td id="precioTotalCarrito" colspan="3"><?php echo number_format($total, 2); ?>&euro;</td>
                                    </tr>
                                </table>
                                <?php
                            }
                            ?>
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
                                    <h5 class="und">Direcci&oacute;n de envio</h5>
                                    <br />
                                    <?php
                                    echo "<strong>Direcci&oacute;n:</strong> " . $direccion["linea_1"];
                                    if (!empty($direccion["linea_2"])) {
                                        echo " - " . $direccion["linea_2"];
                                    } else {
                                        $direccion["linea_2"] = "";
                                    }
                                    echo "<br>";
                                    echo "<strong>Provincia:</strong> " . $direccion["provincia"] . "<br>";
                                    echo "<strong>Ciudad:</strong> " . $direccion["ciudad"] . "<br>";
                                    echo "<strong>C&oacute;digo Postal:</strong> " . $direccion["cp"];
                                    ?>
                                </div>
                            </div>



                        </div>

                    </div>


            </body>

        </html>
        <?php
    }
    ?>


    <?php
    $pdf->load_html(ob_get_clean());

// Renderizamos el documento PDF.
    $pdf->render();

// Enviamos el fichero PDF al navegador.
    $pdf->stream('reportePdf.pdf');
}

function file_get_contents_curl($url) {
    $crl = curl_init();
    $timeout = 5;
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}
