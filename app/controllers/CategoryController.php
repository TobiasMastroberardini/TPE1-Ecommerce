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
        if(AuthHelper::isLogged() && AuthHelper::isAdmin()){
            $nombre = $_GET['nombre'];
            if($nombre){
                $this->categoryModel->createCategory($nombre);
                RedirectHelper::redirectToHome();

            }
        }else{
            RedirectHelper::redirecAdminCategories();
        }
    }

    function editCategory($id_categoria, $nombre){
        if(AuthHelper::isLogged() && AuthHelper::isAdmin()){
            $nombre = $_POST['nombre'];
            if($nombre){
                $this->categoryModel->editCategory($id_categoria,$nombre); 
                RedirectHelper::redirectToHome();
            }
        }else{
            RedirectHelper::redirecAdminCategories();
        }
    }

    function deleteCategory($id_categoria){
        if(AuthHelper::isLogged() && AuthHelper::isAdmin()){
            $products = $this->productCategory->getProductsByCategoria($id_categoria);
            if($products){
                $this->errorView->showError('Debes eliminar todos los productos de una categoria para poder eliminarla');
            }else{
                $this->categoryModel->deleteCategory($id_categoria);
                RedirectHelper::redirecAdminCategories();
            }
        }
    }

    function showCreateCategory(){
        if(AuthHelper::isLogged() && AuthHelper::isAdmin()){
            $this->categoryView->showCreateCategory();
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    function showCategoryList(){
        if(AuthHelper::isLogged() && AuthHelper::isAdmin()){
            $categorias = $this->categoryModel->getCategories();
            $this->categoryView->showCategoryList($categorias);
        }
    }
}