<?php
session_start(); // Asegúrate de iniciar la sesión al principio

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "fruver_db");

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener los datos del formulario
$usuario = $_POST["usuario"];
$password = $_POST["password"];

// Consulta para verificar el usuario y la contraseña
$query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contraseña = '$password'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    // Si el usuario existe, obtener los datos
    $usuario_data = mysqli_fetch_assoc($resultado);
    $nombre = $usuario_data['nombre'];
    $rol = $usuario_data['rol'];  // Obtén el rol del usuario

    // Almacenar datos en la sesión
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nombre"] = $nombre;
    $_SESSION["rol"] = $rol;  // Almacena el rol en la sesión

    // Respuesta JSON para el login exitoso
    echo json_encode(['success' => true, 'redirect' => 'principal.php']);
} else {
    // Respuesta JSON para el login fallido
    echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
}

// Cerrar la conexión
mysqli_close($conexion);
?>