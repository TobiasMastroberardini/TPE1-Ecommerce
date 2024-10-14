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
    
    // Obtén el resultado como un objeto
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    // Verifica si se encontró el resultado y devuelve el rol como int
    return $result ? (int)$result->rol : null; // Convierte a int y maneja el caso nulo
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

    function createUser($nombre, $email, $contraseña, $fecha_registro){
        $query = $this->db->prepare('INSERT INTO usuarios (nombre, email, contraseña, fecha_registro, rol) VALUES (?,?,?,?,?)');
        $query->execute([$nombre, $email, $contraseña, $fecha_registro, 0]);
        return $this->db->lastInsertId();
    }

    function editUser($usuario_id, $email, $contraseña){
        $query = $this->db->prepare('UPDATE usuarios SET email = ?, contraseña = ? WHERE usuario_id = ?');
        $query->execute([$usuario_id, $email, $contraseña]);
    }

    function deleteUser($usuario_id){
        $query = $this->db->prepare('DELETE * FROM usuarios WHERE usuario_id=?');
        $query->execute([$usuario_id]);
    }
}