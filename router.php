<?php
// Hacemos un require_once de los controllers que usamos
require_once 'config.php';
require_once './app/controllers/UserController.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/ProductController.php';
require_once './app/controllers/CategoryController.php';

// Definimos la constante "BASE_URL"
define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

$action = 'products'; // Accion por defecto
// Verificamos que la acción exista
if (!empty( $_GET['action'])) {
    $action = $_GET['action'];
}


// parseamos la accion para separar accion real de parametros
$params = explode('/', $action);

switch ($params[0]) {
    case 'login':
        $controller = new AuthController();
        $controller->showLogin();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'auth':
        $controller = new AuthController();
        $controller->auth();
        break;
    case 'createUser':
        $controller = new UserController();
        $controller->showCreateUSer();
        break;
    case 'registerUser':
        $controller = new UserController();
        $controller->createUser();
        break;
    case 'users':
        $controller = new UserController();
        $controller->showUsers();
        break;
    case 'products':
        $controller = new ProductController();
        $controller->getProduts();
        break;
    case 'category':
        $controller = new ProductController();
        $controller->getProductsByCategory($params[1]);
        break;
    case 'seller':
        $controller = new ProductController();
        $controller->getProductsBySeller($params[1]);
        break;
    case 'createProduct':
        $controller = new ProductController();
        $controller->showCreateProduct();
        break;
    case 'addProduct':
        $controller = new ProductController();
        $controller->createProduct();
        break;
    case 'createCategory':
        $controller = new CategoryController();
        $controller->showCreateCategory();
        break;
    case 'addCategory':
        $controller = new CategoryController();
        $controller->createCategory([1]);
        break;
    default:
        echo 'Página no encontrada'; // Mostrar un mensaje de error, o redirigir
        break;
}