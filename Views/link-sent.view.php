<?php
$title = 'Link for recovery sent';

require_once '../Views/template/singIn.header.php';

?>
  <body>
  <div class="container">
    <div class="row">
        <pre>
            <code>
            <?php echo isset($errors) ?? var_dump($errors); ?>
            </code>
        </pre>
      <div class="col-lg-10 col-xl-9 mx-auto">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
          <div class="card-img-left d-none d-md-flex">
            <!-- Background image for card set in CSS! -->
          </div>
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Link sent Succesfully</h5>
            <form method="post">

             

              

              <hr>

            
            

              <a class="d-block text-center mt-2 small" href="/login">Go back to login</a>

              <hr class="my-4">

            

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
