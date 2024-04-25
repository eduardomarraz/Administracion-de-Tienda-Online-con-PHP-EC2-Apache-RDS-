<?php
// Conexión a la base de datos (debes completar con tus propios datos)
$servername = "database-1.crk2mbd9rnul.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "admin1234";
$dbname = "BDed1";
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
 
// Recibir datos del formulario
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
 
// Insertar datos en la base de datos
$sql = "INSERT INTO productos (nombre, precio) VALUES ('$nombre', '$precio')";
 
if ($conn->query($sql) === TRUE) {
    echo "Producto agregado correctamente";
} else {
    echo "Error al agregar producto: " . $conn->error;
}
 
$conn->close();
?>
