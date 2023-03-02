<?php
ini_set("display_erroes", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'cursophp',
    'username' => 'root',
    'password' => '',
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
]);
$map->get('addJobs', '/job', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'jobAction',
]);
$map->post('saveJobs', '/job', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'jobAction',
]);
$map->get('addProject', '/project', [
    'controller' => 'App\Controllers\ProjectController',
    'action' => 'projectAction',
]);
$map->post('saveProject', '/project', [
    'controller' => 'App\Controllers\ProjectController',
    'action' => 'projectAction',
]);
$map->get('addUser', '/user', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'postSaveUser',
]);
$map->post('saveUser', '/user', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'postSaveUser',
]);
$map->get('loginForm', '/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin',
]);
$map->post('auth', '/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postLogin',
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'No route ';
}else{
    $handlerData = $route->handler;
    $controller = new $handlerData['controller'];
    $response = $controller->{$handlerData['action']}($request);

    foreach($response->getHeaders() as $name => $values){
        foreach($values as $value){
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
}
?>