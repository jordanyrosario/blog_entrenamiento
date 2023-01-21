<?php

include ROOT.'/Views/template/backend-header.php';

?>

    <section class="section">
        <div class="jumbotron">
         
          <?php if (isset($user->image)) { ?>
            <img  class="rounded-circle mt-5" width="90" src="data:image/jpg;charset=utf8;base64,<?php echo $user->image; ?>" /> 
                    <?php } else { ?>
                <img class="rounded-circle mt-5" src="https://i.imgur.com/0eg0aG0.jpg" width="90">
                <?php } ?>


            <h1 class="display-7"><?php echo $user->name.' '.$user->lastName; ?></h1>
            <p class="lead">
                Email: <?php echo $user->email; ?>
            </p>
            <hr class="my-4">
            <p class="lead">Roles</p>
            
            <ul class="list-group">
                     <?php
                            foreach ($user->getAllRoles() as $key => $role) {
                                ?>
                
                         <li class="list-group-item"><?php echo $role->name; ?></li>
                    
                        <?php
                            }?>
            </ul>
            <div class="form-group d-block">
                <div class="d-inline p-2" ><a class="btn btn-primary" href="/admin/users">Back</a></div>
      
                
                    <form class="d-inline p-2" action="" method="post">
                        <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                    </form>
             
            </div>
        </div>

                
    </section>

    <?php include ROOT.'/Views/template/backend-footer.php'; ?>
