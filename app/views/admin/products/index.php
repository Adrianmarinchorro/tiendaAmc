<?php include_once(VIEWS . 'header.php')?>
<div class="card p-4 bg-light">
    <div class="card-header">
        <h1 class="text-center">Administración de Productos</h1>
    </div>
    <div class="card-body">
        <table class="table text-center" width="100%">
            <thead>
            <th>Id</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Modificar</th>
            <th>Borrar</th>
            </thead>
            <tbody>
            <?php foreach ($data['products'] as $product): ?>
                <tr>
                    <td class="text-center"><?= $product->id ?></td>
                    <td class="text-center"><?= $product->type ?></td>
                    <td class="text-center"><?= $product->name ?></td>
                    <td class="text-center"><?= $product->description ?></td>
                    <td class="text-center">
                        <a href="<?= ROOT ?>adminProduct/update/<?= $product->id ?>"
                           class="btn btn-info"
                        >Editar</a>
                    </td>
                    <td class="text-center">
                        <a href="<?= ROOT ?>adminProduct/delete/<?= $product->id ?>"
                           class="btn btn-danger"
                        >Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-6">
                <a href="<?= ROOT ?>adminProduct/create" class="btn btn-success">
                    Crear Producto
                </a>
            </div>
            <div class="col-sm-6">

            </div>
        </div>
    </div>
</div>
<?php include_once(VIEWS . 'footer.php')?>