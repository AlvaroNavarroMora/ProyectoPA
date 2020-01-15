<?php

?>
<!DOCTYPE html>

<html>
    <head>
        <title>UPOMarket</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../frameworks/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="../css/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div id="divTitLogin">
                    <h1 id="titLogin">UPOMarket</h1>
                    </div>
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <h5 class="card-title text-center">Inicio de sesión</h5>
                            <form class="form-signin">
                                <div class="form-label-group">
                                    <input type="email" id="inputEmail" class="form-control" placeholder="Correo electrónico" required autofocus>
                                </div>
                                <br />
                                <div class="form-label-group">
                                    <input type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                </div>
                                <br />
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Recordar contraseña</label>
                                </div>
                                <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Iniciar Sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
