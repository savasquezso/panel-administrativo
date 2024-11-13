<?php
include 'Login.php';
include 'ControlInventarioUsuario.php';
include 'SuperAdmin.php';
include 'Account.php';
include 'Orden.php';
include 'Producto.php';
include 'Venta.php';
include 'AnalisisVenta.php';
include 'AnalisisProveedor.php';

echo "<h2>Prueba de Clases en PHP</h2>";

$login = new Login("usuarioEjemplo", "contraseña123");
echo "<h3>Login</h3>";
echo $login->autenticar("usuarioEjemplo", "contraseña123") . "<br>";
echo $login->resetPassword("usuario@ejemplo.com") . "<br>";

$inventario = new ControlInventarioUsuario();
echo "<h3>Control de Inventario</h3>";
echo $inventario->agregarProducto(1, "Producto1", 50) . "<br>";
echo $inventario->ajustarStock(1, 100) . "<br>";


$account = new Account(1, "Admin", "admin@ejemplo.com", "12345", "Administrador");
echo "<h3>Account</h3>";
echo $account->crearUsuario("UsuarioNuevo", "nuevo@ejemplo.com", "pass123", "Usuario") . "<br>";

$orden = new Orden(1, date("Y-m-d"), 1);
echo "<h3>Orden</h3>";
echo $orden->agregarProducto("Producto1", 2) . "<br>";
echo $orden->calcularTotal() . "<br>";
echo $orden->cambiarEstado("Enviado") . "<br>";

$producto = new Producto(1, "Laptop", "Laptop de alta gama", 1500, 10);
echo "<h3>Producto</h3>";
echo $producto->agregarProducto() . "<br>";
echo $producto->verificarStock() . "<br>";

$venta = new Venta(1, "Producto1", 2, 200, date("Y-m-d")); 
echo "<h3>Venta</h3>";
echo $venta->registrarVenta() . "<br>"; 
echo "Total de la venta: \$" . $venta->obtenerTotal() . "<br>"; 

$analisisVenta = new AnalisisVenta();
echo "<h3>Análisis de Ventas</h3>";
echo "Ganancias de hoy: " . $analisisVenta->calcularGananciasHoy() . "<br>";
echo "Ganancias de ayer: " . $analisisVenta->calcularGananciasAyer() . "<br>";
echo $analisisVenta->compararGanancias() . "<br>";
echo $analisisVenta->mostrarTendencias() . "<br>";

$analisisProveedor = new AnalisisProveedor(1, 1000, 5);
echo "<h3>Análisis de Proveedor</h3>";
echo "Promedio de gasto: " . $analisisProveedor->calcularPromedioGasto() . "<br>";
echo $analisisProveedor->mostrarPromedio() . "<br>";
?>