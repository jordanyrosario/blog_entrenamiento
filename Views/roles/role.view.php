<?php

include ROOT.'/Views/template/backend-header.php';

?>

    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $role->name; ?></h1>
            <p class="lead">Description: <?php echo $role->description; ?></p>
            <br/>
            <hr/>
            <p class="lead">Permissions</p>
            
            <ul class="list-group">
                     <?php
                            foreach ($role->getPermissions() as $key => $permission) {
                                ?>
                
                         <li class="list-group-item"><?php echo $permission->name; ?></li>
                    
                        <?php
                            }?>
                     </ul>
            
            <div class="d-block  ">
                <div class="d-inline p-2">
                    <a  href="/admin/roles"  class="btn btn-secondary">back</a>
                </div>
        
                    <form class="d-inline p-2" action="" method="post">
                        <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                    </form>
            
            </div>
        </div>

    </div>
<?php include ROOT.'/Views/template/backend-footer.php'; ?>