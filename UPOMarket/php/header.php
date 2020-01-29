<?php

function mostrarSinSesion() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="./login.php">Iniciar Sesión</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./signUp.php">Registrarse</a>
    </li>
    <?php
}

function mostrarCliente() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="./misPedidos.php">Compras</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./carrito.php"><i class="fa fa-shopping-cart"></i> Cesta <span id="num-productos"></span></a>
    </li>
    <li id="profile" class="nav-item">
        <a class="nav-link" href="./perfil.php"><i class="fas fa-user"></i> <?php
            echo $_SESSION['nombre'];
            ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./utils/cerrarSesion.php">Cerrar Sesión</a>
    </li>
    <?php
}

function mostrarVendedor() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="./misPedidos.php">Compras</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./misProductos.php">Ventas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./carrito.php"><i class="fa fa-shopping-cart"></i> Cesta <span id="num-productos"></span></a>
    </li>
    <li id="profile" class="nav-item">
        <a class="nav-link" href="./perfil.php"><i class="fas fa-user"></i> <?php
            echo $_SESSION['nombre'];
            ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./utils/cerrarSesion.php">Cerrar Sesión</a>
    </li>
    <?php
}

function mostrarAdmin() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="./conflictos.php">Conflictos</a>
    </li>
    <li id="profile" class="nav-item">
        <a class="nav-link" href="./perfil.php"><i class="fas fa-user"></i> <?php
            echo $_SESSION['nombre'];
            ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./utils/cerrarSesion.php">Cerrar Sesión</a>
    </li>
    <?php
}
?>
<script>
    $(document).ready(function () {
        var num_productos = <?php
if (isset($_SESSION["carrito"])) {
    echo count($_SESSION["carrito"]);
} else {
    echo 0;
}
?>;
        $("#num-productos").text("(" + num_productos + ")");
    });
</script>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../php/principal.php"><img height="40" src="../img/upomarket_nav.png" alt="upomarket_nav"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span id="tooglerMovil" class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <?php
                if (!isset($_SESSION['email'])) {
                    mostrarSinSesion();
                } else {
                    if ($_SESSION['tipo'] == 'admin') {
                        mostrarAdmin();
                    } elseif ($_SESSION['tipo'] == 'vendedor') {
                        mostrarVendedor();
                    } else {
                        mostrarCliente();
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
