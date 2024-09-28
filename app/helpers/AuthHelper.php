<?php

class AuthHelper{
    public static function init(){
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    public static function login($user){
    AuthHelper::init();
    
    // Mostrar la estructura del objeto $user para verificar los nombres de las propiedades
    var_dump($user); // O puedes usar print_r($user) si prefieres

    $_SESSION['USER_USERNAME'] = $user->email;
    $_SESSION['USER_ID'] = $user->id_usuario; // Asegúrate de que este sea el nombre correcto
}


    public static function logout(){
        AuthHelper::init();
        session_destroy();
    }

    public static function isLogged(){
        AuthHelper::init();
        return isset($_SESSION['USER_USERNAME']);
    }

     public static function getLoggedInUserId() {
        AuthHelper::init();
        // Verificar si existe un ID de usuario en la sesión
        if (isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])) {
            return (int)$_SESSION['id_usuario'];
        }

        return null;
    }

    public static function isAdmin() {
        $userModel = new UserModel();
        $rol = $userModel->getRolUser(self::getLoggedInUserId());
        return ($rol == 1);
    }

    public static function isUserLoggedIn() {
        return self::getLoggedInUserId() !== null;
    }

    public static function redirectToLogin() {
        header('Location: login');
        exit();
    }
}