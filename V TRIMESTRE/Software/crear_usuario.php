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

$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "El usuario ya está registrado. Por favor, use otro.";
} else {

    $password_hash = $contrasena;

    $sql = "INSERT INTO usuarios (nombre, usuario, contraseña) VALUES ('$nombre', '$usuario', '$password_hash')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario registrado con éxito.";
        header("Location: usuario.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

?>