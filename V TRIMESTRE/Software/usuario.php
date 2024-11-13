<?php
include 'db_connection.php';
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
        <img src="" alt="" class="logo">
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
                <a href="proveedor.php" class="nav-link">Productos</a>
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
        <div class="container-usuario">
            <form action="crear_usuario.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <input type="submit" value="Registrar">
            </form>
        </div>
        
        <h3>Usuarios Existentes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
                $servername = "localhost";
                $username = "root";  
                $password = "";  
                $dbname = "fruver_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

           
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }
                if (isset($_POST['eliminar'])) {
                    $id_eliminar = $_POST['id'];
                    $sql_eliminar = "DELETE FROM usuarios WHERE id = ?";
                    $stmt = $conn->prepare($sql_eliminar);
                    $stmt->bind_param("i", $id_eliminar);

                    if ($stmt->execute()) {
                        echo "<p style='color: green;'>Usuario eliminado con éxito.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al eliminar el usuario: " . $conn->error . "</p>";
                    }
                }
                if (isset($_POST['actualizar'])) {
                    $id = $_POST['id'];
                    $nombre = $_POST['nombre'];
                    $usuario = $_POST['usuario'];
                    $contrasena = $_POST['contrasena'];
                    $sql_actualizar = "UPDATE usuarios SET nombre=?, usuario=?, contraseña=? WHERE id=?";
                    $stmt = $conn->prepare($sql_actualizar);
                    $stmt->bind_param("sssi", $nombre, $usuario, $contrasena, $id);

                    if ($stmt->execute()) {
                        echo "<p style='color: green;'>Usuario actualizado con éxito.</p>";
                    } else {
                        echo "<p style='color: red;'>Error al actualizar el usuario: " . $conn->error . "</p>";
                    }
                }
                $sql = "SELECT id, nombre, usuario, contraseña FROM usuarios";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["nombre"] . "</td>
                                <td>" . $row["usuario"] . "</td>
                                <td>" . $row["contraseña"] . "</td>
                                <td>
                                    <form method='post' action='' style='display:inline;'>
                                        <input type='hidden' name='id' value='" . $row["id"] . "'>
                                        <button type='submit' name='eliminar' class='btn btn-danger'>ELIMINAR</button>
                                    </form>
                                    <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarModal' 
                                        data-id='" . $row["id"] . "' 
                                        data-nombre='" . $row["nombre"] . "' 
                                        data-usuario='" . $row["usuario"] . "' 
                                        data-contrasena='" . $row["contraseña"] . "'>EDITAR</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay datos disponibles</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
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
                        <form method="post" action="">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="form-group">
                                <label for="edit-nombre">Nombre:</label>
                                <input type="text" class="form-control" name="nombre" id="edit-nombre">
                            </div>
                            <div class="form-group">
                                <label for="edit-usuario">Usuario:</label>
                                <input type="text" class="form-control" name="usuario" id="edit-usuario">
                            </div>
                            <div class="form-group">
                                <label for="edit-contrasena">Contraseña:</label>
                                <input type="password" class="form-control" name="contrasena" id="edit-contrasena">
                            </div>
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#editarModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id'); 
                var nombre = button.data('nombre'); 
                var usuario = button.data('usuario'); 
                var contrasena = button.data('contrasena'); 
                var modal = $(this);
                modal.find('#edit-id').val(id);
                modal.find('#edit-nombre').val(nombre);
                modal.find('#edit-usuario').val(usuario);
                modal.find('#edit-contrasena').val(contrasena);
            });
        </script>
    </div>
</body>
</html>
