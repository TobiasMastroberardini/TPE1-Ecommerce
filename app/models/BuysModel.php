<?php

class BuysModel extends Model {
    public function crearCompra($idUsuario, $total) {
        $query = $this->db->prepare("INSERT INTO compras (id_usuario, fecha_compra, total) VALUES (?, NOW(), ?)");
        $query->execute([$idUsuario, $total]);
        return $this->db->lastInsertId();
    }

    public function agregarDetalleCompra($idCompra, $idProducto, $cantidad, $precioUnitario) {
        $query = $this->db->prepare("INSERT INTO detalles_compra (id_compra, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $query->execute([$idCompra, $idProducto, $cantidad, $precioUnitario]);
    }

    public function obtenerItemsCarrito($idCarrito) {
        $query = $this->db->prepare("SELECT i.id_producto, i.cantidad, p.precio, p.stock 
              FROM items_carrito i 
              JOIN productos p ON i.id_producto = p.id_producto 
              WHERE i.id_carrito = ?");
        $query->execute([$idCarrito]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function vaciarCarrito($idCarrito) {
        $query = $this->db->prepare("DELETE FROM items_carrito WHERE id_carrito = ?");
        $query->execute([$idCarrito]);
    }

    public function actualizarStockProducto($idProducto, $nuevaCantidad) {
        $query = $this->db->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        $query->execute([$nuevaCantidad, $idProducto]);
    }
}
