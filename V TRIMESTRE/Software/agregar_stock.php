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

$nombre = $_POST['nombre'];
$valor_unidad = $_POST['valor_unidad'];
$cantidad = $_POST['cantidad'];
$valor_libra = $_POST['valor_libra'];
$categoria = $_POST['categoria'];
$proveedor = $_POST['proveedor'];
$fecha_ingreso = date('Y-m-d'); 
$id_usuario = ['id_usuario'];


$sql = "INSERT INTO stock (nombre, valor_unidad, cantidad, valor_libra, categoria, proveedor, fecha_ingreso,id_usuario)
        VALUES ('$nombre', '$valor_unidad', '$cantidad', '$valor_libra', '$categoria', '$proveedor', '$fecha_ingreso',$id_usuario)";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo producto agregado correctamente";
    header("Location: producto.php?success=1");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
