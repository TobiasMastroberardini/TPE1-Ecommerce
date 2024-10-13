<?php

require_once 'app/models/Model.php';

class ProductModel extends Model{

    function getProducts(){
        $query = $this->db->prepare('SELECT * FROM productos');
        $query->execute([]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getProductById($producto_id){
        $query = $this->db->prepare('SELECT * FROM productos WHERE id_producto  = ?');
        $query->execute([$producto_id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getProductByNombre($nombre){
        $query = $this->db->prepare('SELECT * FROM productos WHERE nombre = ?');
        $query->execute([$nombre]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

     function getProductsByCategoria($categoria){
        $query = $this->db->prepare('SELECT * FROM productos WHERE id_categoria = ?');
        $query->execute([$categoria]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function getSellerId($id_producto){
        $query = $this->db->prepare('SELECT id_vendedor FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        return $query->fetchall(PDO::FETCH_OBJ);
    }

    function getPrecioProducto($id_producto){
        $query = $this->db->prepare('SELECT precio FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->precio;
    }

    function createProduct($id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion) {
        $query = $this->db->prepare('INSERT INTO productos (id_vendedor, id_categoria, nombre, descripcion, precio, imagen, stock, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion]);
   }

    function editProduct($id_producto, $categoria, $nombre, $descripcio, $precio, $imagen, $stock) {
        $query = $this->db->prepare('UPDATE productos SET categoria = ?, nombre = ?, descripcio = ?, precio = ?, imagen = ?, stock = ? WHERE id_producto = ?');
        $query->execute([$id_producto, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $id_producto]);
    }

    function deleteProduct($producto_id){
        $query = $this->db->prepare('DELETE * FROM productos WHERE producto_id=?');
        $query->execute([$producto_id]);
    }

}
