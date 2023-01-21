<?php

include ROOT.'/Views/template/backend-header.php'; ?>
<div class="page-heading">
    <h3>Posts</h3>
</div>

<section class="section  min-vh-100">
    <nav class="navbar navbar-light ">

        <form class="row" method='GET'>
            <div class="col-auto">
                <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Search"
                    aria-label="Search">
            </div>
            <div class="col-auto">
                <input type="submit" class=" btn btn-outline-success my-2 my-sm-0" value="Search">
            </div>
            <div class="col-auto">

                <select class="form-select " aria-label="Filter" name="order" id='order'>
                    <option value="" selected>Filter by</option>
                    <option value="ASC">Publish date: Old to New</option>
                    <option value="DESC">Publish date: New to Old</option>
                </select>
            </div>
            <div class="card-body">
            <div class="block "><a class="btn btn-primary" href="/admin/post/create">Crear</a></div>
        <div class="table-responsive-md">
            <table class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">description</th>
                        <th scope="col">Created</th>
                        <th scope="col">Updated</th>
                        <th scope="col">Publication date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($posts)) {
                        foreach ($posts as $post) {
                            ?>
                    <tr>
                        <th scope="row"><?php echo $post->getId(); ?></th>
                        <td class="title"><a href="/admin/post/view/<?php echo $post->getId(); ?>"> <?php echo $post->title; ?></a></td>
                        <td><?php echo substr($post->description, 0, 80); ?></td>
                        <td><?php echo $post->getCreatedDate()->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $post->getUpdatedDate()->format('Y-m-d H:i:s'); ?></td>
                        <?php if (null == $post->getPublishDate()) { ?>
                        <td> Not published yet </td>
                        <?php } else {  ?>
                        <td> <?php echo $post->getPublishDate()->format('Y-m-d H:i:s'); ?> </td>
                        <?php } ?>
                        <td> <a href="/admin/post/edit/<?php echo $post->getId(); ?>" class="btn btn-primary">edit</a></td>
                    </tr>

                    <?php
                        }
                    } elseif (null != $result) {
                        foreach ($result as $post) {   ?>
                    <tr>
                        <th scope="row"><?php echo $post->getId(); ?></th>
                        <td class="title"><a href="/admin/post/view/<?php echo $post->getId(); ?>"> <?php echo $post->title; ?></a></td>
                        <td><?php echo substr($post->description, 0, 80); ?></td>
                        <td><?php echo $post->getCreatedDate()->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $post->getUpdatedDate()->format('Y-m-d H:i:s'); ?></td>
                        <?php if (null == $post->getPublishDate()) { ?>
                        <td> Not published yet </td>
                        <?php } else {  ?>
                        <td> <?php echo $post->getPublishDate()->format('Y-m-d H:i:s'); ?> </td>
                        <?php } ?>
                        <td> <a href="/admin/post/edit/<?php echo $post->getId(); ?>" class="btn btn-primary">edit</a></td>
                    </tr>
                    <?php }
                        } else {?>
                    <h1> No hay resultados </h1>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>




    
    <?php include ROOT.'/Views/template/backend-footer.php'; ?>
