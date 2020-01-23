<link href="../css/barraBusqueda.css" rel="stylesheet" type="text/css"/>
<!-- Search form -->
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