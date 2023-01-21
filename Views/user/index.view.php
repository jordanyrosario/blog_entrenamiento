<?php include ROOT.'/Views/template/backend-header.php'; ?>
<div class="page-heading">
    <h3>Users</h3>
</div>
<nav class="navbar navbar-light  ">

    <form class="row" method='GET'>
        <div class="col-auto">
            <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Search"
                aria-label="Search">
        </div>
        <div class="col-auto">
            <input type="submit" class=" btn btn-outline-success my-2 my-sm-0" value="Search">
        </div>

    </form>
</nav>
<section class="section">
    <div class="block table-responsive">
        <!--  <a class="btn btn-primary" href="/user/create">Crear</a></div> -->
        <div class="table-responsive-md">
            <table class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users)) {
                        foreach ($users as $user) {
                            ?>
                    <tr>
                        <td class="title"> <?php echo $user->getId(); ?></td>
                        <td><a href="/admin/users/<?php echo $user->getId(); ?>"> <?php echo $user->name; ?></a></td>
                        <td><?php echo $user->lastName; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td class=""><a class="btn btn-primary" href="/admin/users/edit/<?php echo $user->getId(); ?>"> Edit
                            </a></td>
                    </tr>

                    <tr>
                        <?php
                        }
                    } elseif (null != $result) {
                        foreach ($result as $user) { ?>
                    <tr>
                        <th scope="row"><?php echo $user->getId(); ?></th>
                        <td class="title"><a href="/admin/users/view/<?php echo $user->getId(); ?>"> <?php echo $user->name; ?></a>
                        </td>
                        <td><?php echo $user->lastName; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td class=""><a class="btn btn-primary" href="/admin/users/edit/<?php echo $user->getId(); ?>"> Edit
                            </a></td>

                    </tr>
                    <tr>


                        <?php }
                        } else {?>
                        <h1> No hay resultados </h1>
                        <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
    <?php include ROOT.'/Views/template/backend-footer.php'; ?>