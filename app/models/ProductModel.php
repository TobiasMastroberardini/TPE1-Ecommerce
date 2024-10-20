<?php

require_once 'app/models/Model.php';

class ProductModel extends Model{

    function getProducts() {
        // Consulta con JOIN para traer el nombre de la categoría
        $query = $this->db->prepare('
        SELECT productos.*, categorias.nombre AS categoria_nombre
        FROM productos
        JOIN categorias ON productos.id_categoria = categorias.id_categoria
        WHERE productos.disponible = 1
        ');
        $query->execute();
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

    function getProductsByCategoria($categoria) {
        // Consulta con JOIN para traer el nombre de la categoría
        $query = $this->db->prepare('
        SELECT productos.*, categorias.nombre AS categoria_nombre
        FROM productos
        JOIN categorias ON productos.id_categoria = categorias.id_categoria
        WHERE productos.id_categoria = ?
        ');
        $query->execute([$categoria]);
        return $query->fetchAll(PDO::FETCH_OBJ); // Retorna el resultado como un array de objetos
    }

    function getSellerId($id_producto) {
        $query = $this->db->prepare('SELECT id_vendedor FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getPrecioProducto($id_producto){
        $query = $this->db->prepare('SELECT precio FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->precio;
    }

    function getCantProducts(){
        $query = $this->db->prepare("SELECT COUNT(*) FROM productos WHERE disponible = 1");
        $query->execute();
        $count = $query->fetchColumn();
        return $count; 
    }

    function getCantDinero(){
        $query = $this->db->prepare("SELECT SUM(precio) FROM productos");
        $query->execute();
        $total = $query->fetchColumn();
        return $total ? $total : 0; 
    }

    public function getImageById($id_producto) {
        $query = $this->db->prepare('SELECT imagen FROM productos WHERE id_producto = ?');
        $query->execute([$id_producto]);
    
        $resultado = $query->fetch(PDO::FETCH_OBJ);
    
        return $resultado ? $resultado->imagen : null;
    }

    function createProduct($id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion) {
        $query = $this->db->prepare('INSERT INTO productos (id_vendedor, id_categoria, nombre, descripcion, precio, imagen, stock, fecha_creacion, disponible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$id_vendedor, $categoria, $nombre, $descripcio, $precio, $imagen, $stock, $fecha_creacion, 1]);
   }

    function editProduct($id_producto, $categoria, $nombre, $descripcion, $precio, $imagen, $stock) {
        $query = $this->db->prepare('UPDATE productos SET id_categoria = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, stock = ? WHERE id_producto = ?');
        $query->execute([$categoria, $nombre, $descripcion, $precio, $imagen, $stock, $id_producto]);
    }

    function deleteProduct($producto_id){
        $query = $this->db->prepare('DELETE FROM productos WHERE id_producto=?');
        $query->execute([$producto_id]);
    }

    function disableProduct($id_producto){
        $query = $this->db->prepare('UPDATE productos SET disponible = 0 WHERE id_producto = ?');
        $query->execute([$id_producto]);
    }

    function enableProduct($id_producto){
        $query = $this->db->prepare('UPDATE productos SET disponible = 1 WHERE id_producto = ?');
        $query->execute([$id_producto]);
    }
}
