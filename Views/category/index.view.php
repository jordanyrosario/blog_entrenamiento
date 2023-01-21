<?php include ROOT.'/Views/template/backend-header.php'; ?>
<div class="page-heading">
    <h3>Categories</h3>
</div>
<section class="section">
<div class="block"><a class="btn btn-primary" href="/admin/category/create">Crear</a></div>
            <div class="table-responsive-md">
                <table class="table table-striped table-lg">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">description</th>
                    <th scope="col">slug</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                            <?php
                        foreach ($categories as $category) {
                            ?>
                                <tr>
                            <th scope="row"><?php echo $category->getId(); ?></th>
                            <td class="title"><a href="/admin/category/view/<?php echo $category->getId(); ?>" > <?php echo $category->name; ?></a></td>
                            <td><?php echo substr($category->description, 0, 80); ?></td>
                            <td><?php echo substr($category->slug, 0, 80); ?></td>
                
                            <td>  <a href="/admin/category/edit/<?php echo $category->getId(); ?>" class="btn btn-primary">edit</a></td>
                            </tr>
                            <tr>
                        <?php
                        }
?>
                
                </tbody>
                </table>
            </div>
       
    </div>
<?php
 include ROOT.'/Views/template/backend-footer.php';
?>