<?php

class RedirectHelper{
    
    public static function redirectToLogin() {
        header('Location: '  . BASE_URL .  'login');
        exit();
    }

    public static function redirectToHome() {
        header('Location: ' . BASE_URL . 'home');
        exit();
    }

    public static function redirectToProducts(){
        header('Location:' . BASE_URL . 'products');
        exit();
    }

    public static function redirectToCart(){
        header('Location: ' . BASE_URL . 'cart');
        exit();
    }

    public static function redirectToProfile(){
        header('Location: ' . BASE_URL . 'profile');
        exit();
    }

    public static function redirecAdminCategories(){
        header('Location: ' . BASE_URL . 'showCategorias');
        exit();
    }
}