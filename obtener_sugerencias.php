<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Conexión a la base de datos
include 'conexion.php';

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los parámetros de la solicitud AJAX
$tipo = $_GET['tipo'];
$input = $_GET['input'];

// Preparar la consulta SQL según el tipo (medicación o droga)
if ($tipo === 'medicacion') {
    $sql = "SELECT medicacion FROM autocompleteado WHERE medicacion LIKE '%$input%'";
} elseif ($tipo === 'droga') {
    $sql = "SELECT droga FROM autocompleteado WHERE droga LIKE '%$input%'";
} else {
    // Tipo no válido, devuelve un array vacío
    echo json_encode(array());
    exit();
}

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Almacenar los resultados en un array
    $sugerencias = array();
    while ($fila = $resultado->fetch_assoc()) {
        // Agregar cada sugerencia al array
        if ($tipo === 'medicacion') {
            $sugerencias[] = $fila['medicacion'];
        } elseif ($tipo === 'droga') {
            $sugerencias[] = $fila['droga'];
        }
    }
    // Devolver las sugerencias como un JSON
    echo json_encode($sugerencias);
} else {
    // No se encontraron sugerencias, devuelve un array vacío
    echo json_encode(array());
}

// Cerrar la conexión
$conexion->close();
?>
