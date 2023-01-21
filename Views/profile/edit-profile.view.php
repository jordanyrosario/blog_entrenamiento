
<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User: <?php echo $user->name; ?> </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container">
    <div class="container rounded bg-white mt-5">
    <div class="row">
        <div class="col-md-4 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
           <?php if (isset($user->image)) { ?>
                       <img  class="rounded-circle mt-5" width="90" src="data:image/jpg;charset=utf8;base64,<?php echo $user->image; ?>" /> 
                    <?php } else { ?>
                <img class="rounded-circle mt-5" src="https://i.imgur.com/0eg0aG0.jpg" width="90">
                <?php } ?>
                <span class="font-weight-bold"><?php echo $user->name.' '.$user->lastName; ?></span><span class="text-black-50"><?php echo $user->email; ?></span><span> <?php
            $roles = $user->getAllRoles()->map(function ($role) {
                return $role->name;
            });
foreach ($roles as $role) {
    echo $role;
}
?> </span></div>
        </div>
        <div class="col-md-8 border-start ">
            <div class="p-3 py-5">
                <form method="post" enctype="multipart/form-data">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <input type="hidden" name="_csrf" value="<?php echo $_csrf; ?>" />
                        <h6 class="text-right">Edit Profile</h6>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="first name" value="<?php echo $user->name; ?>">
                        <?php if (isset($errors['name'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['name']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                       
                        <div class="col-md-6"><input type="text" name="lastName" class="form-control" value="<?php echo $user->lastName; ?>" placeholder="Doe">
                        <?php if (isset($errors['lastName'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['lastName']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $user->email; ?>">
                        <?php if (isset($errors['email'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['email']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                        <div class="col-md-6"><input type="file" name="image" class="form-control" placeholder="Image">
                        <?php if (isset($errors['image'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['image']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                    
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    
                        <h6 class="text-right">Change password</h6>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="type new password" >
                        <?php if (isset($errors['password'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['password']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                        <div class="col-md-6"><input type="password" name="password_confirm" class="form-control" placeholder="confirm new password">
                        <?php if (isset($errors['password_confirm'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['password_confirm']; ?> </p>
                        </div>

                    <?php }?></div>
                    
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    
                    <h6 class="text-right">Enter current password</h6>
                </div>
                    <div class="row mt-3">
                    
                        <div class="col-md-6"><input type="password" name="password_old" class="form-control" placeholder="Enter current password">
                        <?php if (isset($errors['password_old'])) { ?>
                        <div>
                        <p class="text-danger"> <?php echo $errors['password_old']; ?> </p>
                        </div>

                    <?php }?>
                    </div>
                    </div>
                    <div class="row">
                        <div class="mt-5 text-right mx-4 col-1">
                            <a class="btn btn-secondary profile-button " type="button" href="/">Cancel </a>
                                    </div>
                                      <div class="mt-5 text-right col-1">
                            <button class="btn btn-primary profile-button" type="submit">Save </button>
                                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                
    </div>

</body>

</html>