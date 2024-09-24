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

    function showInicio($message = null){
        require_once "template/myProductList.phtml";
    }

    function showEditProduct($message = null, $categories){
        require_once "template/editProduct.phtml";
    }
}