<?php
require_once 'app/helpers/AuthHelper.php';
require_once 'app/helpers/RedirectHelper.php';
require_once "app/views/ErrorView.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CategoryModel.php";
require_once "app/models/UserModel.php";
require_once "app/views/ProductView.php";

class ProductController{
    private $modelProduct;
    private $modelCategory;

    private $modelUser;
    private $viewProduct;
    private $ErrorView;

    public function __construct(){
        $this->modelProduct = new ProductModel();
        $this->viewProduct = new ProductView();
        $this->ErrorView = new ErrorView();
        $this->modelCategory = new CategoryModel();
        $this->modelUser = new UserModel();
    }
    
    public function getProduts($params) {
    // Si se pasan parámetros, intenta obtener productos por categoría
    if ($params) {
        $products = $this->modelProduct->getProductsByCategoria($params);
    } else {
        // Si no hay parámetros, obtener todos los productos
        $products = $this->modelProduct->getProducts();
    }

    // Verificar si se encontraron productos
    if ($products) {
        $this->viewProduct->showProducts($products);
    } else {
        // Mostrar un mensaje de error si no hay productos
        $this->ErrorView->showError('Los productos no existen en la base de datos.');
    }
}

    public function showHome(){
        $products =$this->modelProduct->getProducts();
        $cantUsers = $this->modelUser->getCantUsers();
        $cantProducts = $this->modelProduct->getCantProducts();
        $cantDinero = $this->modelProduct->getCantDinero();
        $this->viewProduct->showInicio($products, $cantUsers, $cantProducts, $cantDinero);
    }

    public function getProductById($product_id){
        $products = $this->modelProduct->getProductById($product_id);
        if($products){
            $this->viewProduct->showProduct($products);
        }else{
            $this->ErrorView->showError('El producto no existe en la base de datos.');
        }
    }
    
    function getProductsByCategory($categoria){
        $products = $this->modelProduct->getProductsByCategoria($categoria);
        if($products){
            $this->viewProduct->showProducts($products);
        }else{
            $this->ErrorView->showError('Los productos no existen en la base de datos.');
        }
    }

    function getProductsBySeller($vendedor_id){
        $products = $this->modelProduct->getProductsByCategoria($vendedor_id);
        if($products){
            $this->viewProduct->showProducts($products);
        }else{
            $this->ErrorView->showError('Los productos no existen en la base de datos.');
        }
    }
    
    function createProduct(){
        if(AuthHelper::isLogged()){
            $nombre = $_POST['nombre'];
            $categoria = $_POST['categoria'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagen = "5904";
            $stock = $_POST['stock'];
        
            $id_vendedor = AuthHelper::getLoggedInUserId();
            $fecha_creacion = date('Y-m-d H:i:s');
            
            // Verifica que todos los campos requeridos estén completo
            if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($imagen) || empty($stock)) {
                // Si faltan campos, muestra un error
                $this->viewProduct->showCreateProduct("Faltan completar campos");
            } else {
                // Llama al modelo para crear el producto con los datos recogidos
                $this->modelProduct->createProduct($id_vendedor, $categoria, $nombre, $descripcion, $precio, $imagen, $stock, $fecha_creacion);
                // Redirige a la página de inicio o muestra un mensaje de éxito
                $this->showHome();
            }
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    function editProduct($producto_id){
        $id_vendedor = $this->modelProduct->getSellerId($producto_id);
        if(AuthHelper::isLogged() && AuthHelper::getLoggedInUserId() == $id_vendedor){
            $nombre = $_POST['nombre'];
            $categoria = $_POST['categoria'];
            $descripcio = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagen = $_POST['imagen'];
            $stock = $_POST['stock'];

            if (empty($nombre) || empty($categoria) || empty($descripcio) || empty($precio) || empty($imagen) || empty($stock)) {
                $this->viewProduct->showEditProduct("Faltan completar campos");
            }else{
                $this->modelProduct->editProduct($producto_id,$categoria, $nombre, $descripcio, $precio, $imagen, $stock);
                $this->viewProduct->showInicio("Producto modificado");
            }
        }else{
            RedirectHelper::redirectToLogin();
        }
    }
    
    function deleteProduct($producto_id){
        if(!AuthHelper::isLogged()){
            RedirectHelper::redirectToLogin();
            return;
        }

        $id_vendedor = $this->modelProduct->getSellerId($producto_id);
        if(AuthHelper::getLoggedInUserId() == $id_vendedor || AuthHelper::isAdmin()){
            $this->modelProduct->deleteProduct($producto_id);
        }else{
            RedirectHelper::redirectToLogin();
        }
        header('Location:' . BASE_URL . 'products');
    }

    function showCreateProduct(){
        if(!AuthHelper::isLogged()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $categories = $this->modelCategory->getCategories();
        $this->viewProduct->showCreateProduct(null,$categories);
    }

    function showQuickView($id_producto){
        $producto = $this->modelProduct->getProductById($id_producto);
        $this->viewProduct->showQuickView($producto);
    }

    function showEditProduct($id){
        if(!AuthHelper::isLogged()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $product = $this->modelProduct->getProductById($id);
        $categories = $this->modelCategory->getCategories();
        $this->viewProduct->showEditProduct(null,$categories,$product);
    }
}