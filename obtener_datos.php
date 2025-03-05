<?php
include 'conexion.php';
include 'auth.php';

// Verificar la sesión del usuario
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$idPaciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : null;
$fechaSeleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : null;

if ($idPaciente !== null && $fechaSeleccionada !== null && $user_id !== null) {
    // Consulta SQL para obtener los datos según el paciente, la fecha y el usuario
    $sql = "SELECT medicacion, droga, ayuna, desayuno, almuerzo, merienda, cena, observaciones, cuadro_evolucion FROM medicacion WHERE id_paciente = ? AND fecha = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iss", $idPaciente, $fechaSeleccionada, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Construir el array de datos a convertir a JSON
            $data = array(
                'medicacion' => $row['medicacion'],
                'droga' => $row['droga'],
                'ayuna' => $row['ayuna'],
                'desayuno' => $row['desayuno'],
                'almuerzo' => $row['almuerzo'],
                'merienda' => $row['merienda'],
                'cena' => $row['cena'],
                'observaciones' => $row['observaciones'],
                'cuadro_evolucion' => $row['cuadro_evolucion']
            );

            // Devolver los datos en formato JSON
            echo json_encode($data);
        } else {
            echo json_encode(array("message" => "No se encontraron datos para la fecha seleccionada o el usuario no tiene acceso a estos datos."));
        }
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Error en la consulta: " . $conexion->error));
    }
} else {
    echo json_encode(array("error" => "Parámetros no válidos o usuario no autenticado."));
}
?>
