<?php

use App\Controller\PageController;
use App\Controller\PostController;
use App\Controller\RoleController;
use App\Controller\CategoryController;
use App\Controller\UserController;
use App\Controller\LoginController;
use FastRoute\RouteCollector;
use Mezzio\Authentication\AuthenticationMiddleware;
use App\Controller\RegisterController;
use Core\AuthorizationMiddleware;
use App\Controller\PasswordRecoveryController;
use App\Controller\UserProfileController;
use App\Controller\ContactController;
use App\Controller\DashboardController;

$router->addRoute('GET', '/', [PageController::class, 'index']);
$router->addRoute('GET', '/search', [PageController::class, 'search']);
$router->addRoute('GET', '/view/{slug}', [PageController::class, 'show']);
// Comment route
$router->addRoute('POST', '/view/{slug}', [PageController::class, 'comment', 'middlewares' => [
    AuthenticationMiddleware::class]]);
$router->addRoute('POST', '/view/{slug}/deleteComment', [PageController::class, 'deleteComment', 'middlewares' => [
    AuthenticationMiddleware::class]]);

$router->addRoute('GET', '/contact', [ContactController::class, 'index']);
$router->addRoute('POST', '/contact', [ContactController::class, 'send']);
$router->addRoute('GET', '/login', [LoginController::class, 'login']);
$router->addRoute('POST', '/login', [LoginController::class, 'auth']);
$router->addRoute('GET', '/logout', [LoginController::class, 'logout']);
$router->addRoute('GET', '/register', [RegisterController::class, 'index']);
$router->addRoute('POST', '/register', [RegisterController::class, 'register']);

$router->addRoute('GET', '/admin', [DashboardController::class, 'index', 'middlewares' => [
    AuthenticationMiddleware::class,
    new AuthorizationMiddleware(['post.list'])]]);

$router->addGroup('/admin/post', function (RouteCollector $router) {
    $router->addRoute('GET', '/create', [PostController::class, 'create', 'middlewares' => [
        AuthenticationMiddleware::class]]);
    $router->addRoute('POST', '/create', [PostController::class, 'store', 'middlewares' => [
        AuthenticationMiddleware::class]]);
    $router->addRoute('GET', '/{id:\d+}', [PostController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class]]);
    $router->addRoute('GET', '', [PostController::class, 'index', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['post.list'])]]);
    $router->addRoute('GET', '/edit/{id:\d+}', [PostController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['post.edit'])]]);
    $router->addRoute('POST', '/edit/{id:\d+}', [PostController::class, 'update', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['post.update'])]]);
    $router->addRoute('GET', '/view/{id:\d+}', [PostController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['post.show'])]]);
    $router->post('/view/{id:\d+}', [PostController::class, 'destroy', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['post.delete'])]]);
});

$router->addGroup('/admin/category', function (RouteCollector $router) {
    $router->addRoute('GET', '/create', [CategoryController::class, 'create', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.create'])]]);
    $router->addRoute('POST', '/create', [CategoryController::class, 'store', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.create'])]]);
    $router->addRoute('GET', '/{id:\d+}', [CategoryController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.show'])]]);
    $router->addRoute('GET', '', [CategoryController::class, 'index', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.list'])]]);
    $router->addRoute('GET', '/edit/{id:\d+}', [CategoryController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.edit'])]]);
    $router->addRoute('POST', '/edit/{id:\d+}', [CategoryController::class, 'update', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.edit'])]]);
    $router->addRoute('GET', '/view/{id:\d+}', [CategoryController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.delete'])]]);
    $router->post('/view/{id:\d+}', [CategoryController::class, 'destroy', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['category.delete'])]]);
});

// role's routes
$router->addgroup('/admin/roles', function (RouteCollector $router) {
    $router->addRoute('GET', '', [RoleController::class, 'index', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.list'])]]);
    $router->addRoute('GET', '/create', [RoleController::class, 'create', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.create'])]]);
    $router->addRoute('POST', '/create', [RoleController::class, 'store', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.create'])]]);
    $router->addRoute('GET', '/view/{id:\d+}', [RoleController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.show'])]]);
    $router->post('/view/{id:\d+}', [RoleController::class, 'destroy', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.delete'])]]);
    $router->addRoute('GET', '/edit/{id:\d+}', [RoleController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.edit'])]]);
    $router->addRoute('POST', '/edit/{id:\d+}', [RoleController::class, 'update', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['role.edit'])]]);
});
$router->addGroup('/admin/users', function (RouteCollector $router) {
    // User's routes
    $router->addRoute('GET', '', [UserController::class, 'index', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['user.list'])]]);
    $router->addRoute('GET', '/edit/{id:\d+}', [UserController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['user.edit'])]]);
    $router->addRoute('POST', '/edit/{id:\d+}', [UserController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['user.edit'])]]);
    $router->addRoute('GET', '/{id:\d+}', [UserController::class, 'view', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['user.show'])]]);
    $router->post('/{id:\d+}', [UserController::class, 'destroy', 'middlewares' => [
        AuthenticationMiddleware::class,
        new AuthorizationMiddleware(['user.delete'])]]);
});

$router->addGroup('/user', function (RouteCollector $router) {
    $router->addRoute('GET', '', [UserProfileController::class, 'edit', 'middlewares' => [
        AuthenticationMiddleware::class]]);
    $router->addRoute('POST', '', [UserProfileController::class, 'save', 'middlewares' => [
        AuthenticationMiddleware::class]]);
});

$router->get('/password-recovery', [PasswordRecoveryController::class, 'passwordRecovery']);
$router->get('/link-sent', [PasswordRecoveryController::class, 'linkSent']);
$router->get('/password-reset', [PasswordRecoveryController::class, 'verifyToken']);
$router->post('/password-reset', [PasswordRecoveryController::class, 'resetPassword']);
$router->post('/password-recovery', [PasswordRecoveryController::class, 'getLink']);
$router->get(
    '/{slug:[a-z0-9]+(?:-[a-z0-9]+)*}',
    [PageController::class, 'show']
);
