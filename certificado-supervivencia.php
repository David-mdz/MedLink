<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

// Verifica si se ha pasado el parámetro 'id' en la URL
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Realiza una consulta SQL para obtener los datos del paciente con el ID proporcionado
    $sql = "SELECT * FROM pacientes WHERE id = $id_paciente";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        // Obtiene los datos del paciente
        $datos_paciente = $result->fetch_assoc();
    } else {
        echo "No se encontraron datos para el paciente con ID: $id_paciente";
    }
}

// Definir variables para los datos del paciente
$nombre_apellido = "Nombre del paciente";
$obra_social1 = "obra social";
$obra_social2 = "";
$dni = "Número de DNI";

$meses = array(
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
);
$mes_actual = $meses[date('n')];
$año_actual = date('Y');
$número_del_día_actual = date('j');

// Verifica si se ha pasado el parámetro 'id' en la URL
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Realiza una consulta SQL para obtener los datos del paciente con el ID proporcionado
    $sql = "SELECT nombre_apellido, obra_social1, obra_social2, dni FROM pacientes WHERE id = $id_paciente";
    $result = $conexion->query($sql);

    if ($result && $result->num_rows > 0) {
        // Obtiene los datos del paciente
        $datos_paciente = $result->fetch_assoc();
        $nombre_apellido = $datos_paciente['nombre_apellido'];
        $obra_social1 = $datos_paciente['obra_social1'];
        $obra_social2 = $datos_paciente['obra_social2'];
        $dni = $datos_paciente['dni'];
    } else {
        echo "No se encontraron datos para el paciente con ID: $id_paciente";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" href="css/estilo-certificado-supervivencia.css">
    <link rel="stylesheet" type="text/css" media="print" href="css/estilo-impresion-supervivencia.css">

    <script src="js/time_out.js"></script>

    <title>Certificado de Supervivencia</title>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="contenedor">
        <div class="contenedor-certificado">
            <h4> Certificado de supervivencia</h4>
            <br>
            <p>
            En el día de la fecha, en el departamento de <select name="departamento" id="departamento-select">
        <option value="capital">Mendoza Capital</option>
        <option value="General Alvear">General Alvear</option>
        <option value="Godoy Cruz">Godoy Cruz</option>
        <option value="Guaymallén">Guaymallén</option>
        <option value="Junín">Junín</option>
        <option value="La Paz">La Paz</option>
        <option value="Las Heras">Las Heras</option>
        <option value="Lavalle">Lavalle</option>
        <option value="Luján de Cuyo">Luján de Cuyo</option>
        <option value="Maipú">Maipú</option>
        <option value="Malargüe">Malargüe</option>
        <option value="Rivadavia">Rivadavia</option>
        <option value="San Carlos">San Carlos</option>
        <option value="San Martín">San Martín</option>
        <option value="San Rafael">San Rafael</option>
        <option value="Santa Rosa">Mendoza Capital</option>
        <option value="Tunuyán">General Alvear</option>
        <option value="Tupungato">Mendoza Ciudad</option></select> De la provincia de Mendoza, 
            siendo las <?php echo date('H:i'); ?> hs, a los <?= $número_del_día_actual ?> días del mes de <?= $mes_actual ?> del <?= $año_actual ?>, 
            se constata la supervivencia del Sr./a <?= $nombre_apellido ?> afiliado a <?= $obra_social1 ?><?php if (!empty($obra_social2)) { echo " y " . $obra_social2; } ?>
            con documento tipo DNI N° <?= $dni ?>.
            </p>
            <div class="firma">
                <p>Firma y sello del médico</p>
            </div>
        </div>
            <div class="contenedor-informacion">
                <p>Seleccione un paciente para actualizar los campos del certificado, para imprimir presione las teclas "Control + P".</p>
            </div>
    </div>
    <script>
        document.getElementById("pacientes-select").addEventListener("change", function() {
        var selectedPatientId = this.value;
        if (selectedPatientId) {
            window.location.href = "certificado-supervivencia?id=" + selectedPatientId;
        }
    });
    </script>  

<?php include 'footer.php'; ?>
</body>
</html>