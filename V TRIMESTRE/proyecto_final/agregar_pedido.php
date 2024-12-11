<?php
session_start(); // Iniciar la sesión
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_proveedor = $_POST['id_proveedor'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['estado'];
    $id_producto = $_POST['id_producto']; // Asegúrate de que este campo esté en el formulario
    $costo = $_POST['costo']; // Obtener el costo del formulario

    // Verificar que el producto existe y obtener su stock actual y stock máximo
    $sql_check = "SELECT cantidad, stock_maximo FROM stock WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_producto);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $producto = $result_check->fetch_assoc();
        $cantidad_actual = $producto['cantidad'];
        $stock_maximo = $producto['stock_maximo'];

        // Verifica si el pedido excede el stock máximo
        if (($cantidad_actual + $cantidad) > $stock_maximo) {
            $_SESSION['message'] = "Error: El pedido excede el stock máximo permitido."; // Guardar mensaje de error
        } else {
            // Insertar el pedido en la base de datos
            $sql = "INSERT INTO pedidos_proveedor (id_proveedor, categoria, id_producto, cantidad, costo, estado) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issiis", $id_proveedor, $categoria, $id_producto, $cantidad, $costo, $estado);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Pedido creado con éxito."; // Guardar mensaje de éxito
            } else {
                $_SESSION['message'] = "Error al crear el pedido: " . $conn->error; // Guardar mensaje de error
            }
        }
    } else {
        $_SESSION['message'] = "Error: El producto no existe."; // Guardar mensaje de error
    }

    $stmt_check->close();
    $conn->close();

    // Redirigir a la página donde se mostrarán los mensajes
    header("Location: producto.php"); // Cambia esto a la página donde quieres mostrar el mensaje
    exit();
}
?>