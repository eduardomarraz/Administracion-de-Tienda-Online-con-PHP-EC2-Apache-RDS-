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
$id = $_POST['id'];
$nuevoNombre = $_POST['nuevo-nombre'];
$nuevoPrecio = $_POST['nuevo-precio'];
 
// Actualizar datos en la base de datos
$sql = "UPDATE productos SET nombre='$nuevoNombre', precio='$nuevoPrecio' WHERE id=$id";
 
if ($conn->query($sql) === TRUE) {
    echo "Producto modificado correctamente";
} else {
    echo "Error al modificar producto: " . $conn->error;
}
 
$conn->close();
?>

