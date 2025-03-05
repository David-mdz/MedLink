<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

$paciente = null; // Variable para almacenar los datos del paciente
$mensajeError = ''; // Mensaje de error inicializado como vacío

// Obtener el ID del paciente de la URL
if(isset($_GET['id'])) {
    $paciente_id = $_GET['id'];

    // Consulta para obtener datos del paciente
    $consulta = "SELECT nombre_apellido, dni, obra_social1, obra_social2 FROM pacientes WHERE id = $paciente_id";
    $resultado = mysqli_query($conexion, $consulta);

    if($resultado && mysqli_num_rows($resultado) > 0) {
        // Mostrar los datos del paciente
        $paciente = mysqli_fetch_assoc($resultado);
    } else {
        // Asignar mensaje de error si no se encuentran datos para ese paciente
        $mensajeError = 'No se encontraron datos para ese paciente.';
    }
} else {
    // Asignar mensaje de error si no se proporciona un ID de paciente válido
    $mensajeError = 'Seleccione un paciente.';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo-movil.css" type="text/css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/estilo-recetas.css" type="text/css"media="all">
    <link rel="stylesheet" href="css/estilo-general.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/estilo-recetas-impresion.css" type="text/css"media="print">
    <script src="js/time_out.js"></script>
    <title>Recetas</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="contenedor">
        <div class="contenedor-recetas">
            <img src="Imagenes\Barra.png" alt="Logo" class="logo-barra">
            <div class="recetas">
                <h4>R.P :</h4>
                <p><?php echo isset($paciente['nombre_apellido']) ? $paciente['nombre_apellido'] : 'Nombre y Apellido'; ?></p>
                <p>DNI <?php echo isset($paciente['dni']) ? $paciente['dni'] : 'DNI'; ?></p>
                <p><?php echo isset($paciente['obra_social1']) ? $paciente['obra_social1'] : 'Obra Social 2'; ?></p>
                <p><?php echo isset($paciente['obra_social2']) ? $paciente['obra_social2'] : 'Obra Social 2'; ?></p>
                <textarea id="medicamentos" name="medicamentos" rows="3" cols="50" placeholder="Medicación u orden medica"></textarea><br>     
            </div>
            <div class="barra-estados" id="mensaje-estado">
                    <!-- Mostrar el mensaje de error si existe -->
                    <?php echo $mensajeError; ?>
            </div>
            <img src="Imagenes\barra-gris.png" alt="Logo-gris" class="logo-barra-gris ocultar">
        </div>
        <div class="boton-agregar-receta" id="boton-agregar-receta">
            <img src="Imagenes/agregar-receta.png" alt="Agregar Receta">
        </div>
</div>
<script>
        document.getElementById("pacientes-select").addEventListener("change", function() {
            var selectedPatientId = this.value;
            if (selectedPatientId) {
                window.location.href = "recetas?id=" + selectedPatientId;
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            var mensajeError = document.getElementById("mensaje-estado");
            mensajeError.style.display = "block"; // Mostrar la barra de estados siempre
        });
        
        
        
        document.getElementById("boton-agregar-receta").addEventListener("click", function() {
            const contenedorRecetas = document.querySelector(".contenedor-recetas"); // Obtener el contenedor principal
            const nuevaReceta = contenedorRecetas.cloneNode(true); // Clonar el contenedor de recetas
            nuevaReceta.classList.add("receta-clonada"); // Agregar clase a la receta clonada
            
            contenedorRecetas.parentNode.insertBefore(nuevaReceta, contenedorRecetas.nextSibling); // Insertar el nuevo contenedor de recetas
        });
</script>  

<?php include 'footer.php'; ?>
</body>
</html>