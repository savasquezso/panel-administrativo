Proyecto Fruver
Este proyecto es un sistema de gestión para un negocio de frutas y verduras, llamado "Fruver". Permite la administración de usuarios, proveedores, productos, ventas y pedidos. A continuación, se presenta una descripción general del proyecto, sus características y cómo configurarlo.

Características
Gestión de Usuarios: Permite crear, editar y eliminar usuarios con diferentes roles (administrador, bodega).
Gestión de Proveedores: Permite agregar, editar y eliminar proveedores.
Gestión de Productos: Permite agregar, editar y eliminar productos, así como gestionar su stock.
Gestión de Ventas: Permite registrar ventas y visualizar el historial de ventas.
Gestión de Pedidos: Permite realizar pedidos a proveedores y actualizar su estado.
Dashboard: Proporciona un resumen de las métricas clave, como total de ventas, total de productos y proveedores activos.
Tecnologías Utilizadas
PHP: Lenguaje de programación del lado del servidor.
MySQL: Sistema de gestión de bases de datos.
HTML/CSS: Para la estructura y el diseño de la interfaz de usuario.
JavaScript: Para la interactividad en el lado del cliente.
Bootstrap: Framework CSS para un diseño responsivo y moderno.
Instalación
Clonar el repositorio:

bash
Insert Code
Run
Copy code
git clone https://github.com/tu_usuario/tu_repositorio.git
Configurar el entorno:

Asegúrate de tener instalado XAMPP o un servidor web compatible con PHP y MySQL.
Copia el contenido del repositorio en la carpeta htdocs de tu instalación de XAMPP.
Crear la base de datos:

Abre phpMyAdmin (normalmente en http://localhost/phpmyadmin).
Crea una nueva base de datos llamada fruver_db.
Importa el archivo SQL que contiene la estructura de la base de datos (si está disponible en el repositorio).
Configurar la conexión a la base de datos:

Asegúrate de que los parámetros de conexión en los archivos PHP (como db_connection.php) sean correctos:
php
Insert Code
Run
Copy code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruver_db";
Ejecutar el servidor:

Inicia el servidor Apache y MySQL desde el panel de control de XAMPP.
Accede a la aplicación a través de tu navegador en http://localhost/tu_repositorio.
Uso
Iniciar sesión: Utiliza las credenciales de un usuario existente para acceder al sistema.
Navegar por las secciones: Utiliza el menú lateral para acceder a las diferentes secciones del sistema (usuarios, proveedores, productos, ventas, pedidos).
Realizar acciones: Puedes crear, editar y eliminar registros según sea necesario.
Contribuciones
Las contribuciones son bienvenidas. Si deseas contribuir a este proyecto, por favor sigue estos pasos:

Haz un fork del repositorio.
Crea una nueva rama (git checkout -b feature/nueva-caracteristica).
Realiza tus cambios y haz commit (git commit -m 'Agregada nueva característica').
Haz push a la rama (git push origin feature/nueva-caracteristica).
Abre un Pull Request.
Licencia
Este proyecto está bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.

Contacto
Si tienes alguna pregunta o sugerencia, no dudes en contactarme a través de innova@gmail.com.

¡Gracias por tu interés en el proyecto Fruver!
