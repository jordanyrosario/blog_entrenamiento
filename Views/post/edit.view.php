
<?php

include ROOT.'/Views/template/backend-header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.bubble.css" integrity="sha512-mLecVYo2QWbbYIF2u/ppRT91u615n044kBhrGzqbKQRRQxBUj8BR5b+z9qQsUNyWVYr8Z+c/TISuI7cnbpqpWg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <section class="section">

        <div class="jumbotron">
            <h1 class="display-4">Post</h1>
            <p class="lead">Edit Post</p>
            <hr class="my-4">
            <form action="" method="post" class="post-form" x-data="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input x-on:change="setUrl" id="title" class="form-control" type="text" name="title" value="<?php echo $post->title; ?>"
                        required>
                    <?php if (isset($errors['title'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['title']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Please type a title
                    </div>
                </div>
                <div class="form-group">
                    <label for="title">Slug</label>
                    <input id="title"  x-bind:value="slug" class="form-control" type="text" name="slug" value="<?php echo $post->slug; ?>"
                        pattern="^\S*$" required>
                    <?php if (isset($errors['slug'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['slug']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        This field should not have white spaces
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" class="form-control" type="text" name="description" ><?php echo $post->description; ?> </textarea >
                    <?php if (isset($errors['description'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['description']; ?> </p>
                    </div>

                    <?php }?>
                    <div class="invalid-feedback">
                        Type a description
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-select" aria-label="category" name="category" required>

                        <option selected>Open this select menu</option>

                        <?php
                            foreach ($categories as $category) {
                                ?>
                        <option value="<?php echo $category->getId(); ?>"
                            <?php echo ($post->category->getId() === $category->getId()) ? 'selected' : ''; ?>>
                            <?php echo $category->name; ?></option>
                        <?php }?>

                    </select>
                    <?php if (isset($errors['category'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['category']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                   <textarea name="body" id="body"  cols="10" rows="10" class="form-control"
                        ><?php echo $post->body; ?>
 </textarea>
                      
                        
                    
                    <p><br></p>
                    

                    <?php if (isset($errors['body'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['body']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="publishDate">Publish date</label>
                    <input class="form-control" type="datetime-local" name="publishDate" id="publishDate" value=<?php echo isset($post->publishDate) ? $post->publishDate->format('Y-m-d\\TH:i:s') : ''; ?>>
                    <?php if (isset($errors['publishDate'])) { ?>
                    <div>
                        <p class="text-danger"> <?php echo $errors['publishDate']; ?> </p>
                    </div>

                    <?php }?>
                </div>
                <div class="form-group d-block">
                    <div class="d-inline p-2"><a href="/admin/post" class="btn btn-secondary">Cancel</a></div>
                    <div class="d-inline p-2"><input type="submit" value="Guardar" class="btn btn-primary" name="submit"></div>
                </div>

            </form>

        </div>

    </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js" integrity="sha512-P2W2rr8ikUPfa31PLBo5bcBQrsa+TNj8jiKadtaIrHQGMo6hQM6RdPjQYxlNguwHz8AwSQ28VkBK6kHBLgd/8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
<?php include ROOT.'/Views/template/backend-footer.php'; ?>; ?>