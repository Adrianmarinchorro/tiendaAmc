<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $data['title'] ?></title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a href="<?= ROOT ?>" class="navbar-brand">Tienda</a>
    <div class="collapse navbar-collapse" id="menu">

        <?php if($data['menu']): ?>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a href="<?= ROOT ?>courses" class="nav-link <?= (isset($data['active']) && $data['active']=='courses') ? 'active' : '' ?>">Cursos</a>
                </li>
                <li class="nav-item">
                    <a href="<?= ROOT ?>books" class="nav-link <?= (isset($data['active']) && $data['active']=='books') ? 'active' : '' ?>">Libros</a>
                </li>
                <li class="nav-item">
                    <a href="<?= ROOT ?>shop/whoami" class="nav-link <?= (isset($data['active']) && $data['active']=='whoami') ? 'active' : '' ?>">Quienes somos</a>
                </li>
                <li class="nav-item">
                    <a href="<?= ROOT ?>shop/contact" class="nav-link <?= (isset($data['active']) && $data['active']=='contact') ? 'active' : '' ?>">Contacto</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                    <a href="<?= ROOT ?>shop/logout" class="nav-link">Salir</a>
                </li>
            </ul>
        <?php endif; ?>
        <?php if(isset($data['admin']) && $data['admin']): ?>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a href="<?= ROOT ?>adminUser" class="nav-link">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a href="<?= ROOT ?>adminProduct" class="nav-link">Productos</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</nav>
<div class="container-fluid">
    <div class="row content">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <?php if (isset($data['errors']) && count($data['errors']) > 0) : ?>
            <div class="alert alert-danger mt-3">
                <ul class="list-group">
                    <?php foreach($data['errors'] as $value) : ?>
                        <li class="list-group-item alert alert-danger">
                            <strong><?= $value ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
<?php endif; ?>