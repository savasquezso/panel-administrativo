<?php
class AnalisisProveedor {
    private $proveedorId;
    private $totalCompras;
    private $cantidadCompras;

    public function __construct($proveedorId, $totalCompras = 0, $cantidadCompras = 0) {
        $this->proveedorId = $proveedorId;
        $this->totalCompras = $totalCompras;
        $this->cantidadCompras = $cantidadCompras;
    }

    public function calcularPromedioGasto() {
        if ($this->cantidadCompras > 0) {
            $promedio = $this->totalCompras / $this->cantidadCompras;
            return "El promedio de gasto en compras con el proveedor ID $this->proveedorId es: " . number_format($promedio, 2);
        } else {
            return "No hay compras registradas con el proveedor ID $this->proveedorId.";
        }
    }

    public function mostrarPromedio() {
        return $this->calcularPromedioGasto();
    }
}
?>