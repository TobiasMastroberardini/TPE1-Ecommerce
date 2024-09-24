<?php

require_once 'app/models/UserModel.php';
require_once 'app/views/UserView.php';

class UserController{
    private $userModel;
    private $userView;

    function __construct(){
        $this->userModel = new UserModel();
        $this->userView = new UserView();
    }

    public function showCreateUSer(){
        $this->userView->showCreateUSer();
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
            $this->userModel->createUser($nombre, $email, $hashedPassword, $fechaRegistro);
            
            header('Location: login');
            exit;
        } else {
            $this->userView->showCreateUSer("Campos incompletos");
        }
    }

    public function editUSer($usuario_id){
        $email = $_POST['email'];
        $contrasenia = $_POST['contrasenia'];

        if($email && $contrasenia){
            $this->userModel->editUser($usuario_id, $email, $contrasenia);
        }else{
            $this->userView->showEditUSer("Campos incompletos");
        }
    }

    public function deleteUSer($usuario_id){
            $this->userModel->deleteUser($usuario_id);
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
