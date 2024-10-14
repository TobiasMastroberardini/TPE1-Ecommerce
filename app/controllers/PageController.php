<?php
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/models/UserModel.php';
require_once "app/views/ProductView.php";
require_once "app/views/UserView.php";
require_once "app/views/PageView.php";
require_once "app/views/ErrorView.php";

class PageController {
    private $modelProduct;
    private $modelUser;
    private $modelCategory;
    private $viewProduct;
    private $ErrorView;
    private $pageView;

    public function __construct() {
        $this->modelProduct = new ProductModel();
        $this->modelUser = new UserModel();
        $this->modelCategory = new CategoryModel();
        $this->viewProduct = new ProductView();
        $this->ErrorView = new ErrorView();
        $this->pageView = new PageView();
    }

    public function showHome() {
        $products = $this->modelProduct->getProducts();
        $cantUsers = $this->modelUser->getCantUsers();
        $cantProducts = $this->modelProduct->getCantProducts();
        $cantDinero = $this->modelProduct->getCantDinero();
        $this->pageView->showHome($products, $cantUsers, $cantProducts, $cantDinero);
    }

    public function showLogin($message = null){
        $this->pageView->showLogin($message);
    }
}
