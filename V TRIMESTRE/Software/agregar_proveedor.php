<?php
include 'db_connection.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $empresa = $_POST['empresa'];
    $direccion = $_POST['direccion'];
    $ciudad_municipio = $_POST['ciudad_municipio'];
    $categoria = $_POST['categoria'];

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para insertar datos en la tabla
    $sql = "INSERT INTO proveedores (nombre, correo, telefono, empresa, direccion, ciudad_municipio, categoria)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $correo, $telefono, $empresa, $direccion, $ciudad_municipio, $categoria);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Proveedor agregado con éxito.</p>";
        header("Location: proveedor.php?success=1");
    } else {
        echo "<p style='color: red;'>Error al agregar el proveedor: " . $conn->error . "</p>";
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
}
?>
