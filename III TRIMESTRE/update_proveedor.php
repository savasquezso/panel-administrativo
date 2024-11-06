<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    $sql = "UPDATE proveedores SET nombre='$nombre', tipo='$tipo' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Proveedor actualizado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>