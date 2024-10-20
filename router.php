<?php
// Hacemos un require_once de los controllers que usamos
require_once 'config.php';
require_once './app/controllers/PageController.php';
require_once './app/controllers/UserController.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/ProductController.php';
require_once './app/controllers/CategoryController.php';
require_once './app/controllers/CartController.php';
require_once './app/controllers/BuysController.php';


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
    case 'home':
        $controller = new PageController();
        $controller->showHome();
        break;
    case 'login':
        $controller = new PageController();
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
        // Asegúrate de que params[1] esté definido antes de usarlo
        $params = isset($params[1]) ? $params[1] : null; 
        $controller->getProduts($params);
        break;
    case 'category':
        $controller = new ProductController();
        $controller->getProductsByCategory($params[1]);
        break;
    case 'seller':
        $controller = new ProductController();
        $controller->getProductsBySeller($params[1]);
        break;
    case 'quickView':
        $controller = new ProductController();
        $controller->showQuickView($params[1]);
        break;
    case 'createProduct':
        $controller = new ProductController();
        $controller->showCreateProduct();
        break;
    case 'editProduct':
        $controller = new ProductController();
        $controller->showEditProduct($params[1]);
        break;
    case 'editedProduct':
        $controller = new ProductController();
        $controller->editProduct($params[1]);
        break;
    case 'disableProduct':
        $controller = new ProductController();
        $controller->disableProduct($params[1]);
        break;
    case 'addProduct':
        $controller = new ProductController();
        $controller->createProduct();
        break;
    case 'addProductToCart':
        $controller = new CartController();
        $controller->addProduct($params[1]);
        break;
    case 'cart':
        $controller = new CartController();
        $controller->getProductosCarrito();
        break;
     case 'removeItem':
        $controller = new CartController();
        $controller->removeItem($params[1]);
        break;
    case 'comprar':
        $controller = new BuysController();
        $controller->agregarCompra();
        break;
    case 'createCategory':
        $controller = new CategoryController();
        $controller->showCreateCategory();
        break;
    case 'addCategory':
        $controller = new CategoryController();
        $controller->createCategory($params[1]);
        break;
    case 'deleteCategory':
        $controller = new CategoryController();
        $controller->deleteCategory($params[1]);
        break;
    case 'showCategorias':
        $controller = new CategoryController();
        $controller->showCategoryList();
        break;
    case 'sellers':
        $controller = new UserController();
        $controller->getSellers();
        break;
    case 'compraRealizada':
        require_once 'template/compraRealizada.phtml';
        break;
    default:
        require_once 'template/notFound.phtml'; // Mostrar un mensaje de error
        break;
}