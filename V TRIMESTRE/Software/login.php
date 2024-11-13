<?php

$conexion = mysqli_connect("localhost", "root", "", "fruver_db");


if (!$conexion) {
	die("Conexión fallida: " . mysqli_connect_error());
}


$usuario = $_POST["usuario"];
$password = $_POST["password"];


$query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contraseña = '$password'";
$resultado = mysqli_query($conexion, $query);


if (mysqli_num_rows($resultado) > 0) {

	$usuario_data = mysqli_fetch_assoc($resultado);
	$nombre = $usuario_data['nombre'];


	session_start();
	$_SESSION["usuario"] = $usuario;
	$_SESSION["nombre"] = $nombre;
	header("Location: principal.php");
} else {
	echo "Usuario o contraseña incorrectos";
}


mysqli_close($conexion);
?>