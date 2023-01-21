<?php
$title = 'Login';

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
            <h5 class="card-title text-center mb-5 fw-light fs-5">Log In</h5>
            <form method="post">
            <?php if (isset($error)) {?>
               
                    <ul class="text-danger">  
                        <li> 
                            <p  > <?php echo $error; ?> </p> 
                        </li>
                    </ul>
              
         <?php } ?>
              <input type="hidden" name="_csrf" value="<?php echo $_csrf; ?>" />

              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInputEmail" name="email" placeholder="name@example.com">
                <label for="floatingInputEmail">Email address</label>
              </div>

              <hr>

              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
              </div>

            

              <div class="d-grid mb-2">
                <button class="btn btn-lg btn-primary btn-login fw-bold text-uppercase" type="submit">Login</button>
              </div>

              <a class="d-block text-center mt-2 small" href="/password-recovery">Forgot your password?</a>

              <hr class="my-4">

            

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
