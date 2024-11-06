<?php
require_once 'config.php';

function obtenerProductos() {
    global $conexion;
    $sql = "SELECT * FROM productos";
    $resultado = mysqli_query($conexion, $sql);
    $productos = [];
    
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
    }
    
    return $productos;
}

function agregarProducto($nombre, $precio, $stock) {
    global $conexion;
    $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sdi", $nombre, $precio, $stock);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

function modificarProducto($id, $nombre, $precio, $stock) {
    global $conexion;
    $sql = "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sdii", $nombre, $precio, $stock, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

function eliminarProducto($id) {
    global $conexion;
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}

function obtenerProductoPorId($id) {
    global $conexion;
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($resultado);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar':
                $nombre = $_POST['nombre'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                if (agregarProducto($nombre, $precio, $stock)) {
                    echo "Producto agregado con éxito";
                } else {
                    echo "Error al agregar el producto";
                }
                break;
            
            case 'modificar':
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                if (modificarProducto($id, $nombre, $precio, $stock)) {
                    echo "Producto modificado con éxito";
                } else {
                    echo "Error al modificar el producto";
                }
                break;
            
            case 'eliminar':
                $id = $_POST['id'];
                if (eliminarProducto($id)) {
                    echo "Producto eliminado con éxito";
                } else {
                    echo "Error al eliminar el producto";
                }
                break;
        }
    }
}

$productos = obtenerProductos();
foreach ($productos as $producto) {
    echo "ID: " . $producto['id'] . ", Nombre: " . $producto['nombre'] . 
         ", Precio: $" . $producto['precio'] . ", Stock: " . $producto['stock'] . "<br>";
}

?>