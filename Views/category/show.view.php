<?php include ROOT.'/Views/template/backend-header.php'; ?>
<div class="container">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $category->name; ?></h1>
            <p class="lead"><?php echo $category->description; ?></p>
            <hr class="my-4">
            <p>
           
            
            </p>
            <div class="d-block">
                <div class="d-inline p-2"><a class="btn btn-primary" href="/admin/category">back</a></div>
               
                    <form class="d-inline p-2" action="" method="category">
                    <input type="submit" class="btn btn-danger" name="delete" value="Delete" >
                    </form>
           
            </div>
        </div>
       
    </div>
<?php include ROOT.'/Views/template/backend-footer.php'; ?>