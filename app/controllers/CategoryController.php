<?php
require_once "app/models/CategoryModel.php";
require_once "app/models/ProductModel.php";
require_once "app/views/ErrorView.php";
require_once "app/views/CategoryView.php";

class CategoryController{
    private $categoryModel;
    private $categoryView;
    private $productCategory;
    private $errorView;


    public function __construct() {
        $this->categoryModel = new CategoryModel();
        $this->productCategory = new ProductModel();
        $this->categoryView = new CategoryView();
        $this->errorView = new ErrorView();
    }

    function getCategories(){
        $categories = $this->categoryModel->getCategories();
        return $categories ? $categories : ["No se encontraron categorias"]; 
    }

    function createCategory($nombre){
        $nombre = $_GET['nombre'];
        if($nombre){
            $this->categoryModel->createCategory($nombre);
        }
    }

    function editCategory($id_categoria, $nombre){
        $nombre = $_POST['nombre'];
        if($nombre){
            $this->categoryModel->editCategory($id_categoria,$nombre); 
        }
    }

    function deleteCategory($id_categoria){
        $products = $this->productCategory->getProductsByCategoria($id_categoria);
        if($products){
            $this->errorView->showError('Debes eliminar todos los productos de una categoria para poder eliminarla');
            die();
        }
        $this->categoryModel->deleteCategory($id_categoria);
    }

    function showCreateCategory(){
        $this->categoryView->showCreateCategory();
    }
}