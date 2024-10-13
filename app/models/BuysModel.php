<?php

class BuysModel extends Model {
    // Crear compra
    public function crearCompra($idUsuario, $total) {
        // La consulta para insertar la compra
        $query = $this->db->prepare("INSERT INTO compras (id_usuario, fecha_compra, total) VALUES (?, NOW(), ?)");
        // Ejecutar la consulta con los valores proporcionados
        $query->execute([$idUsuario, $total]);

        // Obtener el ID de la compra recién creada utilizando lastInsertId
        return $this->db->lastInsertId();
    }

    // Agregar detalle de la compra
    public function agregarDetalleCompra($idCompra, $idProducto, $cantidad, $precioUnitario) {
        $query = $this->db->prepare("INSERT INTO detalles_compra (id_compra, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        // Aquí deben pasarse los valores en el mismo orden de los placeholders `?`
        $query->execute([$idCompra, $idProducto, $cantidad, $precioUnitario]);
    }

    // Obtener los items del carrito
    public function obtenerItemsCarrito($idCarrito) {
    $query = $this->db->prepare("SELECT i.id_producto, i.cantidad, p.precio, p.stock 
              FROM items_carrito i 
              JOIN productos p ON i.id_producto = p.id_producto 
              WHERE i.id_carrito = ?");
    $query->execute([$idCarrito]);
    return $query->fetchAll(PDO::FETCH_OBJ);
}


    // Vaciar carrito
    public function vaciarCarrito($idCarrito) {
        $query = $this->db->prepare("DELETE FROM items_carrito WHERE id_carrito = ?");
        // Pasar el valor del ID del carrito al placeholder
        $query->execute([$idCarrito]);
    }

    // Actualizar stock del producto
    public function actualizarStockProducto($idProducto, $nuevaCantidad) {
        $query = $this->db->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        // Pasar el nuevo stock y el ID del producto al placeholder
        $query->execute([$nuevaCantidad, $idProducto]);
    }
}
