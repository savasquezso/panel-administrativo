<?php
include 'db_connection.php';

$message = ''; // Variable para almacenar mensajes

// Manejo de actualización de proveedor
if (isset($_POST['actualizar_proveedor'])) {
    if (isset($_POST['id'], $_POST['nombre'], $_POST['correo'], $_POST['telefono'], $_POST['empresa'], $_POST['direccion'], $_POST['ciudad_municipio'], $_POST['categoria'])) {
        $id = $_POST['id'];

        if (empty($id) || !is_numeric($id)) {
            $message = "<div class='alert alert-danger'>El ID del proveedor no está definido o no es válido.</div>";
        } else {
            $nombre = $conn->real_escape_string(trim($_POST['nombre']));
            $correo = $conn->real_escape_string(trim($_POST['correo']));
            $telefono = $conn->real_escape_string(trim($_POST['telefono']));
            $empresa = $conn->real_escape_string(trim($_POST['empresa']));
            $direccion = $conn->real_escape_string(trim($_POST['direccion']));
            $ciudad_municipio = $conn->real_escape_string(trim($_POST['ciudad_municipio']));
            $categoria = $conn->real_escape_string(trim($_POST['categoria']));

            if (empty($nombre) || empty($correo) || empty($telefono) || empty($empresa) || empty($direccion) || empty($ciudad_municipio) || empty($categoria)) {
                $message = "<div class='alert alert-danger'>Todos los campos son obligatorios.</div>";
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $message = "<div class='alert alert-danger'>El correo electrónico no es válido.</div>";
            } else {
                $sql_actualizar = "UPDATE proveedores SET 
                    nombre='$nombre', 
                    correo='$correo', 
                    telefono='$telefono', 
                    empresa='$empresa', 
                    direccion='$direccion', 
                    ciudad_municipio='$ciudad_municipio', 
                    categoria='$categoria' 
                    WHERE id=$id";

                if ($conn->query($sql_actualizar) === TRUE) {
                    $message = "<div class='alert alert-success'>Proveedor actualizado correctamente.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Error al actualizar el proveedor: " . $conn->error . "</div>";
                }
            }
        }
    } else {
        $message = "<div class='alert alert-danger'>Faltan datos para actualizar el proveedor.</div>";
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}

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
      // Redirigir a la misma página con el mensaje
      header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
      exit();
}
 
// Consulta para obtener proveedores
$sql = "SELECT * FROM proveedores";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="proveedor.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Proveedor</title>
</head>
<body>

<div class="sidebar">
    <div class="logo">Fruver</div>
    <div class="menu-item" onclick="window.location.href='principal.php';">Dashboard</div>
    <div class="menu-item" onclick="window.location.href='proveedor.php';">Proveedor</div>
    <div class="menu-item" onclick="window.location.href='producto.php';">Productos</div>
    <div class="menu-item" onclick="window.location.href='ventas.php';">Ventas</div>
    <div class="menu-item" onclick="window.location.href='usuario.php';">Usuarios</div>
    <div class="menu-item" onclick="window.location.href='ver_pedidos.php';">Ver Pedidos</div>
</div>

<div class="main-content">
<div class="header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Proveedores</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>

    <div class="header-container">
    <?php
$message = ''; // Inicializar la variable de mensaje

// Verificar si hay un mensaje en la URL
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<div class="form-container-proveedor">
    <h2>Nuevo Proveedor</h2>

   
    
    <form method="post" action="agregar_proveedor.php">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="nombre" placeholder="Nombre del representante" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required>
            </div>
            <div class="form-group">
                <label for="empresa">Empresa</label>
                <input type="text" id="empresa" name="empresa" placeholder="Nombre de la empresa" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
            </div>
            <div class="form-group">
                <label for="ciudad_municipio">Ciudad/Municipio</label>
                <input type="text" id="ciudad_municipio" name="ciudad_municipio" placeholder="Ciudad o Municipio" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria" name="categoria" placeholder="Categoría" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Agregar Proveedor</button>
    </form>
</div>

        <?php
// Suponiendo que ya tienes la conexión a la base de datos y la consulta inicial
$limit = 6; // Número de proveedores por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Obtener la página actual
$offset = ($page - 1) * $limit; // Calcular el desplazamiento

// Consulta para obtener los proveedores con límite y desplazamiento
$result = $conn->query("SELECT * FROM proveedores LIMIT $limit OFFSET $offset");
$totalResult = $conn->query("SELECT COUNT(*) as total FROM proveedores");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit); // Calcular el número total de páginas
?>

<div class="card">
    <div class="card-header">
        <h2>Lista de Proveedores</h2>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert-container">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>



<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Empresa</th>
            <th>Dirección</th>
            <th>Ciudad/Municipio</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["nombre"]; ?></td>
                    <td><?php echo $row["correo"]; ?></td>
                    <td><?php echo $row["telefono"]; ?></td>
                    <td><?php echo $row["empresa"]; ?></td>
                    <td><?php echo $row["direccion"]; ?></td>
                    <td><?php echo $row["ciudad_municipio"]; ?></td>
                    <td><?php echo $row["categoria"]; ?></td>
                    <td>
                        <form method='post' action='proveedor.php' style='display:inline;'>
                            <input type='hidden' name='id' value='<?php echo $row["id"]; ?>'>
                            <button type='submit' name='eliminar_proveedor' class='btn btn-danger' onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">ELIMINAR</button>
                        </form>
                        <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarProveedorModal' 
                            data-id='<?php echo $row["id"]; ?>' 
                            data-nombre='<?php echo $row["nombre"]; ?>' 
                            data-correo='<?php echo $row["correo"]; ?>' 
                            data-telefono='<?php echo $row["telefono"]; ?>' 
                            data-empresa='<?php echo $row["empresa"]; ?>' 
                            data-direccion='<?php echo $row["direccion"]; ?>' 
                            data-ciudad='<?php echo $row["ciudad_municipio"]; ?>' 
                            data-categoria='                            <?php echo $row["categoria"]; ?>'>EDITAR</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                
                <td colspan="9">No hay proveedores registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
       
    </div>
</div>

<!-- Modal para editar proveedor -->
<div class="modal fade" id="editarProveedorModal" tabindex="-1" role="dialog" aria-labelledby="editarProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProveedorModalLabel">Editar Proveedor                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="id" id="modal-id">
                    <div class="form-group">
                        <label for="modal-nombre">Nombre</label>
                        <input type="text" class="form-control" id="modal-nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-correo">Correo</label>
                        <input type="email" class="form-control" id="modal-correo" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-telefono">Teléfono</label>
                        <input type="text" class="form-control" id="modal-telefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-empresa">Empresa</label>
                        <input type="text" class="form-control" id="modal-empresa" name="empresa" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-direccion">Dirección</label>
                        <input type="text" class="form-control" id="modal-direccion" name="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-ciudad">Ciudad/Municipio</label>
                        <input type="text" class="form-control" id="modal-ciudad" name="ciudad_municipio" required>
                    </div>
                    <div class="form-group">
                        <label for="modal-categoria">Categoría</label>
                        <input type="text" class="form-control" id="modal-categoria" name="categoria" required>
                    </div>
                    <button type="submit" name="actualizar_proveedor" class="btn btn-primary">Actualizar Proveedor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Script para llenar el modal de edición con los datos del proveedor
    $('#editarProveedorModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var id = button.data('id');
        var nombre = button.data('nombre');
        var correo = button.data('correo');
        var telefono = button.data('telefono');
        var empresa = button.data('empresa');
        var direccion = button.data('direccion');
        var ciudad = button.data('ciudad');
        var categoria = button.data('categoria');

        var modal = $(this);
        modal.find('#modal-id').val(id);
        modal.find('#modal-nombre').val(nombre);
        modal.find('#modal-correo').val(correo);
        modal.find('#modal-telefono').val(telefono);
        modal.find('#modal-empresa').val(empresa);
        modal.find('#modal-direccion').val(direccion);
        modal.find('#modal-ciudad').val(ciudad);
        modal.find('#modal-categoria').val(categoria);
    });
</script>

</body>
</html> 