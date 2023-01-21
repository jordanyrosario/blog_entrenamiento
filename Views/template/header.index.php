<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title><?php echo $title; ?></title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/blog/">



    <!-- Bootstrap core CSS -->
    <!-- <link href="../bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


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

    #Blog-title {

        text-decoration: none;

    }

    #Blog-title:hover {
        text-decoration: underline;
    }
    </style>


    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../blog.css" rel="stylesheet">
</head>


<body>

    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">

                <div class="col-8 text-center">
                    <a class="blog-header-logo text-dark" href="/">Blog</a>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <form action="/search" class="form-inline my-2 my-lg-0" method="get">
                        <div class="input-group">

                            <input class="form-control mr-sm-2" type="search" placeholder="Search" name="search" id="search" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </div>

                    </form>
                    <?php if (!empty($userInfo)) { ?>

                    <div class="dropdown">
                        <a href="#" class="user-dropdown d-flex justify-content-evenly dropend btn btn-sm " data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="avatar avatar-md2">
                                <?php if (isset($userInfo['details']['image'])) { ?>
                                <img  class="rounded-circle img-thumbnail" width="50"
                                    src="data:image/jpg;charset=utf8;base64,<?php echo $userInfo['details']['image']; ?>" />
                                <?php } else { ?>
                                <img class="rounded-circle" style="width: 70px;" src="https://i.imgur.com/0eg0aG0.jpg">
                                <?php } ?>
                            </div>
                            <div class="text pl-8 pt-md-4">
                                <h6 class="user-dropdown-name ">
                                    <?php echo $userInfo['details']['name'].' '.$userInfo['details']['lastName']; ?></h6>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/user">My Account</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php if (in_array('post.show', $userInfo['details']['permissions'])) { ?>
                            <li><a class="dropdown-item" href="/admin/post">Admin site</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <?php } ?>

                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                    <?php } else { ?>
                    <a class="btn btn-sm btn-outline-primary" href="/register">Sign up</a>
                    <a class="btn btn-sm btn-outline-secondary" href="/login">Sign in</a>
                    <?php }?>

                </div>
            </div>
        </header>

    </div>