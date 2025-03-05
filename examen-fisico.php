<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

// Inicializa las variables
$id_paciente = null;
$marcha = "";
$ayuda_ortopedica = "";
$estado_nutricional = "";
$peso = "";
$talla = "";
$orientacion_colaboracion = "";
$examen_piel = "";
$cicatrices_heridas = "";
$examen_tcs = "";
$edemas = "";
$varices = "";
$examen_cabeza_cuello = "";
$ojos = "";
$anteojos = "";
$descripcion_anteojos = "";
$f_nasales = "";
$boca = "";
$usa_protesis = "";
$descripcion_protesis = "";
$examen_torax = "";
$mamas = "";
$tirajes = "";
$aparato_respiratorio = "";
$aparato_cardiovascular = "";
$ta = "";
$pulso = "";
$frecuencia = "";
$tipo = "";
$examen_abdomen = "";
$palpacion = "";
$organomegalias = "";
$percusion = "";
$auscultacion = "";
$otros_abdomen = "";
$examen_genitourinario = "";
$puntos_renouretrales = "";
$otros_genitourinario = "";
$examen_osteomioarticular = "";
$flogosis = "";
$protesis2 = "";
$tropismo_muscular = "";
$deformaciones = "";
$examen_neurologico = "";
$estado_cognitivo = "";
$reflejos_osteoarticulares = "";
$reflejos_cutaneomucosos = "";
$sensibilidad = "";
$taxia = "";
$praxia = "";
$comentarios = "";

// Verificar si se proporciona un ID de paciente en la URL
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Consulta para obtener los datos del paciente
    $sql = "SELECT * FROM examen_fisico WHERE id_paciente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    // Verificar si se encontraron datos
    if ($fila) {
        // Obtener todos los campos de la tabla
        $marcha = $fila['marcha'];
        $ayuda_ortopedica = $fila['ayuda_ortopedica'];
        $estado_nutricional = $fila['estado_nutricional'];
        $peso = $fila['peso'];
        $talla = $fila['talla'];
        $orientacion_colaboracion = $fila['orientacion_colaboracion'];
        $examen_piel = $fila['examen_piel'];
        $cicatrices_heridas = $fila['cicatrices_heridas'];
        $examen_tcs = $fila['examen_tcs'];
        $edemas = $fila['edemas'];
        $varices = $fila['varices'];
        $examen_cabeza_cuello = $fila['examen_cabeza_cuello'];
        $ojos = $fila['ojos'];
        $anteojos = $fila['anteojos'];
        $descripcion_anteojos = $fila['descripcion_anteojos'];
        $f_nasales = $fila['f_nasales'];
        $boca = $fila['boca'];
        $usa_protesis = $fila['usa_protesis'];
        $descripcion_protesis = $fila['descripcion_protesis'];
        $examen_torax = $fila['examen_torax'];
        $mamas = $fila['mamas'];
        $tirajes = $fila['tirajes'];
        $aparato_respiratorio = $fila['aparato_respiratorio'];
        $aparato_cardiovascular = $fila['aparato_cardiovascular'];
        $ta = $fila['ta'];
        $pulso = $fila['pulso'];
        $frecuencia = $fila['frecuencia'];
        $tipo = $fila['tipo'];
        $examen_abdomen = $fila['examen_abdomen'];
        $palpacion = $fila['palpacion'];
        $organomegalias = $fila['organomegalias'];
        $percusion = $fila['percusion'];
        $auscultacion = $fila['auscultacion'];
        $otros_abdomen = $fila['otros_abdomen'];
        $examen_genitourinario = $fila['examen_genitourinario'];
        $puntos_renouretrales = $fila['puntos_renouretrales'];
        $otros_genitourinario = $fila['otros_genitourinario'];
        $examen_osteomioarticular = $fila['examen_osteomioarticular'];
        $flogosis = $fila['flogosis'];
        $protesis2 = $fila['protesis2'];
        $tropismo_muscular = $fila['tropismo_muscular'];
        $deformaciones = $fila['deformaciones'];
        $examen_neurologico = $fila['examen_neurologico'];
        $estado_cognitivo = $fila['estado_cognitivo'];
        $reflejos_osteoarticulares = $fila['reflejos_osteoarticulares'];
        $reflejos_cutaneomucosos = $fila['reflejos_cutaneomucosos'];
        $sensibilidad = $fila['sensibilidad'];
        $taxia = $fila['taxia'];
        $praxia = $fila['praxia'];
        $comentarios = $fila['comentarios'];
    } 
    // Cerrar la consulta
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/examen-fisico-mobile.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/examen-fisico.css" media="screen and (min-width: 769px)">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" type="text/css" media="print" href="css/estilo-impresion.css">
    <script src="js/time_out.js"></script>
    <title>Examen Fisico</title>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <form method="POST" id="examen-fisico-form" name="examen-fisico-form">            
            <h2>Examen Fisico</h2>
            
            <div class="paciente">
                <label for="paciente">Paciente:</label>
                <?php
                // Obtener el ID del paciente de la URL
                $id_paciente = isset($_GET['id']) ? intval($_GET['id']) : 0;

                // Realizar una consulta para obtener el nombre del paciente por su ID
                $sql = "SELECT nombre_apellido FROM pacientes WHERE id = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("i", $id_paciente);
                $stmt->execute();
                $stmt->bind_result($nombre_paciente);
                $stmt->fetch();
                $stmt->close();

                // Mostrar el nombre del paciente en el campo de texto
                echo "<input type='text' id='paciente' name='paciente' value='" . $nombre_paciente . "' readonly>";
                ?>
            </div>
            
           <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

            
            <div class="campo-container">
                <div class="campo">
                    <label for="marcha">Marcha:</label>
                    <input type="text" id="marcha" name="marcha" value="<?php echo $marcha; ?>">
                </div>
                <div class="campo">    
                    <label for="ayuda_ortopedica">¿Necesita ayuda o medios ortopédicos?</label>
                    <input type="text" id="ayuda_ortopedica" name="ayuda_ortopedica" value="<?php echo $ayuda_ortopedica; ?>">
                </div>
                <div class="campo">
                    <label for="estado_nutricional">Estado Nutricional e hidratación:</label>
                    <input type="text" id="estado_nutricional" name="estado_nutricional" value="<?php echo $estado_nutricional; ?>">
                </div>
                <div class="campo">    
                    <label for="peso">Peso (kg):</label>
                    <input type="text" id="peso" name="peso" value="<?php echo $peso; ?>">
                </div>   
                <div class="campo">    
                    <label for="talla">Talla:</label>
                    <input type="text" id="talla" name="talla" value="<?php echo $talla; ?>">
                </div>
                <div class="campo">
                    <label for="orientacion_colaboracion">Orientación y Colaboración:</label>
                    <input type="text" id="orientacion_colaboracion" name="orientacion_colaboracion" value="<?php echo $orientacion_colaboracion; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_piel">Examen de piel:</label>
                    <input type="text" id="examen_piel" name="examen_piel" value="<?php echo $examen_piel; ?>">
                </div>    
                <div class="campo">    
                    <label for="cicatrices_heridas">Cicatrices, heridas o escaras:</label>
                    <input type="text" id="cicatrices_heridas" name="cicatrices_heridas" value="<?php echo $cicatrices_heridas; ?>">
                </div>
            </div>
            <div class="campo-container">    
                <div class="campo">
                    <label for="examen_tcs">Examen TCS:</label>
                    <input type="text" id="examen_tcs" name="examen_tcs" value="<?php echo $examen_tcs; ?>">
                </div>
                <div class="campo">  
                    <label for="edemas">Edemas:</label>
                    <input type="text" id="edemas" name="edemas" value="<?php echo $edemas; ?>">
                </div>
                <div class="campo">        
                    <label for="varices">Varices:</label>
                    <input type="text" id="varices" name="varices" value="<?php echo $varices; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_cabeza_cuello">Examen de cabeza y cuello:</label>
                    <input type="text" id="examen_cabeza_cuello" name="examen_cabeza_cuello" value="<?php echo $examen_cabeza_cuello; ?>">
                </div>
                <div class="campo">    
                    <label for="ojos">Ojos:</label>
                    <input type="text" id="ojos" name="ojos" value="<?php echo $ojos; ?>">
                </div>
                <div class="campo">        
                    <label for="anteojos">Usa anteojos:</label>
                    <input type="text" id="anteojos" name="anteojos" value="<?php echo $anteojos; ?>">
                </div>
                <div class="campo">        
                    <label for="descripcion_anteojos">Describa los que trae:</label>
                    <input type="text" id="descripcion_anteojos" name="descripcion_anteojos" value="<?php echo $descripcion_anteojos; ?>">
                </div>
                <div class="campo">       
                    <label for="f_nasales">F.Nasales:</label>
                    <input type="text" id="f_nasales" name="f_nasales" value="<?php echo $f_nasales; ?>">
                </div>
                <div class="campo">       
                    <label for="boca">Boca:</label>
                    <input type="text" id="boca" name="boca" value="<?php echo $boca; ?>">
                </div>
                <div class="campo">       
                    <label for="usa_protesis">¿Usa prótesis?:</label>
                    <input type="text" id="usa_protesis" name="usa_protesis" value="<?php echo $usa_protesis; ?>">
                </div>
                <div class="campo">         
                    <label for="descripcion_protesis">Describa las que trae:</label>
                    <input type="text" id="descripcion_protesis" name="descripcion_protesis" value="<?php echo $descripcion_protesis; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_torax">Examen de Torax:</label>
                    <input type="text" id="examen_torax" name="examen_torax" value="<?php echo $examen_torax; ?>">
                </div>    
                <div class="campo">    
                    <label for="mamas">Mamas:</label>
                    <input type="text" id="mamas" name="mamas" value="<?php echo $mamas; ?>">
                </div>
                <div class="campo">
                    <label for="tirajes">Tirajes:</label>
                    <input type="text" id="tirajes" name="tirajes" value="<?php echo $tirajes; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="aparato_respiratorio">Aparato respiratorio:</label>
                    <input type="text" id="aparato_respiratorio" name="aparato_respiratorio" value="<?php echo $aparato_respiratorio; ?>">
                </div>
            </div>
            <div class="campo-container">    
                <div class="campo">
                    <label for="aparato_cardiovascular">Aparato cardiovascular:</label>
                    <input type="text" id="aparato_cardiovascular" name="aparato_cardiovascular" value="<?php echo $aparato_cardiovascular; ?>">
                </div>
                <div class="campo">
                    <label for="ta">TA:</label>
                    <input type="text" id="ta" name="ta" value="<?php echo $ta; ?>">
                </div>
                <div class="campo">
                    <label for="pulso">Pulso:</label>
                    <input type="text" id="pulso" name="pulso" value="<?php echo $pulso; ?>">
                </div>
                <div class="campo">
                    <label for="frecuencia">Frecuencia:</label>
                    <input type="text" id="frecuencia" name="frecuencia" value="<?php echo $frecuencia; ?>">
                </div>
                <div class="campo">
                    <label for="tipo">Tipo:</label>
                    <input type="text" id="tipo" name="tipo" value="<?php echo $tipo; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_abdomen">Examen de Abdomen:</label>
                    <input type="text" id="examen_abdomen" name="examen_abdomen" value="<?php echo $examen_abdomen; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="palpacion">Palpación:</label>
                    <input type="text" id="palpacion" name="palpacion" value="<?php echo $palpacion; ?>">
                </div>
                <div class="campo">
                    <label for="organomegalias">Organomegalias:</label>
                    <input type="text" id="organomegalias" name="organomegalias" value="<?php echo $organomegalias; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="percusion">Percusión:</label>
                    <input type="text" id="percusion" name="percusion" value="<?php echo $percusion; ?>">
                </div>
                <div class="campo">
                    <label for="auscultacion">Auscultación:</label>
                    <input type="text" id="auscultacion" name="auscultacion" value="<?php echo $auscultacion; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="otros_abdomen">Otros:</label>
                    <input type="text" id="otros_abdomen" name="otros_abdomen" value="<?php echo $otros_abdomen; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_genitourinario">Examen Genitourinario:</label>
                    <input type="text" id="examen_genitourinario" name="examen_genitourinario" value="<?php echo $examen_genitourinario; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="puntos_renouretrales">Puntos Renouretrales:</label>
                    <input type="text" id="puntos_renouretrales" name="puntos_renouretrales" value="<?php echo $puntos_renouretrales; ?>">
                </div>
                <div class="campo">
                    <label for="otros_genitourinario">Otros:</label>
                    <input type="text" id="otros_genitourinario" name="otros_genitourinario" value="<?php echo $otros_genitourinario; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_osteomioarticular">Examen Osteomioarticular:</label>
                    <input type="text" id="examen_osteomioarticular" name="examen_osteomioarticular" value="<?php echo $examen_osteomioarticular; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="flogosis">Flogosis:</label>
                    <input type="text" id="flogosis" name="flogosis" value="<?php echo $flogosis; ?>">
                </div>
                <div class="campo">
                    <label for="protesis2">Prótesis:</label>
                    <input type="text" id="protesis2" name="protesis2" value="<?php echo $protesis2; ?>">
                </div>
                <div class="campo">
                    <label for="tropismo_muscular">Tropismo Muscular:</label>
                    <input type="text" id="tropismo_muscular" name="tropismo_muscular" value="<?php echo $tropismo_muscular; ?>">
                </div>
                <div class="campo">
                    <label for="deformaciones">Deformaciones:</label>
                    <input type="text" id="deformaciones" name="deformaciones" value="<?php echo $deformaciones; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="examen_neurologico">Examen Neurológico:</label>
                    <input type="text" id="examen_neurologico" name="examen_neurologico" value="<?php echo $examen_neurologico; ?>">
                </div>
            </div>
            <div class="campo-container">
                <div class="campo">
                    <label for="estado_cognitivo">Estado cognitivo:</label>
                    <input type="text" id="estado_cognitivo" name="estado_cognitivo" value="<?php echo $estado_cognitivo; ?>">
                </div>
                <div class="campo">
                    <label for="reflejos_osteoarticulares">Reflejos Osteoarticulares:</label>
                    <input type="text" id="reflejos_osteoarticulares" name="reflejos_osteoarticulares" value="<?php echo $reflejos_osteoarticulares; ?>">
                </div>
                <div class="campo">
                    <label for="reflejos_cutaneomucosos">Reflejos Cutaneomucosos:</label>
                    <input type="text" id="reflejos_cutaneomucosos" name="reflejos_cutaneomucosos" value="<?php echo $reflejos_cutaneomucosos; ?>">
                </div>
                <div class="campo">
                    <label for="sensibilidad">Sensibilidad:</label>
                    <input type="text" id="sensibilidad" name="sensibilidad" value="<?php echo $sensibilidad; ?>">
                </div>
                <div class="campo">
                    <label for="taxia">Taxia:</label>
                    <input type="text" id="taxia" name="taxia" value="<?php echo $taxia; ?>">
                </div>
                <div class="campo">
                    <label for="praxia">Praxia:</label>
                    <input type="text" id="praxia" name="praxia" value="<?php echo $praxia; ?>">
                </div>
            </div>
            <div class="campo-comentario">
                <div class="campo">
                    <label for="comentarios">Comentarios:</label>
                </div>
                <div class="campo">
                    <textarea id="comentarios" name="comentarios" placeholder="Escribir..." class="input-control" rows="5"><?php echo $comentarios; ?></textarea>
                </div>
            </div>
            <div class="botones no-imprimir">
                <div id="mensaje" style="display: none;"></div>
                <button class="boton-guardar" type="submit">Guardar</button>
            </div>
        </form>
    </div>
    
    <script>
    document.getElementById("pacientes-select").addEventListener("change", function() {
        var selectedPatientId = this.value;
        if (selectedPatientId) {
            window.location.href = "examen-fisico?id=" + selectedPatientId;
        }
    });

        $(document).ready(function() {
    $("#examen-fisico-form").submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe automáticamente

        // Obtener el valor del campo id_paciente
        var idPaciente = $("#id_paciente").val();

        // Inicializar la variable mensaje
        var mensaje = "";

        // Verificar si se ha seleccionado un paciente
        if (idPaciente === "") {
            // Asignar el mensaje de error
            mensaje = "Por favor, selecciona un paciente.";
            mostrarMensajeError(mensaje);
        } else {
            // Obtener los datos del formulario
            var formData = $(this).serialize();

            // Enviar una solicitud AJAX para guardar los datos
            $.ajax({
                type: "POST",
                url: "guardar-examen.php",
                data: formData,
                // En el cliente
                success: function(response) {
                    if (response.includes("Datos actualizados correctamente.")) {
                        // La respuesta contiene "Datos actualizados correctamente.", muestra el mensaje de éxito en verde
                        mensaje = response;
                        mostrarMensajeExito(mensaje);
                    } else {
                        // La respuesta no contiene el mensaje de éxito, muestra el mensaje de error en rojo
                        mensaje = response; // Utilizar el mensaje proporcionado por el servidor
                        mostrarMensajeError(mensaje);
                    }
                },
                error: function() {
                    // Asignar un mensaje de error genérico
                    mensaje = "Error al enviar datos al servidor.";
                    mostrarMensajeError(mensaje);
                }
            });
        }
    });

            // Función para mostrar un cuadro de mensaje de éxito
            function mostrarMensajeExito(mensaje) {
                $("#mensaje").text(mensaje);
                $("#mensaje").css("background-color", "#4CAF50"); // Cambia el color de fondo a verde (puedes cambiarlo a tu color deseado)
                $("#mensaje").css("color", "#fff"); // Cambia el color del texto a blanco o el color deseado
                $("#mensaje").fadeIn();

                // Ocultar el mensaje después de unos segundos (ajusta el tiempo según lo necesites)
                setTimeout(function() {
                    $("#mensaje").fadeOut();
                }, 3000); // 3000 milisegundos = 3 segundos
            }

            // Función para mostrar un cuadro de mensaje de error
            function mostrarMensajeError(mensaje) {
                $("#mensaje").text(mensaje);
                $("#mensaje").css("background-color", "#f44336"); // Color de fondo rojo para error
                $("#mensaje").fadeIn();

                // Ocultar el mensaje después de unos segundos (ajusta el tiempo según lo necesites)
                setTimeout(function() {
                    $("#mensaje").fadeOut();
                }, 3000); // 3000 milisegundos = 3 segundos
            }
        });
    </script>
    <script src="js/expansion-texto-automatica.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
