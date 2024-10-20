<?php

require_once 'app/views/AuthView.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/AuthHelper.php';



class AuthController{

    private $modelUser;
    private $viewUser;

      public function __construct() {
        $this->viewUser = new AuthView();
        $this->modelUser = new UserModel();
    }

    public function showLogin($message = null){
        if(!AuthHelper::isLogged()){
            $this->viewUser->showLogin($message);
        }else{
            header('Location: ' . BASE_URL);
        }
    }
    public function auth(){
        if(empty($_POST['email']) || empty($_POST['contrasenia'])){
            die();
        }

        $email = $_POST['email'];
        $user = $this->modelUser->getUSer($email);

        if(empty($user)){
            $this->showLogin("El usuario no fue encontrado");
            die();
        }

        if(!password_verify($_POST['contrasenia'],$user->contraseña)){
            $this->showLogin('La contraseña es incorrecta');
            die();
        }else{
            AuthHelper::login($user);
            header('Location: products');
        }
    }

    public function logout(){
        if(AuthHelper::isLogged()){
            AuthHelper::logout();
        }
        header('Location: products');
    }
}