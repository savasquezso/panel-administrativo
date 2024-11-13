<?php
class Orden {
    private $id;
    private $fecha;
    private $productos = [];
    private $total = 0.0;
    private $estado = "En proceso";
    private $proveedorId;

    public function __construct($id, $fecha, $proveedorId) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->proveedorId = $proveedorId;
    }

    public function agregarProducto($producto, $cantidad) {
        $this->productos[$producto] = $cantidad;
        return "Producto $producto agregado con cantidad $cantidad.";
    }

    public function calcularTotal() {
        $this->total = array_sum(array_map(function($cantidad) {
            return $cantidad * 10;
        }, $this->productos));
        return "Total de la orden: $this->total";
    }

    public function cambiarEstado($nuevoEstado) {
        $this->estado = $nuevoEstado;
        return "Estado de la orden cambiado a $nuevoEstado.";
    }
}
?>