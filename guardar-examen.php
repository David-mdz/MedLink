<?php
include 'conexion.php';

// Función para limpiar y validar datos
function limpiarDatos($valor) {
    return htmlspecialchars(trim($valor));
}

// Obtiene los datos del formulario
$id_paciente = limpiarDatos($_POST['id_paciente']);
$marcha = limpiarDatos($_POST['marcha']);
$ayuda_ortopedica = limpiarDatos($_POST['ayuda_ortopedica']);
$estado_nutricional = limpiarDatos($_POST['estado_nutricional']);
$peso = limpiarDatos($_POST['peso']);
$talla = limpiarDatos($_POST['talla']);
$orientacion_colaboracion = limpiarDatos($_POST['orientacion_colaboracion']);
$examen_piel = limpiarDatos($_POST['examen_piel']);
$cicatrices_heridas = limpiarDatos($_POST['cicatrices_heridas']);
$examen_tcs = limpiarDatos($_POST['examen_tcs']);
$edemas = limpiarDatos($_POST['edemas']);
$varices = limpiarDatos($_POST['varices']);
$examen_cabeza_cuello = limpiarDatos($_POST['examen_cabeza_cuello']);
$ojos = limpiarDatos($_POST['ojos']);
$anteojos = limpiarDatos($_POST['anteojos']);
$descripcion_anteojos = limpiarDatos($_POST['descripcion_anteojos']);
$f_nasales = limpiarDatos($_POST['f_nasales']);
$boca = limpiarDatos($_POST['boca']);
$usa_protesis = limpiarDatos($_POST['usa_protesis']);
$descripcion_protesis = limpiarDatos($_POST['descripcion_protesis']);
$examen_torax = limpiarDatos($_POST['examen_torax']);
$mamas = limpiarDatos($_POST['mamas']);
$tirajes = limpiarDatos($_POST['tirajes']);
$aparato_respiratorio = limpiarDatos($_POST['aparato_respiratorio']);
$aparato_cardiovascular = limpiarDatos($_POST['aparato_cardiovascular']);
$ta = limpiarDatos($_POST['ta']);
$pulso = limpiarDatos($_POST['pulso']);
$frecuencia = limpiarDatos($_POST['frecuencia']);
$tipo = limpiarDatos($_POST['tipo']);
$examen_abdomen = limpiarDatos($_POST['examen_abdomen']);
$palpacion = limpiarDatos($_POST['palpacion']);
$organomegalias = limpiarDatos($_POST['organomegalias']);
$percusion = limpiarDatos($_POST['percusion']);
$auscultacion = limpiarDatos($_POST['auscultacion']);
$otros_abdomen = limpiarDatos($_POST['otros_abdomen']);
$examen_genitourinario = limpiarDatos($_POST['examen_genitourinario']);
$puntos_renouretrales = limpiarDatos($_POST['puntos_renouretrales']);
$otros_genitourinario = limpiarDatos($_POST['otros_genitourinario']);
$examen_osteomioarticular = limpiarDatos($_POST['examen_osteomioarticular']);
$flogosis = limpiarDatos($_POST['flogosis']);
$protesis2 = limpiarDatos($_POST['protesis2']);
$tropismo_muscular = limpiarDatos($_POST['tropismo_muscular']);
$deformaciones = limpiarDatos($_POST['deformaciones']);
$examen_neurologico = limpiarDatos($_POST['examen_neurologico']);
$estado_cognitivo = limpiarDatos($_POST['estado_cognitivo']);
$reflejos_osteoarticulares = limpiarDatos($_POST['reflejos_osteoarticulares']);
$reflejos_cutaneomucosos = limpiarDatos($_POST['reflejos_cutaneomucosos']);
$sensibilidad = limpiarDatos($_POST['sensibilidad']);
$taxia = limpiarDatos($_POST['taxia']);
$praxia = limpiarDatos($_POST['praxia']);
$comentarios = limpiarDatos($_POST['comentarios']);

// Verifica si el id_paciente ya existe en la base de datos
$sql_verificar = "SELECT id FROM examen_fisico WHERE id_paciente = ?";
$stmt_verificar = $conexion->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $id_paciente);
$stmt_verificar->execute();
$resultado_verificar = $stmt_verificar->get_result();
$stmt_verificar->close();

if ($resultado_verificar->num_rows > 0) {
    // El registro ya existe, por lo que se realizará una actualización
    $sql_actualizar = "UPDATE examen_fisico SET marcha=?, ayuda_ortopedica=?, estado_nutricional=?, peso=?, talla=?, orientacion_colaboracion=?, examen_piel=?, cicatrices_heridas=?, examen_tcs=?, edemas=?, varices=?, examen_cabeza_cuello=?, ojos=?, anteojos=?, descripcion_anteojos=?, f_nasales=?, boca=?, usa_protesis=?, descripcion_protesis=?, examen_torax=?, mamas=?, tirajes=?, aparato_respiratorio=?, aparato_cardiovascular=?, ta=?, pulso=?, frecuencia=?, tipo=?, examen_abdomen=?, palpacion=?, organomegalias=?, percusion=?, auscultacion=?, otros_abdomen=?, examen_genitourinario=?, puntos_renouretrales=?, otros_genitourinario=?, examen_osteomioarticular=?, flogosis=?, protesis2=?, tropismo_muscular=?, deformaciones=?, examen_neurologico=?, estado_cognitivo=?, reflejos_osteoarticulares=?, reflejos_cutaneomucosos=?, sensibilidad=?, taxia=?, praxia=?, comentarios=? WHERE id_paciente=?";
    $stmt_actualizar = $conexion->prepare($sql_actualizar);
    $stmt_actualizar->bind_param("sssddsssssssssssssssssssssssssssssssssssssssssssssi", $marcha, $ayuda_ortopedica, $estado_nutricional, $peso, $talla, $orientacion_colaboracion, $examen_piel, $cicatrices_heridas, $examen_tcs, $edemas, $varices, $examen_cabeza_cuello, $ojos, $anteojos, $descripcion_anteojos, $f_nasales, $boca, $usa_protesis, $descripcion_protesis, $examen_torax, $mamas, $tirajes, $aparato_respiratorio, $aparato_cardiovascular, $ta, $pulso, $frecuencia, $tipo, $examen_abdomen, $palpacion, $organomegalias, $percusion, $auscultacion, $otros_abdomen, $examen_genitourinario, $puntos_renouretrales, $otros_genitourinario, $examen_osteomioarticular, $flogosis, $protesis2, $tropismo_muscular, $deformaciones, $examen_neurologico, $estado_cognitivo, $reflejos_osteoarticulares, $reflejos_cutaneomucosos, $sensibilidad, $taxia, $praxia, $comentarios, $id_paciente);
    if ($stmt_actualizar->execute()) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos: " . $stmt_actualizar->error;
    }
    $stmt_actualizar->close();
} else {
    // El registro no existe, por lo que se realizará una inserción
    $sql_insertar = "INSERT INTO examen_fisico (id_paciente, marcha, ayuda_ortopedica, estado_nutricional, peso, talla, orientacion_colaboracion, examen_piel, cicatrices_heridas, examen_tcs, edemas, varices, examen_cabeza_cuello, ojos, anteojos, descripcion_anteojos, f_nasales, boca, usa_protesis, descripcion_protesis, examen_torax, mamas, tirajes, aparato_respiratorio, aparato_cardiovascular, ta, pulso, frecuencia, tipo, examen_abdomen, palpacion, organomegalias, percusion, auscultacion, otros_abdomen, examen_genitourinario, puntos_renouretrales, otros_genitourinario, examen_osteomioarticular, flogosis, protesis2, tropismo_muscular, deformaciones, examen_neurologico, estado_cognitivo, reflejos_osteoarticulares, reflejos_cutaneomucosos, sensibilidad, taxia, praxia, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insertar = $conexion->prepare($sql_insertar);
    $stmt_insertar->bind_param("isssddsssssssssssssssssssssssssssssssssssssssssssss", $id_paciente, $marcha, $ayuda_ortopedica, $estado_nutricional, $peso, $talla, $orientacion_colaboracion, $examen_piel, $cicatrices_heridas, $examen_tcs, $edemas, $varices, $examen_cabeza_cuello, $ojos, $anteojos, $descripcion_anteojos, $f_nasales, $boca, $usa_protesis, $descripcion_protesis, $examen_torax, $mamas, $tirajes, $aparato_respiratorio, $aparato_cardiovascular, $ta, $pulso, $frecuencia, $tipo, $examen_abdomen, $palpacion, $organomegalias, $percusion, $auscultacion, $otros_abdomen, $examen_genitourinario, $puntos_renouretrales, $otros_genitourinario, $examen_osteomioarticular, $flogosis, $protesis2, $tropismo_muscular, $deformaciones, $examen_neurologico, $estado_cognitivo, $reflejos_osteoarticulares, $reflejos_cutaneomucosos, $sensibilidad, $taxia, $praxia, $comentarios);
    
    if ($stmt_insertar->execute()) {
        echo "Datos insertados correctamente.";
    } else {
        echo "Error al insertar los datos: " . $stmt_insertar->error;
    }
    $stmt_insertar->close();
}

$conexion->close();
?>

