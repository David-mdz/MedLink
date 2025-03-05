<?php
include 'conexion.php';
include 'auth.php';


// Verifica si hay un usuario logueado
if (isset($_SESSION['user_id'])) {
    $id_usuario = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        $nombre_apellido = $_POST["nombre-apellido"];
        $fecha_nacimiento = $_POST["fecha-nacimiento"];
        $fecha_ingreso = $_POST["fecha-ingreso"];
        $dni = $_POST["dni"];
        $edad = $_POST["edad"];
        $estado_civil = $_POST["estado-civil"];
        $nacionalidad = $_POST["nacionalidad"];
        $obra_social1 = $_POST["obra-social1"];
        $obra_social2 = $_POST["obra-social2"];
        $responsable_nombre = $_POST["responsable-nombre"];
        $parentesco = $_POST["parentesco"];
        $responsable_telefono = $_POST["responsable-telefono"];
        $otro_responsable = $_POST["otro-responsable"];
        $otro_telefono = $_POST["otro-telefono"];
        $anamnesis = $_POST["anamnesis"];
        $alergias = $_POST["alergias"];
        $antecedentes_personales = $_POST["antecedentes-personales"];
        $antecedentes_patologicos = $_POST["antecedentes-patologicos"];
        $intervenciones_quirurgicas = $_POST["intervenciones-quirurgicas"];

        // Verificamos si el campo nombre_apellido no está vacío
        if (!empty($nombre_apellido)) {
            if (!empty($id)) {
                // Actualizamos los datos del paciente existente
                $sql = "UPDATE pacientes SET nombre_apellido=?, fecha_nacimiento=?, fecha_ingreso=?, dni=?, edad=?, estado_civil=?, nacionalidad=?, obra_social1=?, obra_social2=?, responsable_nombre=?, parentesco=?, responsable_telefono=?, otro_responsable=?, otro_telefono=?, anamnesis=?, alergias=?, antecedentes_personales=?, antecedentes_patologicos=?, intervenciones_quirurgicas=? WHERE id=?";
                $stmt = $conexion->prepare($sql);

                if ($stmt === false) {
                    die("Error en la preparación de la consulta: " . $conexion->error);
                }

                // Bind de los parámetros para la actualización
                $stmt->bind_param("ssssissssssssssssssi", $nombre_apellido, $fecha_nacimiento, $fecha_ingreso, $dni, $edad, $estado_civil, $nacionalidad, $obra_social1, $obra_social2, $responsable_nombre, $parentesco, $responsable_telefono, $otro_responsable, $otro_telefono, $anamnesis, $alergias, $antecedentes_personales, $antecedentes_patologicos, $intervenciones_quirurgicas, $id);

                // Ejecutamos la sentencia para la actualización
                if ($stmt->execute()) {
                    // Redireccionamos a la página de inicio o mostramos un mensaje de éxito
                    header("Location: home");
                    exit();
                } else {
                    echo "Error al actualizar los datos del paciente: " . $stmt->error;
                }

                $stmt->close();
            } else {
                // Insertamos un nuevo paciente
                $sql = "INSERT INTO pacientes (id_usuario, nombre_apellido, fecha_nacimiento, fecha_ingreso, dni, edad, estado_civil, nacionalidad, obra_social1, obra_social2, responsable_nombre, parentesco, responsable_telefono, otro_responsable, otro_telefono, anamnesis, alergias, antecedentes_personales, antecedentes_patologicos, intervenciones_quirurgicas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);

                if ($stmt === false) {
                    die("Error en la preparación de la consulta: " . $conexion->error);
                }

                // Bind de los parámetros para la inserción
                $stmt->bind_param("isssssssssssssssssss", $id_usuario, $nombre_apellido, $fecha_nacimiento, $fecha_ingreso, $dni, $edad, $estado_civil, $nacionalidad, $obra_social1, $obra_social2, $responsable_nombre, $parentesco, $responsable_telefono, $otro_responsable, $otro_telefono, $anamnesis, $alergias, $antecedentes_personales, $antecedentes_patologicos, $intervenciones_quirurgicas);

                // Ejecutamos la sentencia para la inserción
                if ($stmt->execute()) {
                    // Redireccionamos a la página de inicio o mostramos un mensaje de éxito
                    header("Location: home");
                    exit();
                } else {
                    echo "Error al guardar los datos del paciente: " . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            echo "El campo 'nombre_apellido' no puede estar vacío para realizar un nuevo insert.";
        }
    }
}
?>


