
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Signin Template · Bootstrap v5.0</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    

    <!-- Bootstrap core CSS -->

<meta name="theme-color" content="#7952b3">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body>

  
  <!-- Wrapper container -->
<div class="container py-2">
<div class="jumbotron my-4">
            <h1 class="display-4">Blog</h1>
            <p class="lead">Contact Us</p>
            <hr class="my-4">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum obcaecati
                voluptatibus aliquid autem at deleniti. Officia, facilis veniam nisi neque,
                quae quia magnam veritatis non provident ullam, odit temporibus quas!

            </p>
        </div>

<!-- Bootstrap 5 starter form -->
<form id="contactForm mt-3" method="post">

  <!-- Name input -->
  <div class="mb-3">
    <label class="form-label" for="name">Name</label>
    <input class="form-control" id="name" type="text" name="name" placeholder="Name" />
    <?php if (isset($errors['name'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['name']; ?> </p>
                    </div>

                    <?php }?>
  </div><div class="mb-3">
    <label class="form-label" for="name">subject</label>
    <input class="form-control" id="subject" name="subject" type="text" placeholder="Subject" />
    <?php if (isset($errors['name'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['subject']; ?> </p>
                    </div>

                    <?php }?>
  </div>

  <!-- Email address input -->
  <div class="mb-3">
    <label class="form-label" for="emailAddress">Email Address</label>
    <input class="form-control" id="emailAddress" type="email" name="email" placeholder="Email Address" />
    <?php if (isset($errors['email'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['email']; ?> </p>
                    </div>

                    <?php }?>
  </div>

  <!-- Message input -->
  <div class="mb-3">
    <label class="form-label" for="message">Message</label>
    <textarea class="form-control" id="message" type="text" name="body" placeholder="Message" style="height: 10rem;"></textarea>
    <?php if (isset($errors['body'])) { ?>
                    <div>
                     <p class="text-danger"> <?php echo $errors['body']; ?> </p>
                    </div>

                    <?php }?>
  </div>

  <!-- Form submit button -->
  <div class="d-grid">
    <button class="btn btn-primary btn-lg" type="submit">Submit</button>
  </div>

</form>

</div>
</body>
</html>
