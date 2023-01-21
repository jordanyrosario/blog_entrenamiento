<?php

use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use App\Entities\User;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Session\SessionPersistenceInterface;
use Mezzio\Session\Ext\PhpSessionPersistence;
use Mezzio\Authentication\Session\PhpSession;
use Laminas\Diactoros\Response\EmptyResponse;
use Core\RouteMiddleware;
use Mezzio\Csrf\SessionCsrfGuardFactory;
use Mezzio\Csrf\CsrfMiddleware;

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Core\Database\DB;
use Core\App;
use Laminas\Diactoros\ServerRequestFactory;
use Core\RequestHandlerMiddleware;
use Core\Container\Container;
use Laminas\Diactoros\ResponseFactory;

$request = ServerRequestFactory::fromGlobals();

define('ROOT', __DIR__);

function processTemplate(array $data, string $name): string
{
    extract($data);
    ob_start();

    require "Views/{$name}.view.php";

    return ob_get_clean();
}
function View($name, $data = []): ResponseInterface
{
    $content = processTemplate($data, $name);

    return new HtmlResponse($content);
}
function BadRequest($data = [])
{
    $content = processTemplate($data, '400');

    return new HtmlResponse($content, 400);
}
function NotFound($data = []): ResponseInterface
{
    $content = processTemplate($data, '404');

    return new HtmlResponse($content, 404);
}
function serverError($data = [])
{
    $content = processTemplate($data, '500');

    return new HtmlResponse($content, 500);
}

$container = new Container();
$entityManager = DB::Connection();
$userRepository = $entityManager->getRepository(User::class);

$container->register(EntityManager::class, $entityManager);
$container->register(SessionPersistenceInterface::class, PhpSessionPersistence::class);
$container->register(UserRepositoryInterface::class, $userRepository);

$app = new App($container);
$app->setRquest($request);

$responseFactory = new ResponseFactory();
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
    require 'routes.php';
});
// $auraRouter = new RouterContainer();
// $router = $auraRouter->getMap();
// require 'routes.php';
$userfactory = function (
    string $identity,
    array $roles = [],
    array $details = []
) use ($userRepository) {
    return $userRepository->findOneBy(['email' => $identity]);
};
$phpAuth = new PhpSession($userRepository, ['redirect' => '/login', 'username' => 'email'], function () {
    return new EmptyResponse();
}, $userfactory);
$container->register(AuthenticationInterface::class, $phpAuth);
$app->use([
    new SessionMiddleware(new PhpSessionPersistence()),
    new CsrfMiddleware(new SessionCsrfGuardFactory()),
    new RouteMiddleware($dispatcher, $responseFactory),
    new RequestHandlerMiddleware($app, $container),
]);

return $app;
