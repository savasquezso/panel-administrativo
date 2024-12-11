<?php
include 'db_connection.php';

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
$change_type = 'up'; // default to 'up' for increase
if ($total_ventas_yesterday > 0) {
    $percentage_change = (($total_ventas_today - $total_ventas_yesterday) / $total_ventas_yesterday) * 100;
    if ($percentage_change < 0) {
        $change_type = 'down'; // If negative change
        $percentage_change = abs($percentage_change); // Positive display
    }
}



// Consulta para obtener el total de pedidos
$sql_total_pedidos = "SELECT COUNT(*) AS total_pedidos FROM pedidos_proveedor"; // Asegúrate de que 'pedidos_proveedor' sea el nombre correcto de tu tabla
$result_total = $conn->query($sql_total_pedidos);

$total_pedidos = 0; // Inicializa la variable

if ($result_total) {
    $row = $result_total->fetch_assoc();
    $total_pedidos = $row['total_pedidos'];
} else {
    echo "Error al contar los pedidos: " . $conn->error; // Manejo de errores
}



// Consulta para obtener productos con stock por debajo del mínimo
// Consulta para obtener productos con stock por debajo del mínimo
$sql_stock_minimo = "SELECT nombre, cantidad AS stock_actual, stock_minimo FROM stock WHERE cantidad <= stock_minimo";
$result_stock_minimo = $conn->query($sql_stock_minimo);

$productos_bajo_stock = [];
if ($result_stock_minimo->num_rows > 0) {
    while ($row = $result_stock_minimo->fetch_assoc()) {
        $productos_bajo_stock[] = $row; // Almacena los productos en un array
    }
}

// Consulta para obtener los pedidos recientes de la tabla pedidos_proveedor
$sql_recent_orders = "SELECT id_pedido, id_proveedor, categoria, costo, estado, cantidad, id_producto FROM pedidos_proveedor ORDER BY id_pedido DESC LIMIT 10";
$result_recent_orders = $conn->query($sql_recent_orders);

// Consulta para obtener los nombres de los productos
$sql_product_names = "SELECT id, nombre FROM stock";
$result_product_names = $conn->query($sql_product_names);

// Crear un array para almacenar los nombres de los productos
$product_names = [];
if ($result_product_names->num_rows > 0) {
    while ($row = $result_product_names->fetch_assoc()) {
        $product_names[$row['id']] = $row['nombre'];
    }
}

$sql = "
    SELECT pp.id_pedido, p.empresa, pp.categoria, pp.id_producto, pp.cantidad, pp.costo, pp.estado 
    FROM pedidos_proveedor pp
    JOIN proveedores p ON pp.id_proveedor = p.id
";
$result_recent_orders = $conn->query($sql);
session_start();

// Verifica si el usuario está logueado y si tiene el rol correcto
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    // Si no es administrador, redirige a productos.php o muestra un mensaje
    header("Location: producto.php");
    exit(); // Detiene la ejecución del script
}

// Inicializar el costo total
$total_cost = 0;

// Consulta para obtener el costo total de los pedidos con estado 'finalizado'
$sql_total_cost = "SELECT SUM(CAST(costo AS DECIMAL(10, 2))) AS total_cost FROM pedidos_proveedor WHERE estado = 'finalizado'";
$result_total_cost = $conn->query($sql_total_cost);

if ($result_total_cost) {
    $row_total_cost = $result_total_cost->fetch_assoc();
    $total_cost = $row_total_cost['total_cost'] ? $row_total_cost['total_cost'] : 0; // Manejo de NULL
} else {
    echo "Error en la consulta: " . $conn->error; // Manejo de errores
}

// Consulta para contar proveedores activos
$sql_active_providers = "SELECT COUNT(*) AS total_active FROM proveedores";
$result_active_providers = $conn->query($sql_active_providers);
$total_active_providers = $result_active_providers->fetch_assoc()['total_active'];




// Consulta para contar el total de productos
$sql_total_products = "SELECT COUNT(*) AS total_products FROM stock";
$result_total_products = $conn->query($sql_total_products);
$total_products = $result_total_products->fetch_assoc()['total_products'];
    ?>



    <!DOCTYPE html>
    <html><head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="principal.css">
    <title>Dashboard</title>
</head>
<body>
<div class="container">
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
    <h1>Principal</h1>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Salir</button>
    </form>
</div>
        <div class="main-container">
            <div class="welcome-card">
                <div>
                    <h3>Bienvenido</h3>
                    <p>Aqui esta tu resumen principal</p>
                    <button onclick="window.location.href='ver_pedidos.php';" style="background: white; color: #6366f1; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 10px; cursor: pointer;">
    Ver Pedidos
</button>
                </div>
                <img src="principal.svg" class="card-image" alt="Imagen SVG">
            </div>
           
            <div class="card">
    <div class="metric">
        <div class="circle-icon" style="background: rgba(82, 255, 82, 0.1);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#52ff52">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
        </div>
        <div>
            <div class="value">
                <?php echo number_format($total_pedidos); ?> <!-- Mostrar el total de pedidos -->
            </div>
            <div class="label">Total de Pedidos</div>
            <span class="percentage <?php echo $change_type; ?>">
                <?php echo $change_type === 'up' ? '+' : '-'; ?>
                <?php echo number_format($percentage_change, 2); ?>%
            </span>
        </div>
    </div>
</div>
<div class="card">
    <div class="metric">
        <div class="circle-icon" style="background: rgba(0,123,255,0.1);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#007bff">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        </div>
        <div>
            <div class="value"><?php echo number_format($total_products); ?></div> <!-- Mostrar el total de productos -->
            <div class="label">Total Productos</div>
        </div>
    </div>
</div>


<div class="card">
    <div class="metric">
        <div class="circle-icon" style="background: rgba(255,152,0,0.1);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#ff9800">
                <path d="M19 7h-1V6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v1H5c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm-7 12H8v-1h4v1zm6 0h-4v-1h4v1zm0-3H6V9h12v7z"/>
            </svg>
        </div>
        <div>
            <div class="value" id="total-pedidos"><?php echo number_format($total_cost, 2); ?></div> <!-- Mostrar el costo total -->
            <div class="label">Gastos en Pedidos</div> <!-- Cambia la etiqueta aquí -->
        </div>
    </div>
</div>

          <div class="card">
            <div class="metric">
              <div class="circle-icon" style="background: rgba(103,58,183,0.1);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#673ab7">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
              </div>
              <div>
                <div class="value"><?php echo number_format($total_active_providers); ?></div>
                <div class="label">Total Proveedores</div>
              </div>
            </div>
          </div>
        </div>
        
        
        
        
        <div class="stats-container">
        <div class="stats-card">
    <h4>Productos por Llegar a Stock Mínimo</h4>
    <table class="stock-table">
        <thead>
            <tr>
                <th>Nombre del Producto</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($productos_bajo_stock) > 0): ?>
                <?php foreach ($productos_bajo_stock as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock_actual']); ?></td>
                        <td><?php echo htmlspecialchars($producto['stock_minimo']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay productos por debajo del stock mínimo.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="orders-card">
    <h4>Pedidos Recientes</h4>
    <table class="orders-table">
        <thead>
            <tr>
                <th>ID </th>
                <th>Proveedor</th>
                <th>Categoría</th>
                <th>Producto</th> <!-- Cambiado a Nombre del Producto -->
                <th>Costo</th>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_recent_orders->num_rows > 0): ?>
                <?php while ($row = $result_recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['empresa']); ?></td> <!-- Cambiado a nombre de la empresa -->
                        <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                        <td><?php echo htmlspecialchars($product_names[$row['id_producto']]); ?></td> <!-- Mostrar el nombre del producto -->
                        <td><?php echo htmlspecialchars($row['costo']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay pedidos recientes.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


        </div>
    </div>
</div>

<script>
    // Función para obtener el total de pedidos desde ver_pedidos.php
    function obtenerTotalPedidos() {
        fetch('ver_pedidos.php?get_total=true')
            .then(response => response.json())
            .then(data => {
                // Actualiza el valor en la tarjeta
                document.getElementById('total-pedidos').innerText = data.total_pedidos;
            })
            .catch(error => console.error('Error al obtener el total de pedidos:', error));
    }

    // Llama a la función al cargar la página
    document.addEventListener('DOMContentLoaded', obtenerTotalPedidos);
</script>

<script>
// Balance Chart
const balanceOptions = {
    series: [{
        name: 'Balance',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    colors: ['#6366f1'],
    grid: {
        borderColor: '#f1f1f1',
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
    }
};

const balanceChart = new ApexCharts(document.querySelector("#balanceChart"), balanceOptions);
balanceChart.render();

// Orders Chart
const ordersOptions = {
    series: [70],
    chart: {
        height: 350,
        type: 'radialBar',
    },
    colors: ['#6366f1'],
    plotOptions: {
        radialBar: {
            hollow: {
                size: '70%',
            }
        },
    },
    labels: ['Orders']
};

const ordersChart = new ApexCharts(document.querySelector("#ordersChart"), ordersOptions);
ordersChart.render();

const chartData = [ // Datos de ejemplo para los gráficos
  [1000, 2000, 1500, 3000, 2500, 3654, 1800],
  [1800, 1600, 1400, 2200, 3000, 2500, 2000],
  [3000, 2500, 2000, 1500, 1800, 2200, 2600]
];

const chartColors = [ // Colores únicos para cada gráfico
  { borderColor: '#6c5ce7', backgroundColor: 'rgba(108, 92, 231, 0.2)' },
  { borderColor: '#00cec9', backgroundColor: 'rgba(0, 206, 201, 0.2)' },
  { borderColor: '#e17055', backgroundColor: 'rgba(225, 112, 85, 0.2)' }
];

chartData.forEach((data, index) => {
  const ctx = document.getElementById(`salesChart${index + 1}`).getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['', '', '', '', '', '', ''], // Etiquetas vacías
      datasets: [{
        data: data, // Datos específicos de cada gráfico
        borderColor: chartColors[index].borderColor, // Color de la línea
        backgroundColor: chartColors[index].backgroundColor, // Color del área bajo la línea
        tension: 0.4,
        fill: true,
      }]
    },
    
    options: {
      responsive: true,
      maintainAspectRatio: true, // Mantiene la proporción
      plugins: {
        legend: { display: false } // Oculta la leyenda
      },
      scales: {
        x: { display: false }, // Oculta el eje X
        y: { display: false }  // Oculta el eje Y
      }
    }
  });
});

</script></body></html>