<?php
class SuperAdmin {
    private $usuarios = [];

    public function gestionarUsuarios() {
        return $this->usuarios;
    }

    public function configurarSistema($config) {
        return "Sistema configurado con los siguientes parámetros: " . json_encode($config);
    }

    public function eliminarUsuario($userId) {
        if (isset($this->usuarios[$userId])) {
            unset($this->usuarios[$userId]);
            return "Usuario con ID $userId eliminado.";
        }
        return "Usuario no encontrado.";
    }
}
?>