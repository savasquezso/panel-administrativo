<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];  // Obtiene el valor del rol
$codigo = $_POST['codigo']; // Obtiene el código único

// Verifica si el usuario ya existe
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "El usuario ya está registrado. Por favor, use otro.";
} else {
    // Verifica si se está creando un usuario administrador y si el código es correcto
    if ($rol === "administrador" && $codigo !== "ADMIN") {
        echo "Código único incorrecto. No se puede crear un usuario administrador.";
        exit(); // Detiene la ejecución si el código es incorrecto
    }

    // Inserta los datos del nuevo usuario, incluyendo el rol
    $sql = "INSERT INTO usuarios (nombre, usuario, contraseña, rol) VALUES ('$nombre', '$usuario', '$contrasena', '$rol')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario registrado con éxito.";
        header("Location: usuario.php");  // Redirige a la página de usuarios
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

?>