<?php
require_once "app/models/ProductModel.php";
require_once "app/models/CarritoModel.php";
require_once "app/helpers/AuthHelper.php";
require_once "app/views/CartView.php";


class CartController{
    private $cartModel;
    private $productModel;

    private $view;

    public function __construct(){
        $this->cartModel = new CarritoModel();
        $this->productModel = new ProductModel();
        $this->view = new CartView();
    }

    function addProduct($product_id){
        if (AuthHelper::isLogged()) {
            $id_usuario = AuthHelper::getLoggedInUserId();
            $id_carrito = $this->cartModel->getIdCarritoByIdUSer($id_usuario);
            $cantidad = 1;
            $this->cartModel->addItem($id_carrito,$product_id, $cantidad);
            header('Location: '. BASE_URL. 'products');
        }
    }

    function getCarrito(){
        if(AuthHelper::isLogged()){
            $id_usuario = AuthHelper::getLoggedInUserId();
            $id_carrito = $this->cartModel->getIdCarritoByIdUSer($id_usuario);      

            $items = $this->cartModel->getItemsCarrito($id_carrito);
            $this->view->showCarrito($items);
        }
    }
}