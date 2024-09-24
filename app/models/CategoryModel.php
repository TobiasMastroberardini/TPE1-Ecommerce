<?php

require_once 'app/models/Model.php';

class CategoryModel extends Model{

    function getCategories(){
        $query = $this->db->prepare('SELECT * FROM categorias');
        $query->execute([]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function createCategory($nombre){
        $query = $this->db->prepare('INSERT INTO categorias (nombre) VALUES (?)');
        $query->execute([$nombre]);
    }

    function editCategory($id_categoria, $nombre){
        $query = $this->db->prepare('UPDATE categorias SET nombre = ? WHERE id_categoria');
        $query->execute([$id_categoria, $nombre]);
    }

    function deleteCategory($id_categoria){
        $query = $this->db->prepare('DELETE * FROM categorias WHERE id_categoria = ?');
        $query->execute([]);
    }
}