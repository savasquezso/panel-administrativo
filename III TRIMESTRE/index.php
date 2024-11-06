<?php
include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
    <section class="header">
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
    <section class="main">
        <div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="#" id="active--link">
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
        <div class="main--content">
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">General</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Hoy</option>
                        <option value="lastweek">Esta semana</option>
                        <option value="lastmonth">Mes pasado</option>
                        <option value="lastyear">Año Pasado</option>
                        <option value="alltime">Historico</option>
                    </select>
                </div>
                <div class="cards">
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Proveedores</h5>
                                <h1>152</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>114%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>10</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2</span>
                        </div>
                    </div>
                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Total Clientes</h5>
                                <h1>1145</h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>82%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>230</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>45</span>
                        </div>
                    </div>
                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Reportes</h5>
                                <h1>102</h1>
                            </div>
                            <i class="ri-calendar-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>27%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>31</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>23</span>
                        </div>
                    </div>
                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Productividad</h5>
                                <h1>15</h1>
                            </div>
                            <i class="ri-hotel-bed-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>8%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>11</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="proveedores">
                <div class="title">
                    <h2 class="section--title">Proveedores</h2>
                    <div class="doctors--right--btns">
                        <select id="tipoProveedor" class="dropdown doctor--filter">
                            <option value="">Filtrar</option>
                            <option value="Frutas">Frutas</option>
                            <option value="Verduras">Verduras</option>
                            <option value="Tuberculos">Tuberculos</option>
                            <option value="Huevos">Huevos</option>
                            <option value="Especias">Especias</option>
                            <option value="Hortalizas">Hortalizas</option>
                        </select>
                        <button class="add" onclick="showAddProveedorForm()"><i class="ri-add-line"></i>Agregar Proveedor</button>
                    </div>
                </div>
                <div id="addProveedorForm" style="display:none;">
                    <input type="text" id="nombreProveedor" placeholder="Nombre del proveedor">
                    <button onclick="addProveedor()">Guardar Proveedor</button>
                </div>
                <div class="doctors--cards" id="proveedoresContainer">
                    
                </div>
            </div>

            <div class="productos">
                <div class="title">
                    <h2 class="section--title">Domicilios</h2>
                    <button class="add" onclick="showAddDomicilioForm()"><i class="ri-add-line"></i>Agregar domicilio</button>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Genero</th>
                                <th>Edad</th>
                                <th>Estado</th>
                                <th>Modificar / Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="domiciliosTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="addDomicilioForm" style="display:none;">
                <input type="text" id="nombreDomicilio" placeholder="Nombre">
                <input type="date" id="fechaDomicilio">
                <select id="generoDomicilio">
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
                <input type="number" id="edadDomicilio" placeholder="Edad">
                <select id="estadoDomicilio">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                <button onclick="addDomicilio()">Guardar Domicilio</button>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="main.js"></script>
</body>
</html>
