<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'fruteria');


$conexion = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conexion === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}


mysqli_set_charset($conexion, "utf8");

?>