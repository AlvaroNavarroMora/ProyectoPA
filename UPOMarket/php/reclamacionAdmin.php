<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['tipo']) || ($_SESSION['tipo'] != "admin")) {
    header("location: ./principal.php");
}

/*
 * Esta vista muestra la información de una reclamación especifica a un administrador.
 */

if (isset($_POST["idReclamacion"]) && isset($_POST["idProducto"])) {
    include './utils/manejadorBD.php';

    $idReclamacion = filter_var($_POST["idReclamacion"], FILTER_SANITIZE_NUMBER_INT);
    $idProducto = filter_var($_POST["idProducto"], FILTER_SANITIZE_NUMBER_INT);
    $reclamacion = obtenerDatosReclamacion($idReclamacion, $idProducto);
    if (empty($reclamacion)) {
        header("location:principal.php");
    }
} else {
    header("location:principal.php");
}
/*Obtenemos los datos de una reclamación específica*/
function obtenerDatosReclamacion($idReclamacion, $idProducto) {
    $con = openCon();
    $query = "SELECT r.fecha as 'fecha_reclamacion', r.descripcion as 'descripcion',
        r.estado as 'estado_reclamacion', p.fecha as 'fecha_pedido',
        prod.email_vendedor as 'vendedor', p.email_cliente as 'cliente',
        prod.id as 'id', prod.nombre as 'nombre', prod.precio as 'precio',
        lp.cantidad as 'cantidad', lp.estado as 'estado_pedido' FROM pedidos p,
        lineas_de_pedido lp, productos prod, reclamaciones as r 
        WHERE lp.id_pedido = p.id AND lp.id_producto = '$idProducto' AND r.id_pedido='$idReclamacion'
        AND r.id_pedido=p.id AND r.id_producto=lp.id_producto AND lp.id_producto=prod.id";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
            $reclamacion['idLineaDePedido'] = $idReclamacion;
            $reclamacion['idProducto'] = $idProducto;
            $reclamacion['nombreProducto'] = $row['nombre'];
            $reclamacion['cantidad'] = $row['cantidad'];
            $reclamacion['precio'] = $row['precio'];
            $reclamacion['importe'] = $row['precio'] * $row['cantidad'];
            $reclamacion['estadoPedido'] = $row['estado_pedido'];
            $reclamacion['estadoReclamacion'] = $row['estado_reclamacion'];
            $reclamacion['cliente'] = $row['cliente'];
            $reclamacion['vendedor'] = $row['vendedor'];
            $reclamacion['fechaPedido'] = $row['fecha_pedido'];
            $reclamacion['fechaReclamacion'] = $row['fecha_reclamacion'];
            $reclamacion['descripcion'] = $row['descripcion'];
    }
    closeCon($con);

    return $reclamacion;
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Venta - UPOMarket</title>
    <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/shop-homepage.css" rel="stylesheet">
    <link href="../css/header.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
    <script src="../frameworks/jquery/jquery.min.js"></script>
    <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

</head>

<body>
    <?php
    include './header.php';
    include './utils/encriptar.php';
    ?>

    <!-- Page Content -->
    <main class="container mt-4">
        <h2>Datos de la reclamación</h2>
        <hr />
        <form method="post" action="#">
            <h4>Pedido y Reclamación con ID <?php echo $reclamacion['idLineaDePedido'];?></h4>
            <hr />
            <h5>Detalles de la reclamación</h5>
            <strong>Vendedor: </strong><?php echo $reclamacion['vendedor'];?>
            <br />
            <strong>Cliente: </strong><?php echo $reclamacion['cliente'];?>
            <br />
            <strong>Fecha de la reclamación: </strong><?php echo $reclamacion['fechaReclamacion'];?>
            <br />
            <strong>Descripción de la reclamación: </strong><?php echo $reclamacion['descripcion'];?>
            <br />
            <strong>Estado de la reclamación: </strong><?php echo $reclamacion['estadoReclamacion'];?>
            <br />
            <hr />
            <h5>Detalles del pedido</h5>
            <strong>ID del producto: </strong><?php echo $reclamacion['idProducto'];?>
            <br />
            <strong>Nombre del producto: </strong><?php echo $reclamacion['nombreProducto'];?>
            <br />
            <strong>Cantidad: </strong><?php echo $reclamacion['cantidad'];?>
            <br />
            <strong>Precio: </strong><?php echo $reclamacion['precio'];?>€
            <br />
            <strong>Importe total: </strong><?php echo $reclamacion['importe'];?>€
            <br />
            <strong>Fecha de compra: </strong><?php echo $reclamacion['fechaPedido'];?>
            <br />
            <strong>Estado del pedido: </strong><?php echo $reclamacion['estadoPedido'];?>
            <br />
        </form>
    </main>
    <!-- /.container -->
    <?php
    include '../html/footer.html';
    ?>
</body>
</html>