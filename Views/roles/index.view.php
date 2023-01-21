<?php include ROOT.'/Views/template/backend-header.php'; ?>
<div class="page-heading">
    <h3>Roles</h3>
</div>
    <section class="section">
        <div class="block "><a class="btn btn-primary" href="roles/create">Crear</a></div>
        <div class="table-responsive-md">
            <table class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($roles as $role) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo $role->getId(); ?></th>
                            <td class="title"><a href="/admin/roles/view/<?php echo $role->getId(); ?>"> <?php echo $role->name; ?></a></td>
                            <td><?php echo isset($role->description) ? substr($role->description, 0, 80) : ''; ?></td>
                            <td> <a href="/admin/roles/edit/<?php echo $role->getId(); ?>" class="btn btn-primary">Edit</a></td>
                        </tr>
            
                        <?php
                    }
?>
                </tbody>
            </table>
        </div>

    </section>
<?php include ROOT.'/Views/template/backend-footer.php'; ?>
