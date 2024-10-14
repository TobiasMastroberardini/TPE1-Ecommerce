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
    private $modelUser;
    private $modelCategory;
    private $viewProduct;
    private $ErrorView;

    public function __construct() {
        $this->modelProduct = new ProductModel();
        $this->modelUser = new UserModel();
        $this->modelCategory = new CategoryModel();
        $this->viewProduct = new ProductView();
        $this->ErrorView = new ErrorView();
    }
    
    public function getProduts($params) {
        if ($params) {
            $products = $this->modelProduct->getProductsByCategoria($params);
        }else {
            $products = $this->modelProduct->getProducts();
        }
        $this->viewProduct->showProducts($products);
    }
    
    public function getProductsByCategory($categoria){
        $products = $this->modelProduct->getProductsByCategoria($categoria);
        $this->viewProduct->showProducts($products);
    }

    public function getProductsBySeller($vendedor_id){
        $products = $this->modelProduct->getProductsByCategoria($vendedor_id);
        $this->viewProduct->showProducts($products);
    }
    
    public function createProduct(){
        if(AuthHelper::isLogged()){
            $nombre = $_POST['nombre'];
            $categoria = $_POST['categoria'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $imagen = "7";
            $stock = $_POST['stock'];
            $id_vendedor = AuthHelper::getLoggedInUserId();
            $fecha_creacion = date('Y-m-d H:i:s');
            
            if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($imagen) || empty($stock)) {
                $this->viewProduct->showCreateProduct("Faltan completar campos",  null);
            } else {
                $this->modelProduct->createProduct($id_vendedor, $categoria, $nombre, $descripcion, $precio, $imagen, $stock, $fecha_creacion);
                RedirectHelper::redirectToProducts();
            }
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    public function editProduct($producto_id){
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
                RedirectHelper::redirectToHome();
            }
        }else{
            RedirectHelper::redirectToLogin();
        }
    }
    
    public function disableProduct($product_id){
        if(AuthHelper::isLogged()){
            $id_vendedor = $this->modelProduct->getSellerId($product_id);
            if(AuthHelper::getLoggedInUserId() == $id_vendedor || AuthHelper::isAdmin()){
                $this->modelProduct->disableProduct($product_id);
                RedirectHelper::redirectToProducts();
            }else{
                RedirectHelper::redirectToLogin();
            }
        }
        RedirectHelper::redirectToProducts();
    }

    public function enableProduct($product_id){
        if(AuthHelper::isLogged()){
            $id_vendedor = $this->modelProduct->getSellerId($product_id);
            if(AuthHelper::getLoggedInUserId() == $id_vendedor || AuthHelper::isAdmin()){
                $this->modelProduct->enableProduct($product_id);
            }else{
                RedirectHelper::redirectToLogin();
            }
        }
        RedirectHelper::redirectToProducts();
    }

    public function deleteProduct($product_id){
        if(AuthHelper::isLogged()){
            $id_vendedor = $this->modelProduct->getSellerId($product_id);
            if(AuthHelper::getLoggedInUserId() == $id_vendedor || AuthHelper::isAdmin()){
                $this->modelProduct->deleteProduct($product_id);
            }else{
                RedirectHelper::redirectToLogin();
            }
        }
        RedirectHelper::redirectToProducts();
    }

    public function showQuickView($id_producto) {
        $producto = $this->modelProduct->getProductById($id_producto);
        $this->viewProduct->showQuickView($producto);
    }

    public function showCreateProduct() {
        if(AuthHelper::isLogged()){
            $categories = $this->modelCategory->getCategories();
            $this->viewProduct->showCreateProduct(null, $categories);
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    public function showEditProduct($id) {
        $product = $this->modelProduct->getProductById($id);
        $categories = $this->modelCategory->getCategories();
        $this->viewProduct->showEditProduct(null, $categories, $product);
    }
}