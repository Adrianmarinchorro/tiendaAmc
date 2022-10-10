<?php include_once 'header.php'?>

<div class="card p4 bg-light">
    <div class="card-header">
        <h1 class="text-center">
            <?= $data['subtitle'] ?>
        </h1>
    </div>

    <div class="card-body">
        <form action="<?= ROOT ?>login/changePassword/<?= $data['data'] ?>" method="post">

            <input type="hidden" name="id" value="<?=$data['data']?>">

            <div class="form-group text-left">
                <label for="password">Clave de acceso:</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Escriba su contraseña">
            </div>

            <div class="form-group text-left">
                <label for="password2">Repita su clave de acceso:</label>
                <input type="password" name="password2" id="password2" class="form-control" required placeholder="Repita su contraseña">
            </div>

            <div class="form-group text-left">
                <input type="submit" value="Cambiar Contraseña" class="btn btn-success">
            </div>

            <div class="card-footer">
                <div class="row">
                    <a href="<?= ROOT ?>login" class="btn btn-info">Regresar</a>
                </div>
            </div>

        </form>
    </div>

</div>

<?php include_once 'footer.php'?>