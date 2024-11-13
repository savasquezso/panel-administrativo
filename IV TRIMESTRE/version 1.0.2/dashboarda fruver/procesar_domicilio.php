<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO domicilios (nombre, fecha, genero, edad, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $fecha, $genero, $edad, $estado);

    if ($stmt->execute()) {
        echo "Domicilio agregado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no autorizado";
}
?>

