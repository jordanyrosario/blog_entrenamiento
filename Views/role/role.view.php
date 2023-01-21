<?php

$title = 'Role '.$role->name;

require_once '../Views/template/header.php';

?>

<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $role->name; ?></h1>
            <p class="lead">Description: <?php echo $role->description; ?></p>
            <div><a class="btn btn-primary" href="/admin/role">back</a></div>
            <div>
                <form action="" method="post">
                    <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                </form>

            </div>
        </div>

    </div>
</body>

</html>