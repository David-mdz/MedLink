<?php
include 'conexion.php'; // Incluye tu archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['mes'])) {
    $idPaciente = $_GET['id'];
    $mesSeleccionado = $_GET['mes'];

    $sqlConsulta = "SELECT insumo, SUM(cantidad) AS consumo_mensual FROM insumos WHERE id_paciente = ? AND fecha_registro LIKE CONCAT('%-', ?, '-%') GROUP BY insumo";
    $stmtConsulta = $conexion->prepare($sqlConsulta);

    if ($stmtConsulta) {
        $stmtConsulta->bind_param("is", $idPaciente, $mesSeleccionado);
        $stmtConsulta->execute();
        $resultConsulta = $stmtConsulta->get_result();

        if ($resultConsulta->num_rows > 0) {
            $consumoMensual = [];
            while ($row = $resultConsulta->fetch_assoc()) {
                $consumoMensual[$row['insumo']] = $row['consumo_mensual'];
            }

            echo json_encode($consumoMensual);
        } else {
            echo json_encode(["error" => "No se encontraron datos para el paciente y mes especificados"]);
        }
    } else {
        echo json_encode(["error" => "Error en la preparación de la consulta"]);
    }
} else {
    echo json_encode(["error" => "Solicitud inválida"]);
}
?>

