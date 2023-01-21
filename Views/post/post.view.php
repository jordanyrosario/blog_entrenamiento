<?php

include ROOT.'/Views/template/backend-header.php';

?>

<section class="section  min-vh-100">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $post->title; ?></h1>
            <p class="lead"><?php echo $post->description; ?></p>
            <hr class="my-4">
            <p>
            <?php echo $post->body; ?>
            
            </p>
            <div class="d-block">
                <div class="d-inline p-2"><a class="btn btn-primary" href="/admin/post">back</a></div>
             
                    <form class="d-inline p-2" action="" method="post">
                    <input type="submit" class="btn btn-danger" name="delete" value="Delete" >
                    </form>
                
          
            </div>
        </div>
       
    </section>
<?php include ROOT.'/Views/template/backend-footer.php'; ?>