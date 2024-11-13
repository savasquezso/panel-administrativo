<?php
class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;

    public function __construct($id, $nombre, $descripcion, $precio, $stock) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function agregarProducto() {
        return "Producto $this->nombre agregado con precio $this->precio y stock $this->stock.";
    }

    public function eliminarProducto() {
        return "Producto $this->nombre eliminado.";
    }

    public function verificarStock() {
        return $this->stock > 0 ? "Stock disponible: $this->stock" : "Stock agotado.";
    }
}
?>