<?php
include 'conexion.php'; // Asegúrate de incluir el archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];
    $id_usuario = $_SESSION['user_id']; // Suponiendo que tienes guardado el ID del usuario en la sesión

    // Consulta SQL para obtener los datos del paciente con el ID proporcionado y asociado al usuario actual
    $sql = "SELECT * FROM pacientes WHERE id = $id AND id_usuario = $id_usuario";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Crear un arreglo asociativo con los datos del paciente
        $paciente = array(
            "nombre_apellido" => $row["nombre_apellido"],
            "fecha_nacimiento" => $row["fecha_nacimiento"],
            "fecha_ingreso" => $row["fecha_ingreso"],
            "dni" => $row["dni"],
            "edad" => $row["edad"],
            "estado_civil" => $row["estado_civil"],
            "nacionalidad" => $row["nacionalidad"],
            "obra_social1" => $row["obra_social1"],
            "obra_social2" => $row["obra_social2"],
            "responsable_nombre" => $row["responsable_nombre"],
            "parentesco" => $row["parentesco"],
            "responsable_telefono" => $row["responsable_telefono"],
            "otro_responsable" => $row["otro_responsable"],
            "otro_telefono" => $row["otro_telefono"],
            "anamnesis" => $row["anamnesis"],
            "alergias" => $row["alergias"],
            "antecedentes_personales" => $row["antecedentes_personales"],
            "antecedentes_patologicos" => $row["antecedentes_patologicos"],
            "intervenciones_quirurgicas" => $row["intervenciones_quirurgicas"]
        );

        // Devuelve los datos del paciente en formato JSON
        echo json_encode($paciente);
    } else {
        echo "Paciente no encontrado";
    }
} else {
    echo "Solicitud no válida";
}

$conexion->close();
?>
