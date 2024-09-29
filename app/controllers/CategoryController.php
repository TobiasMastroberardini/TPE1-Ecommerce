<?php
require_once 'app/helpers/RedirectHelper.php';
require_once 'app/helpers/AuthHelper.php';
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
        if(!AuthHelper::isLogged() && !AuthHelper::isAdmin()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $nombre = $_GET['nombre'];
        if($nombre){
            $this->categoryModel->createCategory($nombre);
        }
        RedirectHelper::redirectToHome();
    }

    function editCategory($id_categoria, $nombre){
        if(!AuthHelper::isLogged() && !AuthHelper::isAdmin()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $nombre = $_POST['nombre'];
        if($nombre){
            $this->categoryModel->editCategory($id_categoria,$nombre); 
        }
        RedirectHelper::redirectToHome();
    }

    function deleteCategory($id_categoria){
        if(!AuthHelper::isLogged() && !AuthHelper::isAdmin()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $products = $this->productCategory->getProductsByCategoria($id_categoria);
        if($products){
            $this->errorView->showError('Debes eliminar todos los productos de una categoria para poder eliminarla');
            die();
        }
        $this->categoryModel->deleteCategory($id_categoria);
    }

    function showCreateCategory(){
        if(!AuthHelper::isLogged() && !AuthHelper::isAdmin()){
            RedirectHelper::redirectToLogin();
            return;
        }
        $this->categoryView->showCreateCategory();
    }
}