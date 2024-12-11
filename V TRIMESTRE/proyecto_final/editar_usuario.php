<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió una solicitud de actualización
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contraseña'];

    // Actualizar datos del usuario
    $sql_actualizar = "UPDATE usuarios SET nombre=?, usuario=?, contraseña=? WHERE id=?";
    $stmt = $conn->prepare($sql_actualizar);
    $stmt->bind_param("sssi", $nombre, $usuario, $contrasena, $id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Usuario actualizado con éxito.</p>";
    } else {
        echo "<p style='color: red;'>Error al actualizar el usuario: " . $conn->error . "</p>";
    }
}

// Obtener datos del usuario por ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>


