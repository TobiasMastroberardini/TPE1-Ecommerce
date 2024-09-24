<?php
require_once "app/views/ErrorView.php";
require_once "app/models/ProductModel.php";
require_once "app/models/CategoryModel.php";
require_once "app/views/ProductView.php";

class ProductController{
    private $modelProduct;
    private $modelCategory;
    private $viewProduct;
    private $ErrorView;

    public function __construct(){
        $this->modelProduct = new ProductModel();
        $this->viewProduct = new ProductView();
        $this->ErrorView = new ErrorView();
        $this->modelCategory = new CategoryModel();
    }
    
    public function getProduts(){
        $products = $this->modelProduct->getProducts();
        if($products){
            $this->viewProduct->showProducts($products);
        }else{
            $this->ErrorView->showError('Los productos no existen en la base de datos.');
        }
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
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = "5904";
    $stock = $_POST['stock'];

    $id_vendedor = AuthHelper::getLoggedInUserId();
    $fecha_creacion = date('Y-m-d H:i:s');

    // Verifica que todos los campos requeridos estén completos
    if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($imagen) || empty($stock)) {
        // Si faltan campos, muestra un error
        $this->viewProduct->showCreateProduct("Faltan completar campos");
    } else {
        // Llama al modelo para crear el producto con los datos recogidos
        $this->modelProduct->createProduct($id_vendedor, $categoria, $nombre, $descripcion, $precio, $imagen, $stock, $fecha_creacion);
        // Redirige a la página de inicio o muestra un mensaje de éxito
        $this->viewProduct->showInicio("Producto agregado");
    }
}

function createProductm(){
     // Verificar si el usuario está logueado
    $id_vendedor = AuthHelper::getLoggedInUserId();
    if ($id_vendedor === null) {
        // Si el usuario no está autenticado, redirige a la página de login
        header('Location: login');
        exit;
    }

    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = "5904";  // Nota: considera manejar la subida de imágenes en el futuro
    $stock = $_POST['stock'];

    // Usa el ID del usuario de la sesión
    $id_vendedor = $_SESSION['id_usuario'];
    $fecha_creacion = date('Y-m-d H:i:s');

    // Verifica que todos los campos requeridos estén completos
    if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($imagen) || empty($stock)) {
        // Si faltan campos, muestra un error
        $this->viewProduct->showCreateProduct("Faltan completar campos");
    } else {
        // Llama al modelo para crear el producto con los datos recogidos
        $this->modelProduct->createProduct($id_vendedor, $categoria, $nombre, $descripcion, $precio, $imagen, $stock, $fecha_creacion);
        // Redirige a la página de inicio o muestra un mensaje de éxito
        $this->viewProduct->showInicio("Producto agregado");
    }
}


    function editProduct($producto_id){
        $nombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $descripcio = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $imagen = $_POST['imagen'];
        $stock = $_POST['stock'];
        $id_vendedor = $_SESSION['usuario_id'];
        $fecha_creacion = date('Y-m-d H:i:s');

        if (empty($nombre) || empty($categoria) || empty($descripcio) || empty($precio) || empty($imagen) || empty($stock)) {
            $this->viewProduct->showEditProduct("Faltan completar campos");
        }else{
            $this->modelProduct->editProduct($producto_id, $id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock);
            $this->viewProduct->showInicio("Producto modificado");
        }
    }
    
    function deleteProduct($producto_id){
        $this->modelProduct->deleteProduct($producto_id);
    }

    function showCreateProduct(){
        $categories = $this->modelCategory->getCategories();
        $this->viewProduct->showCreateProduct(null,$categories);
    }

    function showEditProduct(){
        $categories = $this->modelCategory->getCategories();
        $this->viewProduct->showEditProduct(null,$categories);
    }
}