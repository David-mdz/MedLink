<?php
include 'conexion.php';
session_start(); // Iniciar la sesión si no se ha iniciado aún

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar los datos que vienen de la solicitud
    $idPaciente = isset($_POST["id"]) ? intval($_POST["id"]) : null; // Asegúrate de que sea un número entero
    $idUsuario = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : null; // Asegúrate de que sea un número entero

    if ($idPaciente && $idUsuario) {
        // Comenzar una transacción
        $conexion->begin_transaction();

        try {
            // Eliminar los datos del paciente en la tabla 'examen_fisico' asociados al usuario
            $sql = "DELETE FROM examen_fisico WHERE id_paciente = ? AND id_usuario = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $idPaciente, $idUsuario);
            $stmt->execute();

            // Eliminar los datos del paciente en la tabla 'medicacion' asociados al usuario
            $sql = "DELETE FROM medicacion WHERE id_paciente = ? AND id_usuario = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $idPaciente, $idUsuario);
            $stmt->execute();

            // Eliminar los datos del paciente en la tabla 'pacientes' asociados al usuario
            $sql = "DELETE FROM pacientes WHERE id = ? AND id_usuario = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $idPaciente, $idUsuario);
            $stmt->execute();

            // Confirmar la transacción
            $conexion->commit();

            echo "success"; // Indica éxito
        } catch (Exception $e) {
            // Si ocurre un error, deshacer la transacción
            $conexion->rollback();
            echo "error"; // Indica error
        }

        // Cerrar la conexión
        $stmt->close();
    } else {
        echo "error"; // Indica error si no se proporciona un ID de paciente o usuario válido
    }
}
?>
