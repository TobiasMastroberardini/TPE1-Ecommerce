<?php

require_once 'app/models/Model.php';

class UserModel extends Model{

    function getUsers(){
        $query = $this->db->prepare('SELECT * FROM usuarios');
        $query->execute([]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getUSer($email){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $query->execute([$email]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getUSerByNombre($nombre){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE nombre=?');
        $query->execute([$nombre]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getIdUser($id_usuario){
        $query = $this->db->prepare('SELECT id_usuario FROM usuarios WHERE id_usuario');
        $query->execute([$id_usuario]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getRolUser($id_usuario) {
        $query = $this->db->prepare('SELECT rol FROM usuarios WHERE id_usuario = ?');
        $query->execute([$id_usuario]);
    
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result ? (int)$result->rol : null; // Convierte a int y maneja el caso nulo
    }

    public function getImageById($usuario_id) {
        $query = $this->db->prepare('SELECT imagen FROM usuarios WHERE id_usuario = ?');
        $query->execute([$usuario_id]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        // Retornar el nombre de la imagen si se encuentra, o null si no
        return $result ? $result->imagen : null;
    }


    function getSellers(){
        $query = $this->db->prepare("
            SELECT u.id_usuario, u.nombre, u.email, u.fecha_registro
            FROM usuarios u
            JOIN productos p ON u.id_usuario = p.id_vendedor
            GROUP BY u.id_usuario
            HAVING COUNT(p.id_producto) > 0
        ");
        $query->execute([]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    function verifyEmailRegistred($email){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email=?');
        $query->execute([$email]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getCantUsers(){
        $query = $this->db->prepare("SELECT COUNT(*) FROM usuarios");
        $query->execute();
        $count = $query->fetchColumn();

        return $count;
    }

    function createUser($nombre, $email, $contraseña, $fecha_registro, $imagen){
        $query = $this->db->prepare('INSERT INTO usuarios (nombre, email, contraseña, fecha_registro, rol, imagen) VALUES (?,?,?,?,?,?)');
        $query->execute([$nombre, $email, $contraseña, $fecha_registro, 0, $imagen]);
        return $this->db->lastInsertId();
    }

    function editUser($usuario_id, $email, $contraseña, $imagen){
        $query = $this->db->prepare('UPDATE usuarios SET email = ?, contraseña = ?, imagen = ? WHERE usuario_id = ?');
        $query->execute([$usuario_id, $email, $contraseña, $imagen]);
    }

    function deleteUser($usuario_id){
        $query = $this->db->prepare('DELETE * FROM usuarios WHERE usuario_id=?');
        $query->execute([$usuario_id]);
    }
}