<?php

include ROOT.'/Views/template/backend-header.php'; ?>


    <section class="section  min-vh-100">

        <div class="jumbotron">
            <h1 class="display-4">Post</h1>
            <p class="lead">Create Post</p>
            <hr class="my-4">
            <form action="" method="post" class="post-form" x-data="post">
                <div class="form-group" >
                    <label for="title">Title</label>
                    <input x-on:change="setUrl" id="title" class="form-control" type="text" name="title"
                        value="<?php echo isset($post) ? $post->title : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please type a title
                    </div>
                    <?php if (isset($errors['title'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['title']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="title">Slug</label>
                    <input id="slug" class="form-control" type="text" name="slug" x-bind:value="slug" pattern="^\S*$"
                        value="<?php echo isset($post) ? $post->slug : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please type a slug
                    </div>
                    <?php if (isset($errors['slug'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['slug']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="description" class="form-control" type="text" name="description"
                        value="<?php echo isset($post) ? $post->description : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please type a description
                    </div>
                    <?php if (isset($errors['description'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['description']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-select" aria-label="category" name="category" required>

                        <option selected>Open this select menu</option>

                        <?php
                            foreach ($categories as $category) {
                                ?>
                        <option value="<?php echo $category->getId(); ?>" <?php echo isset($post->category) && $post->category == $category ? 'selected' : ''; ?>> <?php echo $category->name; ?></option>
                        <?php }?>
                
                        </select>
                        
                        <?php if (isset($errors['category'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['category']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="body">Content</label>


                    <textarea name="body" id="body" cols="10" rows="10" class="form-control" ><?php echo isset($post) ? $post->body : ''; ?></textarea>
                    <?php if (isset($errors['body'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['body']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Please type a post body
                    </div>
                </div>
                <div class='row'>
                    <div class="form-group col" id="divPublishDate">
                        <label for="publishDate">Publish date</label>
                        <input class="form-control" type="datetime-local" name="publishDate" id="publishDate" value="<?php echo isset($post->publishDate) ? $post->publishDate->format('Y-m-d\\TH:i') : ''; ?>">
                        <?php if (isset($errors['publishDate'])) { ?>
                        <div>
                            <p class="text-danger"> <?php echo $errors['publishDate']; ?> </p>
                        </div>

                        <?php }?>

                    </div>

                    <div class="form-check col mt-4 ">
                        <input class="form-check-input" type="checkbox" value="null" name="publishDate"
                            id="disablePublish">
                        <label class="form-check-label" for="disablePublish">
                            Not publish yet.
                        </label>
                    </div>
                </div>
                <div class="d-inline p-2"><a href="/admin/post" class="btn btn-secondary">Cancel</a></div>
                <div class="d-inline p-2"><input type="submit" value="Guardar" class="btn btn-primary" name="submit"></div>

            </form>

        </div>

    </section>

    <script >
         tinymce.init({
  selector: '#body',  // change this value according to your HTML
  height: 500,
 
  plugins: 'media  code  fullscreen image link autolink  ',
  toolbar: 'insertfile  undo redo | formatselect  | ' +
  'bold italic  | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | image media code | link autolink |' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
          
    </script>
<script>
const isPublish = document.getElementById("divPublishDate");
const button = document.getElementById("disablePublish");

button.addEventListener("click", function() {
    if (button.checked) {
        isPublish.hidden = true;
    } else {
        isPublish.hidden = false;
    }
});



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
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('post', () => ({
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
<?php include ROOT.'/Views/template/backend-footer.php'; ?>; 