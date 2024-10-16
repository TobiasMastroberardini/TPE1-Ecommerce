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
        $categorias = $this->modelCategory->getCategories();
        $this->viewProduct->showProducts($products, $categorias);
    }
    
    public function getProductsByCategory($categoria){
        $products = $this->modelProduct->getProductsByCategoria($categoria);
        $categories  = $this->modelCategory->getCategories();
        $this->viewProduct->showProducts($products, $categories);
    }

    public function getProductsBySeller($vendedor_id){
        $products = $this->modelProduct->getProductsByCategoria($vendedor_id);
        $categories  = $this->modelCategory->getCategories();
        $this->viewProduct->showProducts($products, $categories);
    }
    
   public function createProduct() {
    if (AuthHelper::isLogged()) {
        $nombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $id_vendedor = AuthHelper::getLoggedInUserId();
        $fecha_creacion = date('Y-m-d H:i:s');

        // Manejo de la carga de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen']['name'];
            $target_dir = "images/"; // Directorio donde se guardarán las imágenes
            $target_file = $target_dir . basename($imagen); // Ruta completa del archivo

            // Verificar el tipo de archivo
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']; // Tipos de archivo permitidos

            if (in_array($fileType, $allowedTypes)) {
                // Generar un nombre único para la imagen
                $uniqueImageName = uniqid() . '.' . $fileType; // Generar un ID único
                $destination = $target_dir . $uniqueImageName;

                // Mover el archivo cargado al directorio
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destination)) {
                    // Guardar el producto en la base de datos
                    if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($stock)) {
                        $this->viewProduct->showCreateProduct("Faltan completar campos", null);
                    } else {
                        $this->modelProduct->createProduct($id_vendedor, $categoria, $nombre, $descripcion, $precio, $uniqueImageName, $stock, $fecha_creacion);
                        RedirectHelper::redirectToProducts();
                    }
                } else {
                    $this->viewProduct->showCreateProduct("Error al cargar la imagen", null);
                }
            } else {
                $this->viewProduct->showCreateProduct("Solo se permiten archivos JPG, JPEG, PNG y GIF.", null);
            }
        } else {
            $this->viewProduct->showCreateProduct("No se ha cargado ninguna imagen.", null);
        }
    } else {
        RedirectHelper::redirectToLogin();
    }
  }

    public function editProduct($producto_id) {
    $vendedor = $this->modelProduct->getSellerId($producto_id);
    if (AuthHelper::isLogged() && AuthHelper::getLoggedInUserId() == $vendedor->id_vendedor) {  // Acceder a la propiedad id_vendedor
        $nombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        // Inicializamos la variable imagen
        $imagen = $_FILES['imagen']['name'];

        if (empty($nombre) || empty($categoria) || empty($descripcion) || empty($precio) || empty($stock)) {
            $this->viewProduct->showEditProduct("Faltan completar campos");
        } else {
            // Solo movemos el archivo si se ha subido una nueva imagen
            if (!empty($imagen)) {
                $imagenTempPath = $_FILES['imagen']['tmp_name'];
                $destinoPath = 'images/' . $imagen; // Ruta donde se guardará la imagen

                // Verificamos si el archivo se mueve correctamente
                if (!move_uploaded_file($imagenTempPath, $destinoPath)) {
                    $this->viewProduct->showEditProduct("Error al cargar la imagen.");
                    return; // Salimos de la función si hay error
                }
            } else {
                // Si no hay nueva imagen, podemos mantener la imagen existente
                // Se podría agregar lógica para obtener la imagen existente de la base de datos
                $imagen = $this->modelProduct->getImageById($producto_id); // Método hipotético para obtener la imagen existente
            }

            // Ahora llamamos a la función de edición en el modelo
            $this->modelProduct->editProduct($producto_id, $categoria, $nombre, $descripcion, $precio, $imagen, $stock);
            RedirectHelper::redirectToHome();
        }
    } else {
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