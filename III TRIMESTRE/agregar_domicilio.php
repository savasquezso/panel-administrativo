<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Domicilio</title>
</head>
<body>
    <h1>Agregar Domicilio</h1>
    <form action="procesar_domicilio.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="Hombre">Hombre</option>
            <option value="Mujer">Mujer</option>
        </select><br><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br><br>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="cancelado">Cancelado</option>
        </select><br><br>

        <input type="submit" value="Agregar Domicilio">
    </form>
</body>
</html>