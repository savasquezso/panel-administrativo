<?php
class Login {
    private $username;
    private $password;
    private $loginAttempts = 0;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function autenticar($username, $password) {
        if ($this->username === $username && $this->password === $password) {
            $this->loginAttempts = 0;
            return "Autenticación exitosa.";
        } else {
            $this->loginAttempts++;
            return "Autenticación fallida. Intentos: " . $this->loginAttempts;
        }
    }

    public function resetPassword($email) {
        return "Enlace de restablecimiento enviado a $email.";
    }
}
?>