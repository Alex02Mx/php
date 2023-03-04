<?php
ini_set("display_erroes", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();


$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction',
    'auth' => true,
   
]);
$map->get('addJobs', '/job', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'jobAction',
    'auth' => true,
]);
$map->post('saveJobs', '/job', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'jobAction',
    'auth' => true,
]);
$map->get('addProject', '/project', [
    'controller' => 'App\Controllers\ProjectController',
    'action' => 'projectAction',
    'auth' => true,
]);
$map->post('saveProject', '/project', [
    'controller' => 'App\Controllers\ProjectController',
    'action' => 'projectAction',
    'auth' => true,
]);
$map->get('addUser', '/user', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'postSaveUser',
    'auth' => true,
]);
$map->post('saveUser', '/user', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'postSaveUser',
    'auth' => true,
]);
$map->get('loginForm', '/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin',
]);
$map->post('auth', '/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin',
    //'auth' => true,
]);
$map->get('logoutForm', '/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout',
]);
$map->get('admin', '/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getindex',
    'auth' => true,
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'No route ';
}
else{
    $handlerData = $route->handler;
    // $controller = new $handlerData['controller'];
    // $response = $controller->{$handlerData['action']}($request);

    $needsAuth = $handlerData['auth'] ?? false;
    $sessionId = $_SESSION['userId'] ?? null;

    if( $needsAuth && !$sessionId ){
        $controllerName = 'App\Controllers\AuthController';
        $responseName = 'getLogout';
    }
    else{
        $controllerName = $handlerData['controller'];
        $responseName = $handlerData['action'];
    }

    $controller = new $controllerName;
    $response = $controller->$responseName($request);
  
    foreach($response->getHeaders() as $name => $values){
        foreach($values as $value){
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
    
  }
?>