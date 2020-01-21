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
            <script src="../js/carrito.js" type="text/javascript"></script>
        </head>

        <body>
            <?php
            include './header.php';
            ?>
            <!-- Page Content -->
            <main class="container">
                <div class="row">



                    <div class="divCarrito">
                        <h3>Mi carrito</h3>
                        <?php
                        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                            echo "<div class='alert alert-success'>El carrito está vacío.</div>";
                        } else {
                            ?>
                            <form method="post" action="./utils/anadirCarrito.php">
                                <table id="tableProductos" class="table table-light">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Imagen</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal </th>
                                            <th>Eliminar </th>
                                        </tr>
                                        <?php
                                        $i = 0;
                                        foreach ($_SESSION['carrito'] as $indice => $producto) {
                                            $query = "SELECT nombre, descripcion, precio, imagen FROM productos WHERE id='" . $producto['id'] . "'";
                                            $result = ejecutarConsulta($query);
                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_array($result);
                                                if ($row['nombre'] == $producto['nombre']) {
                                                    echo "<td>" . $row['nombre'] . "</td>";
                                                    echo "<td>" . $row['descripcion'] . "</td>";
                                                    echo "<td><img src='" . $row['imagen'] . "' alt='Imagen de producto' /></td>";
                                                    echo "<td>" . $row['precio'] . "</td>";
                                                    echo "<td><input name='cantidad" . $i . "' type='number' id='cantidad" . $i . "' value='" . $producto['cantidad'] . "'/></td>";
                                                    echo "<td id ='subtotal" . $i . "'></td>";
                                                    echo '<input type="hidden" name="idProducto' . $i . '" value="' . encriptar($producto['id']) . '">';
                                                    echo "<td><button  id ='btnEliminarCarrito" . $i . "' name='btnEliminarCarrito' class='btn btn-danger' type='submit' value='" . $i . "' >Eliminar</button></td>";
                                                }
                                            }
                                        }
                                        ?>
                                    </thead>
                                    <tr>
                                        <td colspan="4"><h5>Total:</h5></td>
                                        <td id="precioTotalCarrito" colspan="3"><?php echo number_format(345.293, 2); ?>€</td>
                                    </tr>
                                </table>
                            </form>
                            <?php
                        }
                        ?>

                    </div>

                    <!-- /.row -->


                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="divCarrito">
                        <h4>Procesar Compra:</h4>
                        <p>Dirección</p>
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
} else {
    header('Location: ./principal.php');
    ;
}
?>