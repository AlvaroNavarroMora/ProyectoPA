<link href="../css/barraBusqueda.css" rel="stylesheet" type="text/css"/>
<script>
    $(document).ready(function () {
        $("#ordenarResultados select").change(function () {
            $("#ordenarResultados").submit();
        });
    });
</script>
<!-- 
                        Search form
            Cuando el usuario envía el formulario con los términos de búsqueda
            los recibimos por GET en esta página, y mostramos los resultados con PHP
-->
<div class="container-fluid">
    <div class="row mx-auto">
        <div class="col-xl-9 my-auto mx-auto">
            <form id='searchForm' class="form-inline md-form mr-auto mb-4" action='buscaProductos.php' method="GET">
                <select class="form-control" name="categoria">
                    <option value="">Todas las categorías</option>
                    <?php
                    foreach ($categorias as $c) {
                        echo "<option value='$c[0]'>$c[0]</option>";
                    }
                    ?>
                </select>
                <div class="input-group">
                    <input id='searchBar' type="text" class="form-control" placeholder="Buscar productos" name='busqueda'>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xl-3 my-auto mx-auto ordenacion">
            <form id="ordenarResultados" class="form-inline mr-auto mb-1" method="GET">
                <?php
                if (isset($_GET["categoria"])) {
                    echo "<input type='text' value='" . $_GET["categoria"] . "' name='categoria' hidden>";
                }
                ?>
                <select class="form-control" name="ordenar">
                    <?php
                    echo '<option value="-1" ';
                    if (!isset($_GET["ordenar"]) || $_GET["ordenar"] == -1) {
                        echo "selected";
                    }
                    echo '>Ordenar (por defecto)</option>
                    <option value="0" ';
                    if(isset($_GET["ordenar"]) && $_GET["ordenar"] == 0) {
                        echo "selected";
                    }
                    echo '>Mejor Valorados</option>
                    <option value="1" ';
                    if(isset($_GET["ordenar"]) && $_GET["ordenar"] == 1) {
                        echo "selected";
                    }
                    echo '>Más Vendidos</option>
                    <option value="2" ';
                    if(isset($_GET["ordenar"]) && $_GET["ordenar"] == 2) {
                        echo "selected";
                    }
                    echo '>Novedades</option>
                    <option value="3" ';
                    if(isset($_GET["ordenar"]) && $_GET["ordenar"] == 3) {
                        echo "selected";
                    }
                    echo '>Precio: ascendente</option>
                    <option value="4" ';
                    if(isset($_GET["ordenar"]) && $_GET["ordenar"] == 4) {
                        echo "selected";
                    }
                    echo '>Precio: descentente</option>
                        ';
                    ?>
                </select>
            </form>
        </div>
    </div>
</div>