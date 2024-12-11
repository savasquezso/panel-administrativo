<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $sql_producto = "SELECT id_producto, cantidad FROM pedidos_proveedor WHERE id_pedido=?";
    $stmt_producto = $conn->prepare($sql_producto);
    $stmt_producto->bind_param("i", $id_pedido);
    $stmt_producto->execute();
    $result_producto = $stmt_producto->get_result();
    $producto = $result_producto->fetch_assoc();

    if ($producto) {
        $id_producto = $producto['id_producto'];
        $cantidad_pedido = $producto['cantidad'];

        $sql_update = "UPDATE pedidos_proveedor SET estado=? WHERE id_pedido=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);

        if ($stmt_update->execute()) {
            if ($nuevo_estado === 'finalizado') {
                $sql_stock = "UPDATE stock SET cantidad = cantidad + ? WHERE id = ?";
                $stmt_stock = $conn->prepare($sql_stock);
                $stmt_stock->bind_param("ii", $cantidad_pedido, $id_producto);
                $stmt_stock->execute();
                $stmt_stock->close();
            }
            echo "<script>alert('Estado actualizado con éxito.'); window.location.href='ver_pedidos.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el estado: " . $conn->error . "');</script>";
        }

        $stmt_update->close();
    } else {
        echo "<script>alert('No se encontró el producto asociado al pedido.');</script>";
    }

    $stmt_producto->close();
}

// Consulta para obtener los pedidos, uniendo con la tabla de proveedores y stock
$sql = "
    SELECT pp.id_pedido, p.empresa, pp.categoria, s.nombre AS nombre, pp.cantidad, pp.costo, pp.estado 
    FROM pedidos_proveedor pp
    JOIN proveedores p ON pp.id_proveedor = p.id
    JOIN stock s ON pp.id_producto = s.id
";
$result = $conn->query($sql);


session_start();
 // Esto mostrará el contenido de la sesión

// Verifica si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit(); // Detiene la ejecución del script
}

// Verifica el rol del usuario
if ($_SESSION["rol"] !== "administrador" && $_SESSION["rol"] !== "bodega") {
    header("Location: producto.php");
    exit(); // Detiene la ejecución del script
}

// Aquí va el resto del código para manejar los pedidos...
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pagina.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Ver Pedidos</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Fruver</div>
        <div class="menu-item" onclick="window.location.href='principal.php';">Dashboard</div>
        <div class="menu-item" onclick="window.location.href='proveedor.php';">Proveedor</div>
        <div class="menu-item" onclick="window.location.href='producto.php';">Productos</div>
        <div class="menu-item" onclick="window.location.href='ventas.php';">Ventas</div>
        <div class="menu-item" onclick="window.location.href='usuario.php';">Usuarios</div>
    </div>

    <div class="main-content">
    <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Gestion de pedidos</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>

        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Empresa</th> <!-- Cambiado de ID Proveedor a Empresa -->
                        <th>Categoría</th>
                        <th>ID Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id_pedido"] . "</td>
                    <td>" . $row["empresa"] . "</td>
                    <td>" . $row["categoria"] . "</td>
                    <td>" . $row["nombre"] . "</td>
                    <td>" . $row["cantidad"] . "</td>
                    <td>$" . number_format(is_numeric($row["costo"]) ? (float)$row["costo"] : 0.00, 2) . "</td>
                    <td>" . $row["estado"] . "</td>
                    <td>";
            
            // Verifica si el estado es 'finalizado' o 'cancelado'
            if ($row["estado"] !== 'finalizado' && $row["estado"] !== 'cancelado') {
                echo "<form method='post' action='' style='display:inline;'>
                        <input type='hidden' name='id_pedido' value='" . $row["id_pedido"] . "'>
                        <select name='nuevo_estado' required>
                            <option value='pendiente' " . ($row["estado"] == 'pendiente' ? 'selected' : '') . ">Pendiente</option>
                            <option value='finalizado' " . ($row["estado"] == 'finalizado' ? 'selected' : '') . ">Finalizado</option>
                            <option value='cancelado' " . ($row["estado"] == 'cancelado' ? 'selected' : '') . ">Cancelado</option>
                        </select>
                        <button type='submit' name='actualizar_estado' class='btn btn-primary'>Actualizar</button>
                    </form>";
            } else {
                echo "<span>No se puede actualizar</span>"; // Mensaje o texto que indica que no se puede actualizar
            }

            echo "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No hay datos disponibles</td></tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>      