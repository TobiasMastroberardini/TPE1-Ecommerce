<?php
require_once 'app/models/Model.php';
class CarritoModel extends Model{

   function getIdCarritoByIdUSer($id_usuario){
    $query = $this->db->prepare("SELECT id_carrito FROM carritos WHERE id_usuario = ?");
    $query->execute([$id_usuario]);
    return $query->fetch(PDO::FETCH_COLUMN); // Retorna un solo valor (id_carrito)
}


    function createCarrito($id_usuario, $fecha_creacion){
        $query = $this->db->prepare("INSERT INTO carritos (id_usuario, fecha_creacion) VALUES (?, ?)");
        $query->execute([$id_usuario, $fecha_creacion]);
    }

    function addItem($id_carrito, $id_producto, $cantidad){
        $query = $this->db->prepare("INSERT INTO items_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)");
        $query->execute([$id_carrito, $id_producto, $cantidad]);
    }

    function getItemsCarrito($id_carrito){
        $query = $this->db->prepare("SELECT * FROM items_carrito WHERE id_carrito = ?");
        $query->execute([$id_carrito]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}