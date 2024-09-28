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
        $query->execute[$id_usuario];
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function verifyEmailRegistred($email){
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE email=?');
        $query->execute([$email]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function createUser($nombre, $email, $contraseña, $fecha_registro){
        $query = $this->db->prepare('INSERT INTO usuarios (nombre, email, contraseña, fecha_registro) VALUES (?,?,?,?)');
        $query->execute([$nombre, $email, $contraseña, $fecha_registro]);
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