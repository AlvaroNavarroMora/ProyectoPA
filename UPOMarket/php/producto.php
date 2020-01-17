<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nombre Producto - Upomarket</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <link href="../css/producto.css" rel="stylesheet" type="text/css"/>
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
            <!-- LISTA DE CATEGORÍAS -->
            <div class="col-lg-3">
                <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                <div class="list-group">
                    <a href="#" class="list-group-item active">Category 1</a>
                    <a href="#" class="list-group-item">Category 2</a>
                    <a href="#" class="list-group-item">Category 3</a>
                </div>
            </div>
            <!-- /.col-lg-3 -->
            <div class="col-lg-9">
                <div class="card mt-4">
                    <img class="card-img-top img-fluid" src="https://www.gugcstudentguild.com.au/wp-content/uploads/2014/05/PROGRAMS-WEB-BYRON_BAY_SURF-900x400px.jpg" alt="">
                    <div class="card-body">
                        <h3 class="card-title">Bono Surf</h3>
                        <h4>40.99€</h4>
                        <p class="card-text">Bono de 2 clases para aprender a surfear. Bono de 2 clases para aprender a surfear. Bono de 2 clases para aprender a surfear. Bono de 2 clases para aprender a surfear. Bono de 2 clases para aprender a surfear.</p>
                        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                        4.0 estrellas
                    </div>
                </div>
                <!-- /.card caracteristicas -->
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        Características
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">40 horas de clase</li>
                            <li class="list-group-item">Profesor experto</li>
                            <li class="list-group-item">Certificado gratuito al final del curso</li>
                            <li class="list-group-item">Evaluaciones y pruebas físicas incluidas</li>
                        </ul>
                    </div>
                </div>
                <!-- fin /.card caracteristicas-->
                <!-- /.card Opiniones-->
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        Opiniones del producto
                    </div>
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Dicho por: Anonimo en 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Dicho por: Anonimo en 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Dicho por: Anonimo en 3/1/17</small>
                        <hr>
                        <a id="btn-coment" href="#" class="btn btn-success">Deja un comentario!</a>
                    </div>
                </div>
                <!-- fin /.card Opiniones-->
            </div>
            <!-- /.col-lg-9 -->
        </div>
    </main>
    <!-- /.container -->
    <?php
    include '../html/footer.html';
    ?>
</body>

</html>