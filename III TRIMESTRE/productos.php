<?php
include 'db_connection.php';

$sql = "SELECT * FROM domicilios ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <title>Ver Productos</title>
</head>
<body>
<section class="main">
        <div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="index.php" id="active--link">
                        <span class="icon icon-1"><i class="ri-layout-grid-line"></i></span>
                        <span class="sidebar--item">Panel</span>
                    </a>
                </li>
                <li>
                    <a href="productos.php">
                        <span class="icon icon-2"><i class="ri-calendar-2-line"></i></span>
                        <span class="sidebar--item">Productos</span>
                    </a>
                </li>
                <li>
                    <a href="proveedor.php">
                        <span class="icon icon-3"><i class="ri-user-2-line"></i></span>
                        <span class="sidebar--item" style="white-space: nowrap;">Proveedores</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon icon-4"><i class="ri-user-line"></i></span>
                        <span class="sidebar--item">Total Clientes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon icon-5"><i class="ri-line-chart-line"></i></span>
                        <span class="sidebar--item">Reporte</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon icon-6"><i class="ri-customer-service-line"></i></span>
                        <span class="sidebar--item">Soporte</span>
                    </a>
                </li>
            </ul>
            <ul class="sidebar--bottom-items">
                <li>
                    <a href="#">
                        <span class="icon icon-7"><i class="ri-settings-3-line"></i></span>
                        <span class="sidebar--item">Configuraciones</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon icon-8"><i class="ri-logout-box-r-line"></i></span>
                        <span class="sidebar--item">Salir</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="logo">
            <i class="ri-menu-line icon icon-0 menu"></i>
            <h2>Fru<span>ver</span></h2>
        </div>
        <div class="search--notification--profile">
            <div class="search">
                <input type="text" placeholder="Buscar">
                <button><i class="ri-search-2-line"></i></button>
            </div>
            <div class="notification--profile">
                <div class="picon lock">
                    <i class="ri-lock-line"></i>
                </div>
                <div class="picon bell">
                    <i class="ri-notification-2-line"></i>
                </div>
                <div class="picon chat">
                    <i class="ri-wechat-2-line"></i>
                </div>
                <div class="picon profile">
                    <img src="img/TUBERCULOS.jpg" alt="">
                </div>
            </div>
        </div>
</section>    
        <section>  
        <section>

        <article> 
            <header>     
                <h1>Domicilios</h1>
            </header>
            <table border="1">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Género</th>
                    <th>Edad</th>
                    <th>Estado</th>
                </tr>
                <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["fecha"] . "</td>";
                            echo "<td>" . $row["genero"] . "</td>";
                            echo "<td>" . $row["edad"] . "</td>";
                            echo "<td>" . $row["estado"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay domicilios registrados</td></tr>";
                    }
                ?>
            </table> 
        </article>
    </section>
</section>
</div>
</body>
</html>

<?php
$conn->close();
?>