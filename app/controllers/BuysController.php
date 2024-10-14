<?php
require_once 'app/models/BuysModel.php';
require_once 'app/models/CarritoModel.php';

class BuysController {
    private $buysModel;
    private $cartModel;

    public function __construct() {
        $this->buysModel = new BuysModel();
        $this->cartModel = new CarritoModel();
    }

    // Función para gestionar la compra
    public function agregarCompra() {
        $idUsuario = AuthHelper::getLoggedInUserId();

        $idCarrito = $this->cartModel->getIdCarritoByIdUSer($idUsuario);

        $itemsCarrito = $this->buysModel->obtenerItemsCarrito($idCarrito);

        if (empty($itemsCarrito)) {
            RedirectHelper::redirectToProducts();
            exit;
        }

        $total = 0;
        foreach ($itemsCarrito as $item) {
            $total += $item->cantidad * $item->precio;
        }

        $idCompra = $this->buysModel->crearCompra($idUsuario, $total); // La función retornará el ID generado

        foreach ($itemsCarrito as $item) {
            $this->buysModel->agregarDetalleCompra($idCompra, $item->id_producto, $item->cantidad, $item->precio);

            $nuevoStock = $item->stock - $item->cantidad;
            $this->buysModel->actualizarStockProducto($item->id_producto, $nuevoStock);
        }

        $this->buysModel->vaciarCarrito($idCarrito);

        header('Location: compraRealizada');
        exit;
    }
}
