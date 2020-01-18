<?php
session_start();
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
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav class="list-group">
                        <ul class="list-unstyled">
                            <li><a href="#" class="list-group-item">Category 1</a></li>
                            <li><a href="#" class="list-group-item">Category 2</a></li>
                            <li><a href="#" class="list-group-item">Category 3</a></li>
                        </ul>
                    </nav>

                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-3">
                    <div class="row">
                        <div class="divCarrito">
                            <h3>Mi carrito</h3>
                            <table id="productos" class="table table-striped table-bordered dataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="col-lg-3">
                    <div class="row">
                        <div class="divCarrito">
                            <h4>Precio Total:</h4>
                            <p>50€</p>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-3">
                    <div class="row">
                        <div class="divCarrito">
                            <h4>Procesar Compra:</h4>
                            <p>Dirección</p>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-9 -->

            </div>
            <!-- /.row -->

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>
