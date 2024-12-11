<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $numero = $_POST['numero'];
    $costo = $_POST['costo'];
    $fecha_compra = $_POST['fecha_compra'];

    // Preparar y ejecutar la consulta SQL para insertar los datos
    $sql = "INSERT INTO ventas (nombre, cedula, numero, costo, fecha_compra) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $cedula, $numero, $costo, $fecha_compra);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Venta agregada con éxito.</p>";
        header("Location: ventas.php?success=1");
    } else {
        echo "<p style='color: red;'>Error al agregar la venta: " . $conn->error . "</p>";
    }

    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
