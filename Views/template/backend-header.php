<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Horizontal - Mazer Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/favicon.svg" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.0.1/tinymce.min.js" integrity="sha512-WVGmm/5lH0QUFrXEtY8U9ypKFDqmJM3OIB9LlyMAoEOsq+xUs46jGkvSZXpQF7dlU24KRXDsUQhQVY+InRbncA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <a href="/admin">Blog</a>
                        </div>
                        <div class="header-top-right">

                            <div class="dropdown">
                                <a href="#" class="user-dropdown d-flex dropend" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2" >
                                    <?php if (isset($user->image)) { ?>
                                        <img  class="img-fluid img-thumbnail" src="data:image/jpg;charset=utf8;base64,<?php echo $user->image; ?>" /> 
                                        <?php } else { ?>
                                    <img  src="https://i.imgur.com/0eg0aG0.jpg" >
                                    <?php } ?>
                                    </div>
                                    <div class="text">
                                        <h6 class="user-dropdown-name"><?php echo $user->name.' '.$user->lastName; ?></h6>
                                        <p class="user-dropdown-status text-sm text-muted"><?php echo $user->getAllRoles()->first()->name; ?></p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="dropdownMenuButton1">
                                  <li><a class="dropdown-item" href="/user">My Account</a></li>
                 
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="/logout">Logout</a></li>
                                </ul>
                            </div>

                            <!-- Burger button responsive -->
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <nav class="main-navbar">
                    <div class="container">
                        <ul>
                            
                            
                            
                            <li
                                class="menu-item  ">
                                <a href="/admin" class='menu-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                                                 
                            
                            
                            <li
                                class="menu-item active has-sub">
                                <a href="#" class='menu-link'>
                                    <i class="i bi-newspaper"></i>
                                    <span>Posts</span>
                                </a>
                                <div
                                    class="submenu ">
                                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                                    <div class="submenu-group-wrapper">
                                        
                                        
                                        <ul class="submenu-group">
                                            
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/post"
                                                    class='submenu-link'>All</a>

                                                
                                            </li>
                                            
                                        
                                        
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/post/create"
                                                    class='submenu-link'>New</a>

                                                
                                            </li>
                                            
                                        
                                        
                                            </ul>
                                        
                                    </div>
                                </div>
                            </li>
                            
                            
                            
                            <li
                                class="menu-item  has-sub">
                                <a href="#" class='menu-link'>
                                    <i class="bi bi-file-earmark-medical-fill"></i>
                                    <span>Categories</span>
                                </a>
                                <div
                                    class="submenu ">
                                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                                    <div class="submenu-group-wrapper">
                                        
                                        
                                        <ul class="submenu-group">
                                            
                                            <li   class="submenu-item ">
                                                <a href="/admin/category"
                                                    class='submenu-link'>All</a>

                                                   
                                                
                                                
                                            </li>
                                            
                                            <li class="submenu-item ">
                                                        <a href="/admin/category/create" class="submenu-link">New</a>
                                                </li>                                       
                                    
                                        
                                    </div>
                                </div>
                            </li>
                            
                            
                            
                            <li
                                class="menu-item  has-sub">
                                <a href="#" class='menu-link'>
                                    <i class="bi bi-people-fill"></i>
                                    <span>Users</span>
                                </a>
                                <div
                                    class="submenu ">
                                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                                    <div class="submenu-group-wrapper">
                                        
                                        
                                        <ul class="submenu-group">
                                            
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/users"
                                                    class='submenu-link'>All</a>

                                                
                                            </li>
                                            
                                        
                                        
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/users/create"
                                                    class='submenu-link'>New</a>

                                                
                                            </li>
                                            
                                    
                                        
                                    </div>
                                </div>
                            </li>
                            
                            
                            
                      
                            
                            
                           
                            
                            <li
                                class="menu-item  has-sub">
                                <a href="#" class='menu-link'>
                                    <i class="bi bi-shield-lock-fill"></i>
                                    <span>Roles</span>
                                </a>
                                <div
                                    class="submenu ">
                                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                                    <div class="submenu-group-wrapper">
                                        
                                        
                                        <ul class="submenu-group">
                                            
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/roles"
                                                    class='submenu-link'>All</a>

                                                
                                            </li>
                                            
                                        
                                        
                                            <li
                                                class="submenu-item  ">
                                                <a href="/admin/roles/create"
                                                    class='submenu-link'>New</a>

                                                
                                            </li>
                                                                                    
                                        
                                    </div>
                                </div>
                            </li>
                            
                            
                        </ul>
                    </div>
                </nav>

            </header>

            <div class="content-wrapper container">
                

<div class="page-content min-vh-100">