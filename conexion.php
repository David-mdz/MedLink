<?php
$servername = "localhost"; // Cambia esto si tu servidor MySQL tiene una dirección diferente
$username = "root"; // Reemplaza con el nombre de usuario de tu base de datos
$password = "422542"; // Reemplaza con la contraseña de tu base de datos
$database = "historiaclinica"; // Reemplaza con el nombre de tu base de datos


// Crear una conexión a la base de datos
$conexion = new mysqli($servername, $username, $password, $database);

// Verificar si hay errores en la conexión
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
}
?>
