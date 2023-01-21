<?php include ROOT.'/Views/template/backend-header.php'; ?>

    <section class="section">
        <div class="jumbotron">
            <h1 class="display-4">Blog</h1>
            <p class="lead">Simple Blog</p>
            <hr class="my-4">
            <form action="" method="post" class="post-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="<?php echo $role->name; ?>" required>
                    <div class="invalid-feedback">
                        Please type a name
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>


                    <textarea name="description" id="description" cols="10" rows="10" class="form-control"><?php echo $role->description; ?> </textarea>
                </div>
                <div class="form-group">
                    <label for="description">Permissions</label>
                    <ul class="list-group">
                    <?php
                            foreach ($permissions as $key => $permission) {
                                ?>
                            
                                <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" <?php echo $role->getPermissions()->contains($permission) ? 'checked' : ''; ?>  name="permissions[]" value="<?php echo $permission->getId(); ?>">
                                <?php echo $permission->name; ?>
                                </label>
                            
                                <?php
                            }?>
                     </ul>
                                </div>
                </div>
                <div class="form-group d-block ">
                    <div class="d-inline p-2"><a href="/admin/roles" class="btn btn-secondary">Cancel</a></div>
                    <div class="d-inline p-2"><input type="submit" value="Guardar" class="btn btn-primary" name="submit"></div>
                </div>

            </form>

                    </section>

    
<script>
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.post-form')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
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