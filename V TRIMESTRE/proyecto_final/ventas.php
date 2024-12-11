<?php
include 'db_connection.php';

// Obtener ventas diarias de los últimos 7 días
$sql_sales = "
    SELECT DATE(fecha_compra) AS fecha, SUM(costo) AS total_ventas
    FROM ventas
    WHERE fecha_compra >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(fecha_compra)
    ORDER BY fecha
";
$result_sales = $conn->query($sql_sales);

$sales_data = [];
$dates = [];
$totals = [];

if ($result_sales->num_rows > 0) {
    while ($row = $result_sales->fetch_assoc()) {
        $dates[] = $row['fecha'];
        $totals[] = (float)$row['total_ventas'];
    }
}

// Convertir arrays a formato JSON
$sales_data = json_encode(['dates' => $dates, 'totals' => $totals]);

// Obtener ventas del día actual
$sql_today = "SELECT SUM(costo) AS total_ventas_today FROM ventas WHERE DATE(fecha_compra) = CURDATE()";
$result_today = $conn->query($sql_today);
$total_ventas_today = 0;

if ($result_today->num_rows > 0) {
    $row_today = $result_today->fetch_assoc();
    $total_ventas_today = $row_today["total_ventas_today"];
}

// Obtener ventas del día anterior
$sql_yesterday = "SELECT SUM(costo) AS total_ventas_yesterday FROM ventas WHERE DATE(fecha_compra) = CURDATE() - INTERVAL 1 DAY";
$result_yesterday = $conn->query($sql_yesterday);
$total_ventas_yesterday = 0;

if ($result_yesterday->num_rows > 0) {
    $row_yesterday = $result_yesterday->fetch_assoc();
    $total_ventas_yesterday = $row_yesterday["total_ventas_yesterday"];
}

$percentage_change = 0;
$change_type = 'success'; // default to success

if ($total_ventas_yesterday > 0) {
    $percentage_change = (($total_ventas_today - $total_ventas_yesterday) / $total_ventas_yesterday) * 100;
    if ($percentage_change < 0) {
        $change_type = 'danger'; // use danger for decrease
        $percentage_change = abs($percentage_change); // Show positive percentage
    }
} else if ($total_ventas_today > 0) {
    $percentage_change = 100; // If yesterday's total was 0 and today's total is positive
}

// Cerrar la conexión
$conn->close();

session_start();

// Verifica si el usuario está logueado y si tiene el rol correcto
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    // Si no es administrador, redirige a productos.php o muestra un mensaje
    header("Location: producto.php");
    exit(); // Detiene la ejecución del script
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pagina.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <h1>Ventas</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>

        <div class="card-container">
            <div class="card">
                <h6>Ventas Totales</h6>
                <h3>$<?php echo number_format($total_ventas_today, 2); ?></h3>
                <div class="small <?php echo $change_type; ?>">
                    <?php
                    echo "Ventas de Hoy: $" . number_format($total_ventas_today, 2) . "<br>";
                    echo "Ventas de Ayer: $" . number_format($total_ventas_yesterday, 2) . "<br>";

                    if ($percentage_change > 0) {
                        echo " " . number_format($percentage_change, 2) . "%";
                    } else if ($percentage_change < 0) {
                        echo " " . number_format(abs($percentage_change), 2) . "%";
                    } else {
                        echo "Sin variación";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Gráfica Comparativa -->
        <div class="card" style="width: 800px; margin: auto;"> <!-- Ajusta el ancho según sea necesario -->
    <div class="card-header">
        <h2>Ventas Diarias de los Últimos 7 Días</h2>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

        <div class="table-container-ve">
            <h2>Listado de Ventas</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Número</th>
                        <th>Costo</th>
                        <th>Fecha de Compra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conexión a la base de datos
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    // Eliminar Venta
                    if (isset($_POST['eliminar_venta'])) {
                        $id_eliminar = $_POST['id'];
                        $sql_eliminar = "DELETE FROM ventas WHERE id = ?";
                        $stmt = $conn->prepare($sql_eliminar);
                        $stmt->bind_param("i", $id_eliminar);

                        if ($stmt->execute()) {
                            echo "<p style='color: green;'>Venta eliminada con éxito.</p>";
                        } else {
                            echo "<p style='color: red;'>Error al eliminar la venta: " . $conn->error . "</p>";
                        }
                    }

                    // Actualizar Venta
                    if (isset($_POST['actualizar_venta'])) {
                        $id = $_POST['id'];
                        $nombre = $_POST['nombre'];
                        $cedula = $_POST['cedula'];
                        $numero = $_POST['numero'];
                        $costo = $_POST['costo'];
                        $fecha_compra = $_POST['fecha_compra'];

                        $sql_actualizar = "UPDATE ventas SET nombre=?, cedula=?, numero=?, costo=?, fecha_compra=? WHERE id=?";
                        $stmt = $conn->prepare($sql_actualizar);
                        $stmt->bind_param("sssssi", $nombre, $cedula, $numero, $costo, $fecha_compra, $id);

                        if ($stmt->execute()) {
                            echo "<p style='color: green;'>Venta actualizada con éxito.</p>";
                        } else {
                            echo "<p style='color: red;'>Error al actualizar la venta: " . $conn->error . "</p>";
                        }
                    }

                    // Mostrar Datos de la Tabla Ventas
                    $sql = "SELECT id, nombre, cedula, numero, costo, fecha_compra FROM ventas";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["nombre"] . "</td>
                                    <td>" . $row["cedula"] . "</td>
                                    <td>" . $row["numero"] . "</td>
                                    <td>$" . number_format($row["costo"], 2) . "</td>
                                    <td>" . $row["fecha_compra"] . "</td>
                                    <td>
                                        <form method='post' action='' style='display:inline;'>
                                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                                            <button type='submit' name='eliminar_venta' class='btn btn-danger'>ELIMINAR</button>
                                        </form>
                                        <button class='btn btn-warning editar-venta-btn' style='margin-left: 5px;' 
                                            data-id='" . $row["id"] . "' 
                                            data-nombre='" . $row["nombre"] . "' 
                                            data-cedula='" . $row["cedula"] . "' 
                                            data-numero='" . $row["numero"] . "' 
                                            data-costo='" . $row["costo"] . "' 
                                            data-fecha='" . $row["fecha_compra"] . "'>EDITAR</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay datos disponibles</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <div class="form-container-ve">
            <h2>Agregar Nueva Venta</h2>
            <form method="post" action="agregar_venta.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ ]+" title="Solo se permiten letras y espacios" required>
                </div>
                <div class="form-group">
                    <label for="cedula">Cédula:</label>
                    <input type="text" id="cedula" name="cedula" pattern="\d{10}" maxlength="10" required title="Debe ingresar exactamente 10 dígitos numéricos">
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" pattern="\d{1,10}" maxlength="10" required title="Debe ingresar entre 1 y 10 dígitos numéricos" inputmode="numeric">
                </div>
                <div class="form-group">
                    <label for="costo">Costo:</label>
                    <input type="number" id="costo" name="costo" class="form-control" required step="0.01" min="0" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label for="fecha_compra">Fecha de Compra:</label>
                    <input type="date" id="fecha_compra" name="fecha_compra" class="form-control small-input" required>
                </div>

                <style>
                    .small-input {
                        width: 200px;
                        /* Ajusta el ancho según sea necesario */
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Función para obtener la fecha actual en formato YYYY-MM-DD
                        function getCurrentDate() {
                            const today = new Date();
                            const yyyy = today.getFullYear();
                            let mm = today.getMonth() + 1; // Enero es 0!
                            let dd = today.getDate();

                            // Asegurarse de que el mes y el día tengan dos dígitos
                            if (mm < 10) {
                                mm = '0' + mm;
                            }
                            if (dd < 10) {
                                dd = '0' + dd;
                            }

                            return yyyy + '-' + mm + '-' + dd;
                        }

                        // Obtener el campo de entrada de fecha
                        const fechaCompraInput = document.getElementById('fecha_compra');

                        // Obtener la fecha actual en formato YYYY-MM-DD
                        const currentDate = getCurrentDate();

                        // Establecer la fecha mínima, máxima y el valor predeterminado como la fecha actual
                        fechaCompraInput.setAttribute('min', currentDate);
                        fechaCompraInput.setAttribute('max', currentDate);
                        fechaCompraInput.value = currentDate;
                    });
                </script>


                <button type="submit" class="btn btn-primary">Agregar Venta</button>
            </form>
        </div>

        <!-- Modal para Editar Venta -->
        <div class="modal fade" id="editarVentaModal" tabindex="-1" role="dialog" aria-labelledby="editarVentaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarVentaModalLabel">Editar Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editarVentaForm" method="post" action="">
                            <div class="form-group">
                                <label for="modal_nombre">Nombre:</label>
                                <input type="text" id="modal_nombre" name="nombre" class="form-control" pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ ]+" title="Solo se permiten letras y espacios" required>
                            </div>
                            <div class="form-group">
                                <label for="modal_cedula">Cédula:</label>
                                <input type="text" id="modal_cedula" name="cedula" class="form-control" pattern="\d{10}" maxlength="10" required title="Debe ingresar exactamente 10 dígitos numéricos" inputmode="numeric">
                            </div>
                            <div class="form-group">
                                <label for="modal_numero">Número:</label>
                                <input type="text" id="modal_numero" name="numero" class="form-control" pattern="\d{1,10}" maxlength="10" required title="Debe ingresar entre 1 y 10 dígitos numéricos" inputmode="numeric">
                            </div>
                            <div class="form-group">
                                <label for="modal_costo">Costo:</label>
                                <input type="number" id="modal_costo" name="costo" class="form-control" required step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label for="modal_fecha_compra">Fecha de Compra:</label>
                                <input type="date" id="modal_fecha_compra" name="fecha_compra" class="form-control" required>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Función para obtener la fecha actual en formato YYYY-MM-DD
                                    function getCurrentDate() {
                                        const today = new Date();
                                        const yyyy = today.getFullYear();
                                        let mm = today.getMonth() + 1; // Enero es 0!
                                        let dd = today.getDate();

                                        // Asegurarse de que el mes y el día tengan dos dígitos
                                        if (mm < 10) {
                                            mm = '0' + mm;
                                        }
                                        if (dd < 10) {
                                            dd = '0' + dd;
                                        }

                                        return yyyy + '-' + mm + '-' + dd;
                                    }

                                    // Obtener el campo de entrada de fecha en el modal
                                    const fechaCompraInput = document.getElementById('modal_fecha_compra');

                                    // Obtener la fecha actual
                                    const currentDate = getCurrentDate();

                                    // Establecer la fecha mínima y máxima como la fecha actual
                                    fechaCompraInput.setAttribute('min', currentDate);
                                    fechaCompraInput.setAttribute('max', currentDate);

                                    // Establecer el valor predeterminado como la fecha actual
                                    fechaCompraInput.value = currentDate;
                                });
                            </script>


                            <input type="hidden" id="modal_id" name="id">
                            <button type="submit" name="actualizar_venta" class="btn btn-primary">Actualizar Venta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editarButtons = document.querySelectorAll('.editar-venta-btn');
                editarButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const nombre = this.getAttribute('data-nombre');
                        const cedula = this.getAttribute('data-cedula');
                        const numero = this.getAttribute('data-numero');
                        const costo = this.getAttribute('data-costo');
                        const fecha = this.getAttribute('data-fecha');

                        document.getElementById('modal_id').value = id;
                        document.getElementById('modal_nombre').value = nombre;
                        document.getElementById('modal_cedula').value = cedula;
                        document.getElementById('modal_numero').value = numero;
                        document.getElementById('modal_costo').value = costo;
                        document.getElementById('modal_fecha_compra').value = fecha;

                        $('#editarVentaModal').modal('show');
                    });
                });
            });

            // Obtener datos PHP en formato JSON
            const salesData = <?php echo $sales_data; ?>;

            // Configurar el gráfico
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.dates,
                    datasets: [{
                        label: 'Ventas Diarias',
                        data: salesData.totals,
                        backgroundColor: 'rgba(86, 182, 252, 0.2)',
                        borderColor: 'rgba(0, 47, 185 , 0.5)',
                        borderWidth: 3,
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Fecha'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Ventas'
                            }
                        }
                    }
                }
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
</body>

</html>