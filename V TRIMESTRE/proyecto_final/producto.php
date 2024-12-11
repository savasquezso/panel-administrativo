<?php
include 'db_connection.php';


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$message = ''; // Inicializar la variable de mensaje

// Manejo de agregar producto
if (isset($_POST['agregar_stock'])) {
    $nombre = $_POST['nombre'];
    $valor_unidad = $_POST['valor_unidad'];
    $cantidad = $_POST['cantidad'];
    $valor_libra = $_POST['valor_libra'];
    $categoria = $_POST['categoria'];
    $proveedor = $_POST['proveedor'];
    $stock_minimo = $_POST['stock_minimo'];
    $stock_maximo = $_POST['stock_maximo'];

    if (preg_match("/^[A-Za-z\s]+$/", $nombre)) {
        $sql_agregar = "INSERT INTO stock (nombre, valor_unidad, cantidad, valor_libra, categoria, proveedor, stock_minimo, stock_maximo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_agregar);
        $stmt->bind_param("sdidsisi", $nombre, $valor_unidad, $cantidad, $valor_libra, $categoria, $proveedor, $stock_minimo, $stock_maximo);

        if ($stmt->execute()) {
            $message = "Producto agregado con éxito.";
        } else {
            $message = "Error al agregar el producto: " . $conn->error;
        }
    } else {
        $message = "El nombre del producto no es válido.";
    }
}

// Manejo de actualización de producto
if (isset($_POST['actualizar_stock'])) {
    $id = (int) $_POST['id'];
    $nombre = $_POST['nombre'];
    $valor_unidad = (float) $_POST['valor_unidad'];
    $cantidad = (int) $_POST['cantidad'];
    $valor_libra = (float) $_POST['valor_libra'];
    $categoria = $_POST['categoria'];
    $stock_minimo = (int) $_POST['stock_minimo'];
    $stock_maximo = (int) $_POST['stock_maximo'];

    $sql_actualizar = "UPDATE stock SET nombre=?, valor_unidad=?, cantidad=?, valor_libra=?, categoria=?, stock_minimo=?, stock_maximo=? WHERE id=?";
    $stmt = $conn->prepare($sql_actualizar);
    $stmt->bind_param("sdidssii", $nombre, $valor_unidad, $cantidad, $valor_libra, $categoria,$stock_minimo, $stock_maximo, $id);

    if ($stmt->execute()) {
        $message = "Producto actualizado con éxito.";
        header("Location: producto.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error al actualizar el producto: " . $stmt->error;
        header("Location: producto.php?message=" . urlencode($message));
        exit();
    }
}

// Manejo de eliminación de producto
if (isset($_POST['eliminar_stock'])) {
    $id_eliminar = (int) $_POST['id']; // Asegúrate de que sea int

    $sql_eliminar = "DELETE FROM stock WHERE id = ?";
    $stmt = $conn->prepare($sql_eliminar);
    $stmt->bind_param("i", $id_eliminar);

    if ($stmt->execute()) {
        $message = "Producto eliminado con éxito.";
        header("Location: producto.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error al eliminar el producto: " . $stmt->error;
        header("Location: producto.php?message=" . urlencode($message));
        exit();
    }
}
// Paginación
$productos_por_pagina = 10; // Número de productos por página
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Consulta para obtener los productos con paginación
$sql = "SELECT id, nombre, valor_unidad, cantidad, valor_libra, categoria, proveedor, stock_minimo, stock_maximo FROM stock LIMIT $offset, $productos_por_pagina";
$result = $conn->query($sql);

// Consulta para contar el total de productos
$sql_total = "SELECT COUNT(*) as total FROM stock";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_productos = $row_total['total'];
$total_paginas = ceil($total_productos / $productos_por_pagina);

session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit();     // Detiene la ejecución del script
}

// Verifica el rol del usuario
if ($_SESSION["rol"] !== "administrador" && $_SESSION["rol"] !== "bodega") {
    // Redirigir o mostrar un mensaje de acceso denegado si es necesario
}


$sql_proveedores = "SELECT id, empresa FROM proveedores";
$result_proveedores = $conn->query($sql_proveedores);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pagina.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<div class="sidebar">
        <div class="logo">Fruver</div>
        <div class="menu-item" onclick="window.location.href='principal.php';">Dashboard</div>
        <div class="menu-item" onclick="window.location.href='proveedor.php';">Proveedor</div>
        <div class="menu-item"onclick="window.location.href='producto.php';">Productos</div>
        <div class="menu-item"onclick="window.location.href='ventas.php';">Ventas</div>
        <div class="menu-item"onclick="window.location.href='usuario.php';">Usuarios</div>
        <div class="menu-item" onclick="window.location.href='ver_pedidos.php';">Ver Pedidos</div>
    </div>
    <div class="main-content">
    <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Stock</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>
<div class="main-content">
    <div class="container-flex">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div>
                <h3>BIENVENIDO</h3>
                <p>Aquí está tu resumen principal</p>
                <button onclick="window.location.href='ver_pedidos.php';" style="background: white; color: #6366f1; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 10px; cursor: pointer;">
    Ver Pedidos
</button>
            </div>
            <img src="img-producto-bienvenida.svg" class="card-image" alt="Imagen SVG">
        </div>

     

        <!-- Form Container -->
        <div class="form-container-producto">
            <h2>Nuevo Producto</h2>
            <form id="nuevoProductoForm" method="post" action="agregar_stock.php">
    <div class="form-row">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="valor_unidad">Valor Unidad</label>
            <input type="number" id="valor_unidad" name="valor_unidad" class="form-control" required step="any">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="valor_libra">Valor Libra</label>
            <input type="number" id="valor_libra" name="valor_libra" class="form-control" required step="any">
        </div>
        <div class="form-group">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria" class="form-control" required>
            <option value="">Seleccionar Categoría</option>
                            <option value="frutas">Frutas</option>
                            <option value="verdura">Verdura</option>
                            <option value="carnes">Tuberculos</option>
                            <option value="carnes">Hierbas y aromaticas</option>
                            <option value="carnes">Cereales</option>
                            <option value="carnes">Raíces</option>
                            <option value="carnes">Especias y condimentos</option>
                            <option value="carnes">Hongos y setas</option>
            </select>
        </div>
        <div class="form-group">
    <label for="proveedor">Proveedor</label>
    <select id="proveedor" name="proveedor" class="form-control" required>
        <option value="">Seleccionar Proveedor</option>
        <?php
        // Obtener proveedores de la base de datos
        $sql_proveedores = "SELECT id, empresa FROM proveedores";
        $result_proveedores = $conn->query($sql_proveedores);
        while ($row = $result_proveedores->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['empresa'] . "</option>";
        }
        ?>
    </select>
</div>
        <div class="form-group">
            <label for="stock_minimo">Stock Mínimo</label>
            <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="stock_maximo">Stock Máximo</label>
            <input type="number" id="stock_maximo" name="stock_maximo" class="form-control" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Agregar Producto</button>
</form>
        </div>
    </div>
       <!-- Card Grid -->
       <div class="card-grid">
            <div type="button"  class="card-item" data-toggle="modal" data-target="#crearPedidoModal" >Crear Pedido</div>
            <div class="card-item"></div>
            <div class="card-item"></div>
        </div>
</div>


<!-- Modal para Crear Pedido -->
<div class="modal fade" id="crearPedidoModal" tabindex="-1" role="dialog" aria-labelledby="crearPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPedidoModalLabel">Crear Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="crearPedidoForm" method="post" action="agregar_pedido.php">
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <select id="proveedor" name="id_proveedor" class="form-control" required>
                            <option value="">Seleccionar Proveedor</option>
                            <?php
                            // Obtener proveedores de la base de datos
                            $sql_proveedores = "SELECT id, empresa FROM proveedores";
                            $result_proveedores = $conn->query($sql_proveedores);
                            while ($row = $result_proveedores->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['empresa'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_producto">Producto</label>
                        <select id="id_producto" name="id_producto" class="form-control" required>
                            <option value="">Seleccionar Producto</option>
                            <?php
                            // Obtener productos de la base de datos
                            $sql_productos = "SELECT id, nombre FROM stock";
                            $result_productos = $conn->query($sql_productos);
                            while ($row = $result_productos->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <option value="">Seleccionar Categoría</option>
                            <option value="frutas">Frutas</option>
                            <option value="verdura">Verdura</option>
                            <option value="tuberculos">Tuberculos</option>
                            <option value="hiervas_aromaticas">Hierbas y aromaticas</option>
                            <option value="cereales">Cereales</option>
                            <option value="Raices">Raíces</option>
                            <option value="Especias_condimentos">Especias y condimentos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="costo">Costo</label>
                        <input type="number" id="costo" name="costo" class="form-control" required step="0.01" min="0" placeholder="Costo del pedido">
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Pedido</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

$message = "";
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>
        <div class="row g-4">
            <div class="table-container-p">
            <div class="alert-container">
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
                <table class="table">
                <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Valor Unidad</th>
        <th>Cantidad</th>
        <th>Valor Libra</th>
        <th>Categoría</th>
        <th>Proveedor</th>
        <th>Stock Mínimo</th>
        <th>Stock Máximo</th>
        <th>Acción</th>
    </tr>
</thead>
<tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["nombre"] . "</td>
                    <td>" . $row["valor_unidad"] . "</td>
                    <td>" . $row["cantidad"] . "</td>
                    <td>" . $row["valor_libra"] . "</td>
                    <td>" . $row["categoria"] . "</td>
                    <td>" . $row["proveedor"] . "</td>
                    <td>" . $row["stock_minimo"] . "</td>
                    <td>" . $row["stock_maximo"] . "</td>
                    <td>
                        <form method='post' action='' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <button type='submit' name='eliminar_stock' class='btn btn-danger'>Eliminar</button>
                        </form>
                        <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarStockModal' 
                            data-id='" . $row["id"] . "' 
                            data-nombre='" . $row["nombre"] . "' 
                            data-valor_unidad='" . $row["valor_unidad"] . "' 
                            data-cantidad='" . $row["cantidad"] . "' 
                            data-valor_libra='" . $row["valor_libra"] . "' 
                            data-categoria='" . $row["categoria"] . "' 
                            data-proveedor='" . $row["proveedor"] . "' 
                            data-stock_minimo='" . $row["stock_minimo"] . "' 
                            data-stock_maximo='" . $row["stock_maximo"] . "'>Editar</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No hay datos disponibles</td></tr>";
    }
    ?>
</tbody>
                </table>
                <div class="pagination">
    <?php if ($pagina_actual > 1): ?>
        <button onclick="changePage(<?php echo $pagina_actual - 1; ?>)">Anterior</button>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <button onclick="changePage(<?php echo $i; ?>)" <?php if ($i == $pagina_actual) echo 'class="active"'; ?>>
            <?php echo $i; ?>
        </button>
    <?php endfor; ?>

    <?php if ($pagina_actual < $total_paginas): ?>
        <button onclick="changePage(<?php echo $pagina_actual + 1; ?>)">Siguiente</button>
    <?php endif; ?>
</div>
            </div>
            
        </div>

        
    </div>
    <!-- Modal para Editar Producto -->
<div class="modal fade" id="editarStockModal" tabindex="-1" role="dialog" aria-labelledby="editarStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarStockModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="id" id="edit-stock-id">
                    <div class="form-group">
                        <label for="edit-stock-nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="edit-stock-nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-stock-valor_unidad">Valor Unidad:</label>
                        <input type="number" class="form-control" name="valor_unidad" id="edit-stock-valor_unidad" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="edit-stock-cantidad">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad" id="edit-stock-cantidad" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="edit-stock-valor_libra">Valor Libra:</label>
                        <input type="number" class="form-control" name="valor_libra" id="edit-stock-valor_libra" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="edit-stock-categoria">Categoría:</label>
                        <select class="form-control" name="categoria" id="edit-stock-categoria" required>
                            <option value="frutas">Frutas</option>
                            <option value="verdura">Verdura</option>
                            <option value="tuberculos">Tuberculos</option>
                            <option value="hiervas_aromaticas">Hierbas y aromaticas</option>
                            <option value="cereales">Cereales</option>
                            <option value="Raices">Raíces</option>
                            <option value="Especias_condimentos">Especias y condimentos</option>
                        </select>
                    </div>
               
                    <div class="form-group">
                        <label for="edit-stock-minimo">Stock Mínimo:</label>
                        <input type="number" class="form-control" name="stock_minimo" id="edit-stock-minimo" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-stock-maximo">Stock Máximo:</label>
                        <input type="number" class="form-control" name="stock_maximo" id="edit-stock-maximo" required>
                    </div>
                    <button type="submit" name="actualizar_stock" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
  $('#editarStockModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var nombre = button.data('nombre');
    var valor_unidad = button.data('valor_unidad');
    var cantidad = button.data('cantidad');
    var valor_libra = button.data('valor_libra');
    var categoria = button.data('categoria');
    var proveedor = button.data('proveedor');
    var stock_minimo = button.data('stock_minimo');
    var stock_maximo = button.data('stock_maximo');

    var modal = $(this);
    modal.find('#edit-stock-id').val(id);
    modal.find('#edit-stock-nombre').val(nombre);
    modal.find('#edit-stock-valor_unidad').val(valor_unidad);
    modal.find('#edit-stock-cantidad').val(cantidad);
    modal.find('#edit-stock-valor_libra').val(valor_libra);
    modal.find('#edit-stock-categoria').val(categoria);
    modal.find('#edit-stock-proveedor').val(proveedor);
    modal.find('#edit-stock-minimo').val(stock_minimo);
    modal.find('#edit-stock-maximo').val(stock_maximo);
});

function changePage(page) {
    window.location.href = 'producto.php?pagina=' + page; // Cambia 'producto.php' por el nombre de tu archivo
}
</script>

</body>

</html>