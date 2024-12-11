<?php
// Incluir la conexión a la base de datos
include 'db_connection.php';

$message = ''; // Inicializar la variable de mensaje

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
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
        $message = "<div class='alert alert-danger'>Conexión fallida: " . $conn->connect_error . "</div>";
    } else {
        // Consulta para insertar datos en la tabla
        $sql = "INSERT INTO proveedores (nombre, correo, telefono, empresa, direccion, ciudad_municipio, categoria)
                VALUES ('$nombre', '$correo', '$telefono', '$empresa', '$direccion', '$ciudad_municipio', '$categoria')";

        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>Proveedor agregado con éxito.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error al agregar el proveedor: " . $conn->error . "</div>";
        }

        // Cerrar conexión
        $conn->close();
    }
}

// Redirigir de vuelta al formulario con el mensaje
header("Location:proveedor.php?message=" . urlencode($message));


// Manejo de eliminación de proveedor
if (isset($_POST['eliminar_proveedor'])) {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = $_POST['id'];
        
        $sql_eliminar = "DELETE FROM proveedores WHERE id=$id";
        
        if ($conn->query($sql_eliminar) === TRUE) {
            $message = "<div class='alert alert-success'>Proveedor eliminado correctamente.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error al eliminar el proveedor: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>ID del proveedor no válido.</div>";
    }
}

// Redirigir de vuelta al formulario con el mensaje
header("Location: proveedor.php?message=" . urlencode($message));
exit();
?>