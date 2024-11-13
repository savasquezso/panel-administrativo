<?php
    include 'db_connection.php';

    $totalProveedores = 0;
    $pedidos = [];
    $proveedores = [];
    $categorias = [];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT COUNT(*) FROM proveedores");
        $totalProveedores = $stmt->fetchColumn();

        $sql = "SELECT pedidos_proveedor.id_pedido, proveedores.empresa AS proveedor, proveedores.categoria, pedidos_proveedor.costo
                FROM pedidos_proveedor
                INNER JOIN proveedores ON pedidos_proveedor.id_proveedor = proveedores.id
                ORDER BY pedidos_proveedor.id_pedido";
        $stmt = $pdo->query($sql);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmtProveedores = $pdo->query("SELECT id, empresa, categoria FROM proveedores");
        $proveedores = $stmtProveedores->fetchAll(PDO::FETCH_ASSOC);
        $categorias = array_unique(array_column($proveedores, 'categoria'));
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pedido'])) {
            $id_proveedor = $_POST['id_proveedor'];
            $categoria = $_POST['categoria'];
            $costo = $_POST['costo'];

            $sqlProveedor = "SELECT * FROM proveedores WHERE id = :id_proveedor";
            $stmtProveedor = $pdo->prepare($sqlProveedor);
            $stmtProveedor->execute(['id_proveedor' => $id_proveedor]);
            $proveedor = $stmtProveedor->fetch(PDO::FETCH_ASSOC);

            if ($proveedor) {
                $sqlInsert = "INSERT INTO pedidos_proveedor (id_proveedor, costo) VALUES (:id_proveedor, :costo)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute(['id_proveedor' => $id_proveedor, 'costo' => $costo]);

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error = "Proveedor no encontrado.";
            }
        }
        if (isset($_GET['delete_pedido'])) {
            $id_pedido = $_GET['delete_pedido'];

            $sqlDelete = "DELETE FROM pedidos_proveedor WHERE id_pedido = :id_pedido";
            $stmtDelete = $pdo->prepare($sqlDelete);
            $stmtDelete->execute(['id_pedido' => $id_pedido]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pedido'])) {
            $id_pedido = $_POST['id_pedido'];
            $id_proveedor = $_POST['id_proveedor'];
            $costo = $_POST['costo'];

            $sqlUpdate = "UPDATE pedidos_proveedor
                        SET id_proveedor = :id_proveedor, costo = :costo
                        WHERE id_pedido = :id_pedido";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute(['id_proveedor' => $id_proveedor, 'costo' => $costo, 'id_pedido' => $id_pedido]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $sql = "SELECT SUM(nombre) as total_ventas FROM ventas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['total_ventas'];
    } else {
        echo "0";
    }
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT SUM(id) AS ventas_totales FROM ventas";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ventas_totales = $result['ventas_totales'] ? $result['ventas_totales'] : 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="pagina.css">
        <link href="" rel="stylesheet">
        <script src=""></script>
        <style> 
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }

            .chart-container {
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
                margin-bottom: 20px;
            }

            canvas {
                width: 100% !important;
                height: auto !important;
            }
        </style>
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
                    <h6>Total proveedores</h6>
                    <h3><?php echo htmlspecialchars($totalProveedores); ?></h3>
                    <div class="small warning">Sin cambio</div>
                </div>

                <div class="card card-stad">
                    <h6>Costo total</h6>
                    <h3>$8,568</h3>
                    <div class="small success">8.2% incremento</div>
                </div>

            </div>
            <div class="stats-container">
                    
                    
                </div>
            

            <!-- Canvas para la Gráfica -->
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Proveedor</th>
                            <th>Categoría</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido["id_pedido"]); ?></td>
                                <td><?php echo htmlspecialchars($pedido["proveedor"]); ?></td>
                                <td><?php echo htmlspecialchars($pedido["categoria"]); ?></td>
                                <td>$<?php echo number_format(htmlspecialchars($pedido["costo"]), 2); ?></td>
                                <td>
                                    <!-- Botón Editar -->
                                    <button class="btn btn-warning btn-sm" onclick="editPedido(<?php echo htmlspecialchars($pedido["id_pedido"]); ?>)">Editar</button>
                                    <!-- Botón Eliminar -->
                                    <a href="?delete_pedido=<?php echo htmlspecialchars($pedido["id_pedido"]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este pedido?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botón Agregar Pedido -->
            <button class="btn btn-primary" onclick="toggleAddModal()">Agregar Pedido</button>

            <!-- Modal Agregar Pedido -->
            <div class="modal" id="addPedidoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Pedido</h5>
                            <button type="button" class="btn-close-text" onclick="toggleAddModal()">Cerrar</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="id_proveedor" class="form-label">Proveedor</label>
                                    <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                                        <option value="">Selecciona un proveedor</option>
                                        <?php foreach ($proveedores as $proveedor): ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['id']); ?>">
                                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <select class="form-control" id="categoria" name="categoria" required>
                                        <option value="">Selecciona una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria); ?>">
                                                <?php echo htmlspecialchars($categoria); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="costo" class="form-label">Costo</label>
                                    <input type="number" class="form-control" id="costo" name="costo" required>
                                </div>
                                <button type="submit" name="add_pedido" class="btn btn-primary">Agregar Pedido</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar Pedido -->
            <div class="modal" id="editPedidoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Pedido</h5>
                            <button type="button" class="btn-close-text" onclick="toggleEditModal()">Cerrar</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" id="edit_id_pedido" name="id_pedido">
                                <div class="mb-3">
                                    <label for="edit_id_proveedor" class="form-label">Proveedor</label>
                                    <select class="form-control" id="edit_id_proveedor" name="id_proveedor" required>
                                        <option value="">Selecciona un proveedor</option>
                                        <?php foreach ($proveedores as $proveedor): ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['id']); ?>">
                                                <?php echo htmlspecialchars($proveedor['empresa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_categoria" class="form-label">Categoría</label>
                                    <select class="form-control" id="edit_categoria" name="categoria" required>
                                        <option value="">Selecciona una categoría</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria); ?>">
                                                <?php echo htmlspecialchars($categoria); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_costo" class="form-label">Costo</label>
                                    <input type="number" class="form-control" id="edit_costo" name="costo" required>
                                </div>
                                <button type="submit" name="update_pedido" class="btn btn-primary">Actualizar Pedido</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Agregar Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script>
            function toggleEditModal() {
                var modal = document.getElementById('editPedidoModal');
                modal.style.display = (modal.style.display === 'none' || modal.style.display === '') ? 'flex' : 'none';
            }

            function toggleAddModal() {
                var modal = document.getElementById('addPedidoModal');
                modal.style.display = (modal.style.display === 'none' || modal.style.display === '') ? 'flex' : 'none';
            }

            function editPedido(id) {
                // Hacer una solicitud AJAX para obtener los datos del pedido
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_pedido.php?id_pedido=' + id, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var pedido = JSON.parse(xhr.responseText);
                        if (pedido) {
                            document.getElementById('edit_id_pedido').value = pedido.id_pedido;
                            document.getElementById('edit_id_proveedor').value = pedido.id_proveedor;
                            document.getElementById('edit_categoria').value = pedido.categoria;
                            document.getElementById('edit_costo').value = pedido.costo;
                            toggleEditModal();
                        } else {
                            alert('Error al obtener los datos del pedido.');
                        }
                    }
                };
                xhr.send();
            }

            // Datos para la gráfica
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($pedidos, 'proveedor')); ?>,
                    datasets: [{
                        label: 'Costo de Pedidos',
                        data: <?php echo json_encode(array_column($pedidos, 'costo')); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Costo: $' + tooltipItem.raw.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        
    </body>

    </html>