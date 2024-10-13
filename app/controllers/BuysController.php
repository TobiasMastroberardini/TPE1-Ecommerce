<?php
require_once 'app/models/BuysModel.php';
require_once 'app/models/CarritoModel.php';
class BuysController {
    private $buysModel;
    private $cartModel;

    public function __construct() {
        $this->buysModel = new BuysModel(); // Instancia de BuysModel
        $this->cartModel = new CarritoModel(); // Instancia de CarritoModel
    }

    // Función para gestionar la compra
    public function agregarCompra() {
        // Obtener ID del usuario logueado
        $idUsuario = AuthHelper::getLoggedInUserId();

        // Obtener el carrito del usuario
        $idCarrito = $this->cartModel->getIdCarritoByIdUser($idUsuario);

        // Obtener los ítems del carrito
        $itemsCarrito = $this->buysModel->obtenerItemsCarrito($idCarrito);

        // Calcular el total de la compra
        $total = 0;
        foreach ($itemsCarrito as $item) {
            $total += $item->cantidad * $item->precio;
        }

        // Crear la compra y obtener el ID generado
        $idCompra = $this->buysModel->crearCompra($idUsuario, $total);  // La función retornará el ID generado

        // Agregar los detalles de la compra
        foreach ($itemsCarrito as $item) {
            $this->buysModel->agregarDetalleCompra($idCompra, $item->id_producto, $item->cantidad, $item->precio);
            // Actualizar el stock de los productos
            $nuevoStock = $item->stock - $item->cantidad;
            $this->buysModel->actualizarStockProducto($item->id_producto, $nuevoStock);
        }

        // Vaciar el carrito
        $this->buysModel->vaciarCarrito($idCarrito);

        // Devolver una respuesta o redirigir
        echo "Compra realizada exitosamente";
    }
}
