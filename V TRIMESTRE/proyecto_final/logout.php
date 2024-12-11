<?php
// Iniciar sesión
session_start();

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: login.html");
exit();
?>
