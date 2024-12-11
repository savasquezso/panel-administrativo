<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar el mensaje
$message = "";

// Recoger los datos del formulario
$nombre = $_POST['nombre'];
$valor_unidad = (float) $_POST['valor_unidad'];
$cantidad = (int) $_POST['cantidad'];
$valor_libra = (float) $_POST['valor_libra'];
$categoria = $_POST['categoria'];

if (isset($_POST['proveedor'])) {
    $id_proveedor = $_POST['proveedor'];
} else {
    die("El campo proveedor no fue enviado.");
}

$fecha_ingreso = date('Y-m-d'); 

// Recoger los nuevos campos de stock
$stock_minimo = (int) $_POST['stock_minimo'];
$stock_maximo = (int) $_POST['stock_maximo'];

// Obtener el nombre del proveedor según el ID
$sql_proveedor = "SELECT empresa FROM proveedores WHERE id = $id_proveedor";
$result_proveedor = $conn->query($sql_proveedor);

if ($result_proveedor->num_rows > 0) {
    $row_proveedor = $result_proveedor->fetch_assoc();
    $nombre_proveedor = $row_proveedor['empresa'];
} else {
    die("Proveedor no encontrado.");
}

// Modificar la consulta SQL para incluir los nuevos campos
$sql = "INSERT INTO stock (nombre, valor_unidad, cantidad, valor_libra, categoria, proveedor, fecha_ingreso, stock_minimo, stock_maximo)
        VALUES ('$nombre', $valor_unidad, $cantidad, $valor_libra, '$categoria', '$nombre_proveedor', '$fecha_ingreso', $stock_minimo, $stock_maximo)";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    $message = "Nuevo producto agregado correctamente.";
    header("Location: producto.php?message=" . urlencode($message));
    exit();
} else {
    $message = "Error: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>