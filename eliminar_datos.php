<?php
include 'conexion.php';

$idPaciente = isset($_POST['id_paciente']) ? $_POST['id_paciente'] : null;
$fechaSeleccionada = isset($_POST['fecha']) ? $_POST['fecha'] : null;

$response = array(); // Crear un arreglo para la respuesta

if ($idPaciente !== null && $fechaSeleccionada !== null) {
    // Consulta SQL para eliminar los datos según el paciente y la fecha
    $sql = "DELETE FROM medicacion WHERE id_paciente = ? AND fecha = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("is", $idPaciente, $fechaSeleccionada);
        
        if ($stmt->execute()) {
            // Eliminación exitosa, mensaje de éxito en la respuesta
            $response['success'] = true;
            $response['message'] = "Registros eliminados con éxito.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error al eliminar datos: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Error en la consulta: " . $conexion->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = "Parámetros no válidos.";
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
