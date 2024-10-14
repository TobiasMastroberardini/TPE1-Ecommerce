<?php

class ProductView{
    function showProduct($product){
        require_once "template/product.phtml";
    }

    function showProducts($products){
        require_once "template/productList.phtml";
    }
    function showCreateProduct($message = null,$categories){
        require_once "template/createProduct.phtml";
    }

    function showInicio($products, $cantUsers, $cantProducts, $cantDinero){
        require_once "template/home.phtml";
    }

    function showEditProduct($message = null, $categories, $product){
        require_once "template/editProduct.phtml";
    }

    function showQuickView($product){
        include "template/ProductQuickView.phtml";
    }
}