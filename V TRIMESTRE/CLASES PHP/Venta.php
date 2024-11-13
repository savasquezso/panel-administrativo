<?php

class Venta {
    private $id;             
    private $producto;      
    private $cantidad;      
    private $precioUnitario; 
    private $fecha;          


    public function __construct($id, $producto, $cantidad, $precioUnitario, $fecha) {
        $this->id = $id;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->fecha = $fecha;
    }

 
    public function registrarVenta() {
        return "Venta registrada: {$this->cantidad} unidades de {$this->producto} a \$" . $this->precioUnitario . " cada una.";
    }

    public function obtenerTotal() {
        return $this->cantidad * $this->precioUnitario;
    }

    public function getId() {
        return $this->id;
    }

    public function getProducto() {
        return $this->producto;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getPrecioUnitario() {
        return $this->precioUnitario;
    }

    public function getFecha() {
        return $this->fecha;
    }
}