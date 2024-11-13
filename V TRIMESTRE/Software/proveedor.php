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
    <!-- Agrega los enlaces de Bootstrap CSS y JS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<div class="dashboard">
    <div class="sidebar">
         
         <nav>
             <div class="nav-item">
                 <a href="principal.php" class="nav-link active">🏠</a>
             </div>
             <div class="nav-item">
                 <a href="ventas.php" class="nav-link">📊</a>
             </div>
             <div class="nav-item">
                 <a href="proveedor.php" class="nav-link">📊</a>
             </div>
             <div class="nav-item">
                 <a href="producto.php" class="nav-link">📅</a>
             </div>
             <div class="nav-item">
                 <a href="usuario.php" class="nav-link">⚙️</a>
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
                            <th>Proveedor</th>
                            <th>Tipo</th>
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
                        if (isset($_POST['eliminar_proveedor'])) {
                            $id_eliminar = $_POST['id'];
                            $sql_eliminar = "DELETE FROM proveedores WHERE id = ?";
                            $stmt = $conn->prepare($sql_eliminar);
                            $stmt->bind_param("i", $id_eliminar);

                            if ($stmt->execute()) {
                                echo "<p style='color: green;'>Proveedor eliminado con éxito.</p>";
                            } else {
                                echo "<p style='color: red;'>Error al eliminar el proveedor: " . $conn->error . "</p>";
                            }
                        }
                        if (isset($_POST['actualizar_proveedor'])) {
                            $id = $_POST['id'];
                            $nombre = $_POST['nombre'];
                            $tipo = $_POST['tipo'];
                            $sql_actualizar = "UPDATE proveedores SET nombre=?, categoria=? WHERE id=?";
                            $stmt = $conn->prepare($sql_actualizar);
                            $stmt->bind_param("ssi", $nombre, $tipo, $id);

                            if ($stmt->execute()) {
                                echo "<p style='color: green;'>Proveedor actualizado con éxito.</p>";
                            } else {
                                echo "<p style='color: red;'>Error al actualizar el proveedor: " . $conn->error . "</p>";
                            }
                        }
                        $sql = "SELECT id, nombre, categoria FROM proveedores";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id"] . "</td>
                                        <td>" . $row["nombre"] . "</td>
                                        <td>" . $row["categoria"] . "</td>
                                        <td>
                                            <form method='post' action='' style='display:inline;'>
                                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                                <button type='submit' name='eliminar_proveedor' class='btn btn-danger'>ELIMINAR</button>
                                            </form>
                                            <button class='btn btn-warning' style='margin-left: 5px;' data-toggle='modal' data-target='#editarProveedorModal' 
                                                data-id='" . $row["id"] . "' 
                                                data-nombre='" . $row["nombre"] . "' 
                                                data-tipo='" . $row["categoria"] . "'>EDITAR</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay datos disponibles</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-container-p">
            <h2>Nuevo Proveedor</h2>
            <form method="post" action="agregar_proveedor.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="nombre" placeholder="Nombre del representante" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" id="email" name="correo" placeholder="Ingresar correo" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Número</label>
                        <input type="text" id="phone" name="telefono" placeholder="Ingrese un número" pattern="\d+" title="Solo números" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Nombre de la Empresa</label>
                        <input type="text" id="company" name="empresa" placeholder="Nombre de la empresa" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="business-address">Dirección</label>
                        <input type="text" id="business-address" name="direccion" placeholder="Ingrese la dirección" required>
                    </div>
                    <div class="form-group">
                        <label for="province">Ciudad/Municipio</label>
                        <input type="text" id="province" name="ciudad_municipio" placeholder="Ingrese Ciudad/Municipio" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
                    </div>
                    <div class="form-group">
                        <label for="industry">Categoría</label>
                        <select id="industry" name="categoria" required>
                            <option value="">Seleccionar</option>
                            <option value="Frutas">Frutas</option>
                            <option value="Verduras">Verduras</option>
                            <option value="Tuberculos">Tuberculos</option>
                            <option value="Especias">Especias</option>
                            <option value="Huevos">Huevos</option>
                            <!-- Opciones adicionales si es necesario -->
                        </select>
                    </div>
                </div>
                <div class="button-group">
                    <button type="button" class="button button-cancel">Cancelar</button>
                    <button type="submit" class="button button-submit">Agregar</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Modal de Edición de Proveedor -->
    <div class="modal fade" id="editarProveedorModal" tabindex="-1" role="dialog" aria-labelledby="editarProveedorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarProveedorModalLabel">Editar Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-name">Nombre</label>
                            <input type="text" id="edit-name" name="nombre" class="form-control" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-type">Tipo</label>
                            <input type="text" id="edit-type" name="tipo" class="form-control" pattern="[A-Za-z\s]+" title="Solo letras y espacios" required>
                        </div>
                        <button type="submit" name="actualizar_proveedor" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
                    </div>

    <script>
        $('#editarProveedorModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var tipo = button.data('tipo');

            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-name').val(nombre);
            modal.find('#edit-type').val(tipo);
        });
    </script>
</body>

</html>