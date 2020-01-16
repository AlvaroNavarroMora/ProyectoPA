<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Perfil</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <link href="../css/perfil.css" rel="stylesheet" type="text/css"/>
    <script src="../frameworks/jquery/jquery.min.js"></script>
    <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

</head>

<body>
    <?php
    include '../html/header.html';
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
                <div id="contenedorPerfil">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h3 class="card-title">Perfil
                                <button id="profileButton"><i class="fa fa-edit"></i></button></h3>
                            <h4>Imagen</h4>
                            <img src="../img/defaultProfile.png" alt="Imagen de perfil" id="imgPerfil"/>
                            <h4>Nombre</h4>
                            <p>Nombre de Ejempo</p>
                            <h4>Email</h4>
                            <p>ejemplo@prueba.com</p>
                            <h4>Contraseña</h4>
                            <p>***********</p>
                        </div>
                    </div>
                </div>
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