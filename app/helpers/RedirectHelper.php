<?php

class RedirectHelper{
    
    public static function redirectToLogin() {
        header('Location: login');
        exit();
    }

    public static function redirectToHome() {
        header('Location: home');
        exit();
    }
}