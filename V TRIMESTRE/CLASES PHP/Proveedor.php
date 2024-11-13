<?php

class Proveedor {
    private $id;              
    private $nombre;          
    private $tipo;            
    private $contacto;         
    private $direccion;        
    private $telefono;        
    private $email;           

    public function __construct($id, $nombre, $tipo, $contacto, $direccion, $telefono, $email) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->contacto = $contacto;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
    }

    public function agregarProveedor() {
        return "Proveedor agregado: {$this->nombre} (ID: {$this->id}).";
    }

    public function actualizarProveedor($nombre, $tipo, $contacto, $direccion, $telefono, $email) {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->contacto = $contacto;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        return "Proveedor actualizado: {$this->nombre}.";
    }


    public function eliminarProveedor() {
        return "Proveedor eliminado: {$this->nombre}.";
    }

    public function obtenerInfoProveedor() {
        return [
            'ID' => $this->id,
            'Nombre' => $this->nombre,
            'Tipo' => $this->tipo,
            'Contacto' => $this->contacto,
            'Dirección' => $this->direccion,
            'Teléfono' => $this->telefono,
            'Email' => $this->email,
        ];
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getContacto() {
        return $this->contacto;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getEmail() {
        return $this->email;
    }
}