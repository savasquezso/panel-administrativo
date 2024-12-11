<?php
include 'db_connection.php';
session_start();

// Verifica si el usuario está logueado y si tiene el rol correcto
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: producto.php");
    exit(); // Detiene la ejecución del script
}

$message = ''; // Variable para almacenar mensajes


// Manejo de eliminación de usuario
if (isset($_POST['eliminar'])) {
    $id_eliminar = $_POST['id'];
    
    // Procede a eliminar el usuario sin importar el rol
    $sql_eliminar = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql_eliminar);
    $stmt->bind_param("i", $id_eliminar);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>Usuario eliminado con éxito.</p>";
    } else {
        $message = "<p style='color: red;'>Error al eliminar el usuario: " . $conn->error . "</p>";
    }
}

// Manejo de creación de usuario
if (isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol']; // Obtiene el rol del formulario

    // Inserta los datos del nuevo usuario, incluyendo el rol
    $sql_crear = "INSERT INTO usuarios (nombre, usuario, contraseña, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_crear);
    $stmt->bind_param("ssss", $nombre, $usuario, $contrasena, $rol);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>Usuario registrado con éxito.</p>";
    } else {
        $message = "<p style='color: red;'>Error al crear el usuario: " . $conn->error . "</p>";
    }
}

// Manejo de actualización de usuario
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Actualizar datos del usuario
    $sql_actualizar = "UPDATE usuarios SET nombre=?, usuario=?, contraseña=? WHERE id=?";
    $stmt = $conn->prepare($sql_actualizar);
    $stmt->bind_param("sssi", $nombre, $usuario, $contrasena, $id);

    if ($stmt->execute()) {
        $message = "<p style='color: green;'>Usuario actualizado con éxito.</p>";
    } else {
        $message = "<p style='color: red;'>Error al actualizar el usuario: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pagina.css">
    <link rel="stylesheet" href="usuario.css">
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
    <div class="menu-item" onclick="window.location.href='producto.php';">Productos</div>
    <div class="menu-item" onclick="window.location.href='ventas.php';">Ventas</div>
    <div class="menu-item" onclick="window.location.href='usuario.php';">Usuarios</div>
    <div class="menu-item" onclick="window.location.href='ver_pedidos.php';">Ver Pedidos</div>
</div>

<div class="main-content">
<div class="header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Gestion de Usuarios</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>

    <div class="header-container">
        <div class="container-usuario">
            <form action="" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <label for="rol">Rol:</label>
                <select name="rol" id="rol" required>
                    <option value="administrador">Administrador</option>
                    <option value="bodega">Bodega</option>
                </select>

                <input type="submit" name="crear_usuario" value="Registrar">
            </form>
        </div>

        <div class="form-container-user">
            <h3>Usuarios Existentes</h3>
            <table class="table">
            <div class="message-container">
    <?php if (!empty($message)) echo $message; ?>
</div>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener todos los usuarios
                    $sql = "SELECT id, nombre, usuario, rol FROM usuarios";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["nombre"] . "</td>
                                    <td>" . $row["usuario"] . "</td>
                                    <td>" . $row["rol"] . "</td>
                                    <td>
                                        <form method='post' action='' style='display:inline;'>
                                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                                            <button type='submit' name='eliminar' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>ELIMINAR</button>
                                        </form>
                                        <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarModal' 
                                            data-id='" . $row["id"] . "' 
                                            data-nombre='" . $row["nombre"] . "' 
                                            data-usuario='" . $row["usuario"] . "' 
                                            data-rol='" . $row["rol"] . "'>EDITAR</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay datos disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div> 
    </div>            

    <!-- Modal para Editar Usuario -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditar" method="POST" action="">
                        <input type="hidden" name="id" id="editarId">
                        <div class="form-group">
                            <label for="editarNombre">Nombre:</label>
                            <input type="text" class="form-control" id="editarNombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editarUsuario">Usuario:</label>
                            <input type="text" class="form-control" id="editarUsuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="editarContrasena">Contraseña:</label>
                            <input type="password" class="form-control" id="editarContrasena" name="contrasena" required>
                        </div>
                        <div class="form-group">
                            <label for="editarRol">Rol:</label>
                            <select class="form-control" id="editarRol" name="rol" required>
                                <option value="administrador">Administrador</option>
                                <option value="bodega">Bodega</option>
                            </select>
                        </div>
                        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script para llenar el modal de edición
        $('#editarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var id = button.data('id');
            var nombre = button.data('nombre');
            var usuario = button.data('usuario');
            var rol = button.data('rol');

            var modal = $(this);
            modal.find('#editarId').val(id);
            modal.find('#editarNombre').val(nombre);
            modal.find('#editarUsuario').val(usuario);
            modal.find('#editarRol').val(rol); // Rellenar el rol
        });
    </script>

</div>
</body>
</html>