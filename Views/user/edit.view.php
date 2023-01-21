<?php include ROOT.'/Views/template/backend-header.php'; ?>
    <section class="section">
        <div class="jumbotron">
            <h1 class="display-4">User</h1>
            <p class="lead">Edit User</p>
            <hr class="my-4">
            <form action="" method="post" class="user-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="<?php echo $user->name; ?>" required>
                    <?php if (isset($errors['name'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['name']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Please type a name
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Last Name</label>
                    <input id="name" class="form-control" type="text" name="lastName" value="<?php echo $user->lastName; ?>" required>
                    <?php if (isset($errors['lastName'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['lastName']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Please type a last name
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Email</label>
                    <input id="name" class="form-control" type="text" name="lastName" value="<?php echo $user->email; ?>" required>
                    <?php if (isset($errors['email'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['email']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Please type a valid email
                    </div>
                </div>
                <div class="form-group">
                <label for="category">Role</label>
                <select class="form-select" aria-label="roles" name="roles[]"  required>
                    
                   
                    
                            <?php
                            foreach ($roles as $role) {
                                ?>
                        <option value="<?php echo $role->getId(); ?>" 
                         <?php echo ($user->getAllRoles()->contains($role)) ? 'selected' : ''; ?> ><?php echo $role->name; ?></option>
                        <?php }?>
                
                        </select>
                        <?php if (isset($errors['role'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['role']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                    <div class="form-group d-block">
                        <div class="d-inline p-2"><a href="/admin/users" class="btn btn-secondary">Cancel</a></div>
                    <div class="d-inline p-2"><input type="submit" value="Guardar" class="btn btn-primary" name="submit"></div>
                    </div>

            </form>

        </div>

        </section>


<script>
    (function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.user-form')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>
<?php include ROOT.'/Views/template/backend-footer.php'; ?>
