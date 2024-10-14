<?php

class ProductView{
    function showProducts($products, $categorias){
        require_once "template/productList.phtml";
    }
    function showCreateProduct($message = null,$categories){
        require_once "template/createProduct.phtml";
    }

    function showEditProduct($message = null, $categories, $product){
        require_once "template/editProduct.phtml";
    }

    function showQuickView($product){
        include "template/ProductQuickView.phtml";
    }
}