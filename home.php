<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

// Variable para almacenar los datos del paciente
$datos_paciente = array();

// Obtener el ID del usuario actual (asumiendo que está guardado en la sesión)
$id_usuario = $_SESSION['user_id'];

// Verificar si se ha pasado el parámetro 'id' en la URL y buscar el paciente asociado al usuario actual
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Realizar una consulta SQL segura para obtener los datos del paciente con el ID proporcionado y asociado al usuario actual
    $sql = "SELECT * FROM pacientes WHERE id = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $id_paciente, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Obtener los datos del paciente
            $datos_paciente = $result->fetch_assoc();
        } else {
            echo "No se encontraron datos para el paciente con ID: $id_paciente asociado al usuario actual.";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Datos Personales</title>

    <link rel="favicon" href="Imagenes\Logo-Historiaclinica.ico" type="image/ico">
    <link rel="icon" href="Imagenes\Logo-Historiaclinica.ico" type="image/ico">
    <link rel="stylesheet" type="text/css" media="print" href="css/estilo-impresion-datos-personales.css">
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/home-mobile.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/home-desktop.css" media="screen and (min-width: 769px)">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/cargar_paciente.js"></script>
    <script src="js/calcular_edad.js"></script>
    <script src="js/time_out.js"></script>
    
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <form id="mi-formulario" action="guardar_paciente.php" method="post">
            <div class="forma-main">
                <div class="cuadro-datos-personales">
                <h4 class="titulo-datos-personales">Datos personales</h4>
                    <div class="campo">
                        <label for="nombre-apellido">Nombre y Apellido:</label>
                        <input type="text" id="nombre-apellido" name="nombre-apellido" value="<?php echo isset($datos_paciente['nombre_apellido']) ? $datos_paciente['nombre_apellido'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" id="fecha-nacimiento" name="fecha-nacimiento" oninput="calcularEdad()" value="<?php echo isset($datos_paciente['fecha_nacimiento']) ? $datos_paciente['fecha_nacimiento'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="fecha-ingreso">Fecha de Ingreso:</label>
                        <input type="date" id="fecha-ingreso" name="fecha-ingreso" value="<?php echo isset($datos_paciente['fecha_ingreso']) ? $datos_paciente['fecha_ingreso'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="dni">DNI:</label>
                        <input type="text" id="dni" name="dni" value="<?php echo isset($datos_paciente['dni']) ? $datos_paciente['dni'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="edad">Edad:</label>
                        <input type="text" id="edad" name="edad" readonly value="<?php echo isset($datos_paciente['edad']) ? $datos_paciente['edad'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="estado-civil">Estado civil:</label>
                        <input type="text" id="estado-civil" name="estado-civil" value="<?php echo isset($datos_paciente['estado_civil']) ? $datos_paciente['estado_civil'] : ''; ?>">
                    </div> 
                    <div class="campo">
                        <label for="nacionalidad">Nacionalidad:</label>
                        <input type="text" id="nacionalidad" name="nacionalidad" value="<?php echo isset($datos_paciente['nacionalidad']) ? $datos_paciente['nacionalidad'] : ''; ?>">
                    </div> 
                    <div class="campo obra-social">
                        <label for="obra-social1">Obra Social:</label>
                        <input type="text" id="obra-social1" name="obra-social1" value="<?php echo isset($datos_paciente['obra_social1']) ? $datos_paciente['obra_social1'] : ''; ?>">
                        <input type="text" id="obra-social2" name="obra-social2" value="<?php echo isset($datos_paciente['obra_social2']) ? $datos_paciente['obra_social2'] : ''; ?>">
                    </div>
                </div>
                
                <div class="cuadro-responsable">
                    <h4>Responsable</h4>
                    <div class="campo">
                        <label for="responsable-nombre">Nombre y Apellido:</label>
                        <input type="text" id="responsable-nombre" name="responsable-nombre" value="<?php echo isset($datos_paciente['responsable_nombre']) ? $datos_paciente['responsable_nombre'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="parentesco">Parentesco:</label>
                        <input type="text" id="parentesco" name="parentesco" value="<?php echo isset($datos_paciente['parentesco']) ? $datos_paciente['parentesco'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="responsable-telefono">Telefono:</label>
                        <input type="text" id="responsable-telefono" name="responsable-telefono" value="<?php echo isset($datos_paciente['responsable_telefono']) ? $datos_paciente['responsable_telefono'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="otro-responsable">Otro Responsable:</label>
                        <input type="text" id="otro-responsable" name="otro-responsable" value="<?php echo isset($datos_paciente['otro_responsable']) ? $datos_paciente['otro_responsable'] : ''; ?>">
                    </div>
                    <div class="campo">
                        <label for="otro-telefono">Telefono:</label>
                        <input type="text" id="otro-telefono" name="otro-telefono" value="<?php echo isset($datos_paciente['otro_telefono']) ? $datos_paciente['otro_telefono'] : ''; ?>">
                    </div>
                </div>
                
                <div class="enfermedad-actual">
                    <h4>Enfermedades Actuales</h4>
                    <label for="anamnesis">
                        <h5>Anamnesis:</h5>
                        <textarea name="anamnesis" id="anamnesis" placeholder="Directa o indirecta ? Por quien" class="input-control" rows="5"><?php echo isset($datos_paciente['anamnesis']) ? $datos_paciente['anamnesis'] : ''; ?></textarea>
                    </label>
                    <label for="alergias">
                        <h5>Alergias:</h5>
                        <textarea name="alergias" id="alergias" placeholder="Escribir..." class="input-control" rows="5"><?php echo isset($datos_paciente['alergias']) ? $datos_paciente['alergias'] : ''; ?></textarea>
                    </label>
                    <label for="antecedentes-personales">
                        <h5>Antecedentes personales:</h5>
                        <textarea name="antecedentes-personales" id="antecedentes-personales" placeholder="Escribir..." class="input-control" rows="5"><?php echo isset($datos_paciente['antecedentes_personales']) ? $datos_paciente['antecedentes_personales'] : ''; ?></textarea>
                    </label>
                    <label for="antecedentes-patologicos">
                        <h5>Antecedentes Patologicos:</h5>
                        <textarea name="antecedentes-patologicos" id="antecedentes-patologicos" placeholder="Escribir..." class="input-control" rows="5" ><?php echo isset($datos_paciente['antecedentes_patologicos']) ? $datos_paciente['antecedentes_patologicos'] : ''; ?></textarea>
                    </label>
                    <label for="intervenciones-quirurgicas">
                        <h5>Intervenciones quirurgicas:</h5>
                        <textarea name="intervenciones-quirurgicas" id="intervenciones-quirurgicas" placeholder="Escribir..." class="input-control" rows="5"><?php echo isset($datos_paciente['intervenciones_quirurgicas']) ? $datos_paciente['intervenciones_quirurgicas'] : ''; ?></textarea>
                    </label>
                </div>
                
                <input type="hidden" id="id" name="id" value="<?php echo isset($datos_paciente['id']) ? $datos_paciente['id'] : ''; ?>">
                <div class="boton-container">
                    <button class="boton-guardar no-imprimir" type="submit">Guardar cambios</button>
                    <button class="boton-eliminar no-imprimir" type="button" onclick="eliminarPaciente()">Eliminar Paciente</button>
                </div>
            </div> 
        </form>
</div>   
<script src="js/expansion-texto-automatica.js"></script>   
<script>
    document.getElementById("pacientes-select").addEventListener("change", function() {
    var selectedPatientId = this.value;
    if (selectedPatientId) {
        window.location.href = "home?id=" + selectedPatientId;
    }
});
</script>


<?php include 'footer.php'; ?>
</body>
</html>

