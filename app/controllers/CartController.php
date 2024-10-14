<?php
require_once "app/models/ProductModel.php";
require_once "app/models/CarritoModel.php";
require_once "app/helpers/AuthHelper.php";
require_once "app/views/CartView.php";

class CartController {
    private $cartModel;
    private $productModel;
    private $view;

    public function __construct() {
        $this->cartModel = new CarritoModel();
        $this->productModel = new ProductModel();
        $this->view = new CartView();
    }

    function addProduct($product_id) {
        if (AuthHelper::isLogged()) {
            $id_usuario = AuthHelper::getLoggedInUserId();
            $id_carrito = $this->cartModel->getIdCarritoByIdUser($id_usuario);
            $cantidad = 1;
            $this->cartModel->addItem($id_carrito, $product_id, $cantidad);
            RedirectHelper::redirectToProducts();
        } else {
            RedirectHelper::redirectToLogin();
        }
    }

    function getCarrito() {
        if (AuthHelper::isLogged()) {
            $id_usuario = AuthHelper::getLoggedInUserId();
            $id_carrito = $this->cartModel->getIdCarritoByIdUser($id_usuario);
            $idItems = $this->cartModel->getItemsCarrito($id_carrito); // Asegúrate de que esta función incluye detalles del producto

            return $idItems;
        } else {
            RedirectHelper::redirectToLogin();
        }
    }

    function removeItem($idItem) {
        if (AuthHelper::isLogged()) {
            $this->cartModel->removeItem($idItem);
            RedirectHelper::redirectToCart();
        }
    }

    function getProductosCarrito() {
        $productos = $this->getCarrito();
        $precio = $this->getPrecioCarrito();
        $this->view->showCarrito($productos, $precio);
    }

    function getPrecioCarrito() {
        $productos = $this->getCarrito();
        $precio = 0;

        foreach ($productos as $producto) {
            $precio += $producto->precio;
        }

        return $precio;
    }
}
