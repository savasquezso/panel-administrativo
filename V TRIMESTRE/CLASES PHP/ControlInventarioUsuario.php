<?php
class ControlInventarioUsuario {
    private $inventario = [];

    public function ajustarStock($productoId, $nuevoStock) {
        if (isset($this->inventario[$productoId])) {
            $this->inventario[$productoId]['stock'] = $nuevoStock;
            return "Stock ajustado para el producto ID $productoId.";
        }
        return "Producto no encontrado en el inventario.";
    }

    public function consultarInventario() {
        return $this->inventario;
    }

    public function agregarProducto($productoId, $nombre, $stock) {
        $this->inventario[$productoId] = ['nombre' => $nombre, 'stock' => $stock];
        return "Producto $nombre agregado al inventario.";
    }
}
?>