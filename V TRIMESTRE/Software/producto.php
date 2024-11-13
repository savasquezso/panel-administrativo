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

if (isset($_POST['actualizar_stock'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $valor_unidad = $_POST['valor_unidad'];
    $cantidad = $_POST['cantidad'];
    $valor_libra = $_POST['valor_libra'];
    $categoria = $_POST['categoria'];
    $proveedor = $_POST['proveedor'];

    if (preg_match("/^[A-Za-z\s]+$/", $nombre)) {
        $sql_actualizar = "UPDATE stock SET nombre=?, valor_unidad=?, cantidad=?, valor_libra=?, categoria=?, proveedor=? WHERE id=?";
        $stmt = $conn->prepare($sql_actualizar);
        $stmt->bind_param("sdidsis", $nombre, $valor_unidad, $cantidad, $valor_libra, $categoria, $proveedor, $id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Producto actualizado con éxito.</p>";
        } else {
            echo "<p style='color: red;'>Error al actualizar el producto: " . $conn->error . "</p>";
        }
    }
}

if (isset($_POST['eliminar_stock'])) {
    $id_eliminar = $_POST['id'];
    $sql_eliminar = "DELETE FROM stock WHERE id = ?";
    $stmt = $conn->prepare($sql_eliminar);
    $stmt->bind_param("i", $id_eliminar);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Producto eliminado con éxito.</p>";
    } else {
        echo "<p style='color: red;'>Error al eliminar el producto: " . $conn->error . "</p>";
    }
}

$sql = "SELECT id, nombre, valor_unidad, cantidad, valor_libra, categoria, proveedor FROM stock";
$result = $conn->query($sql);

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
        <img src="LOGO.png" alt="" class="logo">
        <nav>
            <div class="nav-item">
                <a href="principal.php" class="nav-link active">Panel principal</a>
            </div>
            <div class="nav-item">
                <a href="ventas.php" class="nav-link">Analisis & Ventas</a>
            </div>
            <div class="nav-item">
                <a href="proveedor.php" class="nav-link">Proveedores</a>
            </div>
            <div class="nav-item">
                <a href="producto.php" class="nav-link">Productos</a>
            </div>
            <div class="nav-item">
                <a href="usuario.php" class="nav-link">Usuarios</a>
            </div>
        </nav>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger">Salir</button>
            </form>
        </div>

        <div class="card-container">

            <div class="card">
                <h6>Ventas totales</h6>
                <h3>$24,568</h3>
                <div class="small success">3.5% incremento</div>
            </div>
            <div class="card">
                <h6>Total ordenes</h6>
                <h3>1,286</h3>
                <div class="small danger">1.2% disminucion</div>
            </div>
            <div class="card">
                <h6>por modificar</h6>
                <h3>2.56%</h3>
                <div class="small success">2.1% incremento</div>
            </div>
            <div class="card">
                <h6>Ganancias</h6>
                <h3>$779.140</h3>
                <div class="small success">5.2% ingresos</div>
            </div>

        </div>

        <div class="row g-4">
            <div class="table-container-p">
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
                                        <td>
                                            <form method='post' action='' style='display:inline;'>
                                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                                <button type='submit' name='eliminar_stock' class='btn btn-danger'>ELIMINAR</button>
                                            </form>
                                            <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarStockModal' 
                                                data-id='" . $row["id"] . "' 
                                                data-nombre='" . $row["nombre"] . "' 
                                                data-valor_unidad='" . $row["valor_unidad"] . "' 
                                                data-cantidad='" . $row["cantidad"] . "' 
                                                data-valor_libra='" . $row["valor_libra"] . "' 
                                                data-categoria='" . $row["categoria"] . "' 
                                                data-proveedor='" . $row["proveedor"] . "'>EDITAR</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay datos disponibles</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-container-p">
            <h2>Nuevo Producto</h2>
            <form id="nuevoProductoForm" method="post" action="agregar_stock.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="nombre" class="form-control" placeholder="Nombre del producto" required pattern="[A-Za-z\s]+">
                    </div>
                    <div class="form-group">
                        <label for="valor_unidad">Valor Unidad</label>
                        <input type="number" id="valor_unidad" name="valor_unidad" class="form-control" placeholder="Valor por unidad" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Ingrese la cantidad" required step="any">
                    </div>
                    <div class="form-group">
                        <label for="valor_libra">Valor Libra</label>
                        <input type="number" id="valor_libra" name="valor_libra" class="form-control" placeholder="Valor por libra" required step="any">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <option value="huevos">Huevos</option>
                            <option value="frutas">Frutas</option>
                            <option value="hortalizas">Hortalizas</option>
                            <option value="carnes">Carnes</option>
                            <option value="especias">Especias</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <input type="text" id="proveedor" name="proveedor" class="form-control" placeholder="Proveedor del producto" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
                <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cerrar</button>
            </form>
        </div>
    </div>
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
                                <option value="huevos">Huevos</option>
                                <option value="frutas">Frutas</option>
                                <option value="hortalizas">Hortalizas</option>
                                <option value="carnes">Carnes</option>
                                <option value="especias">Especias</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-stock-proveedor">Proveedor:</label>
                            <input type="text" class="form-control" name="proveedor" id="edit-stock-proveedor" required>
                        </div>
                        <button type="submit" name="actualizar_stock" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

            var modal = $(this);
            modal.find('#edit-stock-id').val(id);
            modal.find('#edit-stock-nombre').val(nombre);
            modal.find('#edit-stock-valor_unidad').val(valor_unidad);
            modal.find('#edit-stock-cantidad').val(cantidad);
            modal.find('#edit-stock-valor_libra').val(valor_libra);
            modal.find('#edit-stock-categoria').val(categoria);
            modal.find('#edit-stock-proveedor').val(proveedor);
        });

        function ocultarFormulario() {
            document.getElementById('nuevoProductoForm').style.display = 'none';
        }
    </script>
</body>

</html>