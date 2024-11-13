<?php
class Account {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $rol;
    private $estado = true;

    public function __construct($id, $nombre, $email, $password, $rol) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function crearUsuario($nombre, $email, $password, $rol) {
        return "Usuario '$nombre' creado con rol '$rol'.";
    }

    public function modificarUsuario($id, $nuevoNombre) {
        if ($this->id === $id) {
            $this->nombre = $nuevoNombre;
            return "Usuario con ID $id modificado a '$nuevoNombre'.";
        }
        return "Usuario no encontrado.";
    }

    public function autenticarUsuario($email, $password) {
        if ($this->email === $email && $this->password === $password) {
            return "Autenticación exitosa.";
        }
        return "Autenticación fallida.";
    }
}
?>

