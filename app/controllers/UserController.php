<?php
require_once 'app/helpers/AuthHelper.php';
require_once 'app/helpers/RedirectHelper.php';
require_once 'app/models/UserModel.php';
require_once 'app/models/CarritoModel.php';
require_once 'app/views/UserView.php';
require_once 'app/views/ErrorView.php';

class UserController{
    private $userModel;
    private $cartModel;
    private $userView;
    private $errorView;

    function __construct(){
        $this->userModel = new UserModel();
        $this->cartModel = new CarritoModel();
        $this->userView = new UserView();
        $this->errorView = new ErrorView();
    }

    public function showCreateUSer(){
        $this->userView->showCreateUSer();
    }

    public function showUsers(){
        if(AuthHelper::isLogged()){
        $users = $this->userModel->getUsers();
        $this->userView->showUsers($users);
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    public function createUser() {
        $email = $_POST['email'];
        $nombre = $_POST['nombre'];
        $contrasenia = $_POST['contrasenia'];
        $fechaRegistro = date("Y-m-d H:i:s");

        if ($email && $nombre && $contrasenia && $fechaRegistro) {
            
            $registredEmail = $this->userModel->verifyEmailRegistred($email);

            if($registredEmail){
                $this->userView->showCreateUSer("El Email ya exiate");
                die();
            }

            if ($this->isNotPasswordSecure($contrasenia)) {
                die();
            }
            
            // Hashear la contraseña antes de enviarla al modelo
            $hashedPassword = password_hash($contrasenia, PASSWORD_DEFAULT);

            // Crear usuario con la contraseña hasheada
            $user = $this->userModel->createUser($nombre, $email, $hashedPassword, $fechaRegistro);
            $this->cartModel->createCarrito($user, $fechaRegistro);

            RedirectHelper::redirectToLogin();
            exit;
        } else {
            $this->userView->showCreateUSer("Campos incompletos");
        }
    }

    public function editUSer($usuario_id){
        $id = $this->userModel->getIdUser($usuario_id);
        if(AuthHelper::isLogged() && AuthHelper::getLoggedInUserId() == $id){
            $email = $_POST['email'];
            $contrasenia = $_POST['contrasenia'];

            if($email && $contrasenia){
                $this->userModel->editUser($usuario_id, $email, $contrasenia);
            }else{
                $this->userView->showEditUSer("Campos incompletos");
            }
        }else{
            RedirectHelper::redirectToLogin();
        }
    }

    public function deleteUSer($usuario_id){
        if (!AuthHelper::isLogged()) {
            RedirectHelper::redirectToLogin();
            return;
        }
        
        if (AuthHelper::isAdmin() || AuthHelper::getLoggedInUserId() == $usuario_id) {
            $this->userModel->deleteUser($usuario_id);
        }else{
            $this->errorView->showError('No tienes permisos para eliminar este usuario');
        }
    }

    function getSellers(){
        $selles = $this->userModel->getSellers();
        $this->userView->showUsers($selles);
    }

    // Función para validar la contraseña
    private function isNotPasswordSecure($password) {
        $minLength = 8;
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasLowerCase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

        return !(strlen($password) >= $minLength && $hasUpperCase && $hasLowerCase && $hasNumber && $hasSpecialChar);
    }
}
