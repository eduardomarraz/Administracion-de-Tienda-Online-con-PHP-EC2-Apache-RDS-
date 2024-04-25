<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <header>
        <h1>Gestión de Productos</h1>
    </header>
    <main>
        <section id="agregar-producto">
            <h2>Agregar Producto</h2>
            <form action="agregar_producto.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" min="0.01" step="0.01" required>
                <button type="submit">Agregar Producto</button>
            </form>
        </section>
 
        <section id="listar-productos">
            <h2>Listar Productos</h2>
            <ul>
                <?php
                // Conexión a la base de datos
                $servername = "database-1.crk2mbd9rnul.us-east-1.rds.amazonaws.com";
                $username = "admin";
                $password = "admin1234"; // Contraseña actualizada
                $dbname = "BDed1"; // Nombre de la base de datos actualizado
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                // Verificar la conexión
                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }
                
                // Consulta para obtener todos los productos
                $sql = "SELECT id, nombre, precio FROM productos"; // Nombre de la tabla actualizado
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<li>ID: " . $row["id"]. " - Nombre: " . $row["nombre"]. " - Precio: $" . $row["precio"]. "</li>";
                    }
                } else {
                    echo "No hay productos registrados";
                }
                $conn->close();
                ?>
            </ul>
        </section>
 
        <section id="modificar-producto">
            <h2>Modificar Producto</h2>
            <form action="modificar_producto.php" method="POST">
                <label for="id">ID del Producto a Modificar:</label>
                <input type="number" id="id" name="id" required>
                <label for="nuevo-nombre">Nuevo Nombre:</label>
                <input type="text" id="nuevo-nombre" name="nuevo-nombre">
                <label for="nuevo-precio">Nuevo Precio:</label>
                <input type="number" id="nuevo-precio" name="nuevo-precio" min="0.01" step="0.01">
                <button type="submit">Modificar Producto</button>
            </form>
        </section>
 
        <section id="borrar-producto">
            <h2>Borrar Producto</h2>
            <form action="borrar_producto.php" method="POST">
                <label for="id-borrar">ID del Producto a Borrar:</label>
                <input type="number" id="id-borrar" name="id-borrar" required>
                <button type="submit">Borrar Producto</button>
            </form>
        </section>
    </main>
    <footer>
        <p>Administración de mi tienda</p>
    </footer>
</body>
</html>

