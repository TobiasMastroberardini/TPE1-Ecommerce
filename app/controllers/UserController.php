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
    $imagen = $_FILES['imagen']['name']; // Obtener el nombre de la imagen

    if ($email && $nombre && $contrasenia && $fechaRegistro && $imagen) {
        
        $registredEmail = $this->userModel->verifyEmailRegistred($email);

        if($registredEmail) {
            $this->userView->showCreateUSer("El Email ya existe");
            die();
        }

        if ($this->isNotPasswordSecure($contrasenia)) {
            die();
        }
        
        // Hashear la contraseña antes de enviarla al modelo
        $hashedPassword = password_hash($contrasenia, PASSWORD_DEFAULT);

        // Crear usuario con la contraseña hasheada
        $user = $this->userModel->createUser($nombre, $email, $hashedPassword, $fechaRegistro, $imagen);
        $this->cartModel->createCarrito($user, $fechaRegistro);

        // Mover la imagen al directorio de imágenes
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], 'images/' . $imagen)) {
            // La imagen se ha subido correctamente
            RedirectHelper::redirectToLogin();
            exit;
        } else {
            // Manejo de error en la carga de la imagen
            $this->userView->showCreateUSer("Error al cargar la imagen");
        }
    } else {
        $this->userView->showCreateUSer("Campos incompletos");
    }
}


    public function editUser($usuario_id) {
    $id = $this->userModel->getIdUser($usuario_id);
    if (AuthHelper::isLogged() && AuthHelper::getLoggedInUserId() == $id) {
        $email = $_POST['email'];
        $contrasenia = $_POST['contrasenia'];
        $imagen = $_FILES['imagen']['name']; // Obtener el nombre de la imagen (si se sube una nueva)

        // Verificar si se proporciona un nuevo email y contraseña
        if ($email && $contrasenia) {
            // Si hay una imagen, primero la subimos
            if (!empty($imagen)) {
                // Mover la nueva imagen al directorio de imágenes
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], 'images/' . $imagen)) {
                    // Actualizar usuario con la nueva imagen
                    $this->userModel->editUser($usuario_id, $email, $contrasenia, $imagen);
                } else {
                    $this->userView->showEditUSer("Error al cargar la imagen");
                    return; // Detener la ejecución si hay un error
                }
            } else {
                $imagenActual = $this->userModel->getImageById($id);
                // Si no se proporciona una nueva imagen, solo actualizamos el email y la contraseña
                $this->userModel->editUser($usuario_id, $email, $contrasenia, $imagenActual);
            }
            RedirectHelper::redirectToProfile(); // Redirigir a la página de perfil
        } else {
            $this->userView->showEditUSer("Campos incompletos");
        }
    } else {
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
