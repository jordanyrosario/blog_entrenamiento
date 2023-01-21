<?php include ROOT.'/Views/template/backend-header.php'; ?>
   
    <section class="section">
        <div class="jumbotron">
            <h1 class="display-4">Category</h1>
            <p class="lead">Edit Category</p>
            <hr class="my-4">
            <form action="" method="post" class="category-form" x-data="category">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input x-on:change="setUrl" id="name" class="form-control" type="text" name="name" value="<?php echo $category->name; ?>" required>
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
                    <label for="slug">Slug</label>
                    <input x-bind:value="slug" id="slug" class="form-control" type="text" name="slug" value="<?php echo $category->slug; ?>" pattern="^\S*$" required>
                    <?php if (isset($errors['slug'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['slug']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        This field should not have white spaces or undercores
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="description" class="form-control" type="text" name="description" value="<?php echo $category->description; ?>" required>
                    <?php if (isset($errors['description'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['description']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Type a description
                    </div>
                </div>
               
                    <div class="form-group d-block">
                        <div class="d-inline p-2"><a href="/admin/category" class="btn btn-secondary">Cancel</a></div>
                    <div class="d-inline p-2"><input type="submit" value="Guardar" class="btn btn-primary" name="submit"></div>
                    </div>

            </form>

        </div>

        </section>
        <script>
document.addEventListener('alpine:init', () => {
        Alpine.data('category', () => ({
            slug:"",
 
            setUrl(e) {
                var value = e.target.value;
                this.slug = value.toLowerCase()
                            .replace(/ /g, '-')
                            .replace(/[^\w-]+/g, '');
            }
        }))
    })
</script>

<script>
    (function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.category-form')

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
