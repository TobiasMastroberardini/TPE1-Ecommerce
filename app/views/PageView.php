<?php

class PageView{
    function showLogin($message){
        require_once 'template/login.phtml';
    }

    function showHome($products, $cantUsers, $cantProducts, $cantDinero){
        require_once 'template/home.phtml';
    }
}