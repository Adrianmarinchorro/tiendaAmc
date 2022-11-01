<?php include_once dirname(__DIR__) . ROOT . 'header.php' ?>

    <div class="card p-4 bg-light">
        <div class="card-header">
            <h1 class="text-center">Modulo de Administracion</h1>
        </div>
        <div class="card-body">
            <form action="<?= ROOT ?>admin/verifyUser" method="post">
                <div class="form-group text-left">
                    <label for="user">Usuario:</label>
                    <input type="text" name="admin" class="form-control" placeholder="Escriba su correo electronico" value="<?= $data['data']['user'] ?? '' ?>">
                </div>

                <div class="form-group text-left">
                    <label for="password">Clave de acceso:</label>
                    <input type="password" name="password" class="form-control" placeholder="Escriba su contraseña" value="<?= $data['data']['password'] ?? '' ?>">

                </div>

                <div class="form-group text-left">
                    <input type="submit" value="Enviar" class="btn btn-success mt-2">
                </div>
            </form>
        </div>
        <div class="card-footer">

        </div>
    </div>

<?php include_once dirname(__DIR__) . ROOT . 'footer.php' ?>