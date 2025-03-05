<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

$idPaciente = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fechaSeleccionada = $_POST['fechas-guardadas'];

    if ($user_id === null) {
        $mensaje = "El usuario no está autenticado.";
    } else {
        if ($idPaciente <= 0) {
            $mensaje = "Seleccione un Paciente antes de guardar o modificar.";
        } else {
            // Guardado en la tabla de autoguardado
            $medicacion = isset($_POST['medicacion']) ? $_POST['medicacion'] : '';
            $droga = isset($_POST['droga']) ? $_POST['droga'] : '';

            $fechaActual = date("Y-m-d");

            foreach ($medicacion as $index => $med) {
                $dr = $droga[$index];
                // Verificar si ya existe la combinación de medicación y droga
                $sqlVerificar = "SELECT id FROM autocompleteado WHERE medicacion = ? AND droga = ?";
                $stmtVerificar = $conexion->prepare($sqlVerificar);
                $stmtVerificar->bind_param("ss", $med, $dr);
                $stmtVerificar->execute();
                $resultVerificar = $stmtVerificar->get_result();

                if ($resultVerificar->num_rows == 0) {
                    // No existe la combinación, insertar
                    $sqlAutocompletado = "INSERT INTO autocompleteado (medicacion, droga) VALUES (?, ?)";
                    $stmtAutocompletado = $conexion->prepare($sqlAutocompletado);

                    if ($stmtAutocompletado) {
                        $stmtAutocompletado->bind_param("ss", $med, $dr);

                        if ($stmtAutocompletado->execute()) {
                            $mensaje = "Datos autoguardados correctamente.";
                        } else {
                            $mensaje = "Error al autoguardar los datos: " . $stmtAutocompletado->error;
                        }

                        $stmtAutocompletado->close();
                    } else {
                        $mensaje = "Error en la consulta de autoguardado: " . $conexion->error;
                    }
                } else {
                    $mensaje = "La combinación de medicación y droga ya existe en la tabla de autoguardado.";
                }

                $stmtVerificar->close();
            }

            $medicacion = isset($_POST['medicacion']) ? implode(',', $_POST['medicacion']) : '';
            $droga = isset($_POST['droga']) ? implode(',', $_POST['droga']) : '';
            $ayuna = isset($_POST['ayuna']) ? implode(',', $_POST['ayuna']) : '';
            $desayuno = isset($_POST['desayuno']) ? implode(',', $_POST['desayuno']) : '';
            $almuerzo = isset($_POST['almuerzo']) ? implode(',', $_POST['almuerzo']) : '';
            $merienda = isset($_POST['merienda']) ? implode(',', $_POST['merienda']) : '';
            $cena = isset($_POST['cena']) ? implode(',', $_POST['cena']) : '';
            $observaciones = isset($_POST['observaciones']) ? implode(',', $_POST['observaciones']) : '';
            $cuadro_evolucion = $_POST['cuadro-evolucion-texto'];

            $sqlVerificar = "SELECT id FROM medicacion WHERE id_paciente = ? AND fecha = ?";
            $stmtVerificar = $conexion->prepare($sqlVerificar);

            if ($stmtVerificar) {
                $stmtVerificar->bind_param("is", $idPaciente, $fechaSeleccionada);
                $stmtVerificar->execute();
                $resultVerificar = $stmtVerificar->get_result();

                if ($resultVerificar->num_rows > 0) {
                    $sqlUpdate = "UPDATE medicacion SET medicacion = ?, droga = ?, ayuna = ?, desayuno = ?, almuerzo = ?, merienda = ?, cena = ?, observaciones = ?, cuadro_evolucion = ? WHERE id_paciente = ? AND fecha = ? AND id_usuario = ?";
                    $stmtUpdate = $conexion->prepare($sqlUpdate);

                    if ($stmtUpdate) {
                        $stmtUpdate->bind_param("sssssssssssi", $medicacion, $droga, $ayuna, $desayuno, $almuerzo, $merienda, $cena, $observaciones, $cuadro_evolucion, $idPaciente, $fechaSeleccionada, $user_id);

                        if ($stmtUpdate->execute()) {
                            $mensaje = "Datos actualizados correctamente.";
                        } else {
                            $mensaje = "Error al actualizar los datos: " . $stmtUpdate->error;
                        }

                        $stmtUpdate->close();
                    } else {
                        $mensaje = "Error en la consulta de actualización: " . $conexion->error;
                    }
                } else {
                    $sqlInsert = "INSERT INTO medicacion (id_paciente, fecha, medicacion, droga, ayuna, desayuno, almuerzo, merienda, cena, observaciones, cuadro_evolucion, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert = $conexion->prepare($sqlInsert);

                    if ($stmtInsert) {
                        $stmtInsert->bind_param("sssssssssssi", $idPaciente, $fechaActual, $medicacion, $droga, $ayuna, $desayuno, $almuerzo, $merienda, $cena, $observaciones, $cuadro_evolucion, $user_id);

                        if ($stmtInsert->execute()) {
                            $mensaje = "Datos guardados correctamente.";
                        } else {
                            $mensaje = "Error al guardar los datos: " . $stmtInsert->error;
                        }

                        $stmtInsert->close();
                    } else {
                        $mensaje = "Error en la consulta de inserción: " . $conexion->error;
                    }
                }

                $stmtVerificar->close();
            } else {
                $mensaje = "Error en la consulta de verificación: " . $conexion->error;
            }
        }
    }
}

$sqlFechas = "SELECT DISTINCT fecha FROM medicacion WHERE id_paciente = ?";
$stmtFechas = $conexion->prepare($sqlFechas);

if ($stmtFechas) {
    $stmtFechas->bind_param("i", $idPaciente);
    $stmtFechas->execute();
    $resultFechas = $stmtFechas->get_result();

    $fechasDisponibles = "";
    if ($resultFechas->num_rows > 0) {
        while ($rowFecha = $resultFechas->fetch_assoc()) {
            $fecha = $rowFecha['fecha'];
            $fechasDisponibles .= "<option value='$fecha'>$fecha</option>";
        }
    } else {
        $fechasDisponibles = "<option value=''>Sin fechas disponibles</option>";
    }

    $stmtFechas->close();
} else {
    $mensaje = "Error en la consulta: " . $conexion->error;
}

$fechaActual = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" href="css/medicacion-mobile.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/medicacion-desktop.css" media="screen and (min-width: 769px)">
    <link rel="stylesheet" type="text/css" media="print" href="css/estilo-impresion.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/time_out.js"></script>
    <title>Medicación</title>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container">
        <div class="mensaje">
            <h2>Medicación</h2>
            <div class="no-imprimir" id="datos-cargados"><?php echo $mensaje; ?></div>
        </div>

        <form method="post" id="medicacion-form">
            <div class="contenedor-fecha no-imprimir">
                <select name="fechas-guardadas" id="fechas-guardadas">
                    <option value="">Seleccionar fecha</option>
                    <?php echo $fechasDisponibles; ?>
                </select>
                <button type="submit" id="guardar-datos">Guardar</button>
                <button type="button" id="eliminar-datos">Eliminar</button>
            </div>
            <div class="medicacion">
                <div class="paciente">
                    <?php
                    // Obtener el ID del paciente de la URL
                    $id_paciente = isset($_GET['id']) ? intval($_GET['id']) : 0;

                    // Realizar una consulta para obtener los datos del paciente por su ID
                    $sql = "SELECT nombre_apellido, dni, obra_social1, obra_social2 FROM pacientes WHERE id = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $id_paciente);
                    $stmt->execute();
                    $stmt->bind_result($nombre_paciente, $dni, $obra_social1, $obra_social2);
                    $stmt->fetch();
                    $stmt->close();
                    ?>

                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre_paciente; ?>" readonly>
                    </div>

                    <div class="campo">
                        <label for="dni">DNI:</label>
                        <input type="text" id="dni" name="dni" value="<?php echo $dni; ?>" readonly>
                    </div>

                    <div class="campo obra-social">
                    <label for="obra_social">Obra Social:</label>
                    <div class="obra-social-inputs">
                        <input type="text" id="obra_social1" name="obra_social1" value="<?php echo $obra_social1; ?>" readonly>
                        <input type="text" id="obra_social2" name="obra_social2" value="<?php echo $obra_social2; ?>" readonly>
                    </div>
                </div>
                </div>

                <section>
                <div class="table-container">
                <div class="table-scroll">
                    <table class="medicacion-table" id="medicacion-table">
                        <thead>
                            <tr>
                                <th>Medicación</th>
                                <th>Droga</th>
                                <th class="narrow-column" >Ayuna</th>
                                <th class="narrow-column">Desayuno</th>
                                <th class="narrow-column">Almuerzo</th>
                                <th class="narrow-column">Merienda</th>
                                <th class="narrow-column">Cena</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $i <= 20; $i++) {
                                echo "<tr>";
                                echo "<td><input type='text' name='medicacion[]' id='medicacion_$i'></td>";
                                echo "<td><input type='text' name='droga[]' id='droga_$i'></td>";
                                echo "<td><input type='text' name='ayuna[]' id='ayuna_$i'></td>";
                                echo "<td><input type='text' name='desayuno[]' id='desayuno_$i'></td>";
                                echo "<td><input type='text' name='almuerzo[]' id='almuerzo_$i'></td>";
                                echo "<td><input type='text' name='merienda[]' id='merienda_$i'></td>";
                                echo "<td><input type='text' name='cena[]' id='cena_$i'></td>";
                                echo "<td><input type='text' name='observaciones[]' id='observaciones_$i'></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                </section>
                <div class="cuadro-evolucion">
                    <h4>Evolución del Paciente</h4>
                    <textarea name="cuadro-evolucion-texto" id="cuadro-evolucion-texto" placeholder="Escribir..." class="input-control" rows="5"></textarea>
                </div>
            </div>
            <input type="hidden" name="fecha-actual" value="<?php echo $fechaActual; ?>">
           
        </form>
    </div>
    <script src="js/expansion-texto-automatica.js"></script>
    <script src="js/lista_pacientes.js"></script>  
    <script>
$(document).ready(function() {
    // Agrega la clase campo-gris a los campos impares
    $('input[name^="medicacion["]:odd').addClass('campo-gris');
    $('input[name^="droga["]:odd').addClass('campo-gris');
    $('input[name^="ayuna["]:odd').addClass('campo-gris');
    $('input[name^="desayuno["]:odd').addClass('campo-gris');
    $('input[name^="almuerzo["]:odd').addClass('campo-gris');
    $('input[name^="merienda["]:odd').addClass('campo-gris');
    $('input[name^="cena["]:odd').addClass('campo-gris');
    $('input[name^="observaciones["]:odd').addClass('campo-gris');
});

// Manejar la acción de cargar datos con AJAX
document.addEventListener("DOMContentLoaded", function () {
    var cargarBoton = document.getElementById("cargar-datos");
    var fechasSelector = document.getElementById("fechas-guardadas");

    cargarBoton.addEventListener("click", function () {
        var fechaSeleccionada = fechasSelector.value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obtener_datos.php?id_paciente=<?php echo $idPaciente; ?>&fecha=" + fechaSeleccionada, true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                var datos = JSON.parse(xhr.responseText);
                if (datos) {
                    var medicacionArray = datos.medicacion.split(',');
                    var drogaArray = datos.droga.split(',');
                    var ayunaArray = datos.ayuna.split(',');
                    var desayunoArray = datos.desayuno.split(',');
                    var almuerzoArray = datos.almuerzo.split(',');
                    var meriendaArray = datos.merienda.split(',');
                    var cenaArray = datos.cena.split(',');
                    var observacionesArray = datos.observaciones.split(',');

                    for (var i = 1; i <= 20; i++) {
                        document.getElementById('medicacion_' + i).value = medicacionArray[i - 1];
                        document.getElementById('droga_' + i).value = drogaArray[i - 1];
                        document.getElementById('ayuna_' + i).value = ayunaArray[i - 1];
                        document.getElementById('desayuno_' + i).value = desayunoArray[i - 1];
                        document.getElementById('almuerzo_' + i).value = almuerzoArray[i - 1];
                        document.getElementById('merienda_' + i).value = meriendaArray[i - 1];
                        document.getElementById('cena_' + i).value = cenaArray[i - 1];
                        document.getElementById('observaciones_' + i).value = observacionesArray[i - 1];
                    }
                    document.getElementById('cuadro-evolucion-texto').value = datos.cuadro_evolucion;
                    document.getElementById('datos-cargados').innerHTML = "Datos cargados correctamente.";
                } else {
                    document.getElementById('datos-cargados').innerHTML = "No se encontraron datos para la fecha seleccionada.";
                }
                            } catch (error) {
                    console.error("Error al analizar JSON:", error);
                    // Manejar el error de análisis aquí
                }
            }
        };
        xhr.send();
    });
});
// Manejar la acción de eliminar datos con AJAX
document.addEventListener("DOMContentLoaded", function () {
    var eliminarBoton = document.getElementById("eliminar-datos");
    var fechasSelector = document.getElementById("fechas-guardadas");
    var datosCargadosDiv = document.getElementById("datos-cargados"); // Elemento donde mostrar el mensaje

    eliminarBoton.addEventListener("click", function () {
        var fechaSeleccionada = fechasSelector.value;

        if (fechaSeleccionada) {
            var confirmacion = confirm("¿Estás seguro de que deseas eliminar los datos para esta fecha?");
            
            if (confirmacion) {
                var xhrEliminar = new XMLHttpRequest();
                xhrEliminar.open("POST", "eliminar_datos.php", true);
                xhrEliminar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhrEliminar.onreadystatechange = function () {
                    if (xhrEliminar.readyState === 4) {
                        if (xhrEliminar.status === 200) {
                            var respuesta = JSON.parse(xhrEliminar.responseText);
                            if (respuesta.success) {
                                // Eliminación exitosa, mostrar mensaje de éxito en datos-cargados
                                datosCargadosDiv.textContent = respuesta.message;
                                // Opcional: Recargar la página o realizar alguna otra acción
                            } else {
                                datosCargadosDiv.textContent = "Error al eliminar los datos: " + respuesta.message;
                            }
                        } else {
                            datosCargadosDiv.textContent = "Error en la solicitud AJAX: " + xhrEliminar.statusText;
                        }
                    }
                };
                
                xhrEliminar.send("id_paciente=<?php echo $idPaciente; ?>&fecha=" + fechaSeleccionada);
            } else {
                datosCargadosDiv.textContent = "Eliminación cancelada.";
            }
        } else {
            datosCargadosDiv.textContent = "Selecciona una fecha antes de eliminar los datos.";
        }
    });
});

// Agrega este script después del código que ya tienes en tu archivo JavaScript
document.addEventListener("DOMContentLoaded", function () {
    var fechasSelector = document.getElementById("fechas-guardadas");

    fechasSelector.addEventListener("change", function () {
        var fechaSeleccionada = this.value;
        var xhr = new XMLHttpRequest(); // Declaración de xhr aquí

        if (fechaSeleccionada) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "obtener_datos.php?id_paciente=<?php echo $idPaciente; ?>&fecha=" + fechaSeleccionada, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    
                    var datos = JSON.parse(xhr.responseText);
                    if (datos) {
                        var medicacionArray = datos.medicacion.split(',');
                        var drogaArray = datos.droga.split(',');
                        var ayunaArray = datos.ayuna.split(',');
                        var desayunoArray = datos.desayuno.split(',');
                        var almuerzoArray = datos.almuerzo.split(',');
                        var meriendaArray = datos.merienda.split(',');
                        var cenaArray = datos.cena.split(',');
                        var observacionesArray = datos.observaciones.split(',');

                        for (var i = 1; i <= 20; i++) {
                            document.getElementById('medicacion_' + i).value = medicacionArray[i - 1];
                            document.getElementById('droga_' + i).value = drogaArray[i - 1];
                            document.getElementById('ayuna_' + i).value = ayunaArray[i - 1];
                            document.getElementById('desayuno_' + i).value = desayunoArray[i - 1];
                            document.getElementById('almuerzo_' + i).value = almuerzoArray[i - 1];
                            document.getElementById('merienda_' + i).value = meriendaArray[i - 1];
                            document.getElementById('cena_' + i).value = cenaArray[i - 1];
                            document.getElementById('observaciones_' + i).value = observacionesArray[i - 1];
                        }
                        document.getElementById('cuadro-evolucion-texto').value = datos.cuadro_evolucion;
                        document.getElementById('datos-cargados').innerHTML = "Datos cargados correctamente.";
                    } else {
                        document.getElementById('datos-cargados').innerHTML = "No se encontraron datos para la fecha seleccionada.";
                    }
                }
            };
            xhr.send();
        } else {
            // Restablece los campos a su estado original o realiza alguna otra acción según tu preferencia
        }
    });
});

    document.getElementById("pacientes-select").addEventListener("change", function() {
    var selectedPatientId = this.value;
    if (selectedPatientId) {
        window.location.href = "medicacion-evolucion?id=" + selectedPatientId;
    }
});
  
</script>
<script>
    $(document).ready(function() {
        // Selector para los campos de medicación dentro de la tabla de medicación
        var medicacionInputs = $('.medicacion-table input[name^="medicacion"]');

        // Selector para los campos de droga dentro de la tabla de medicación
        var drogaInputs = $('.medicacion-table input[name^="droga"]');

        // Configuración del autocompletado para los campos de medicación
        medicacionInputs.autocomplete({
            source: 'obtener_sugerencias.php', // Ruta al script PHP que devuelve las sugerencias
            minLength: 2 // Número mínimo de caracteres antes de activar el autocompletado
        });

        // Configuración del autocompletado para los campos de droga
        drogaInputs.autocomplete({
            source: 'obtener_sugerencias.php', // Ruta al script PHP que devuelve las sugerencias
            minLength: 2 // Número mínimo de caracteres antes de activar el autocompletado
        });

        // Función para cargar las sugerencias de medicación desde la base de datos
        function cargarSugerenciasMedicacion(input) {
            if (input && input.val) {
                // Realiza una solicitud AJAX para obtener las sugerencias de medicación
                $.ajax({
                    url: 'obtener_sugerencias.php',
                    method: 'GET',
                    data: { tipo: 'medicacion', input: input.val() },
                    success: function(response) {
                        try {
                            // Intenta analizar la respuesta JSON
                            var suggestions = JSON.parse(response);
                            // Mostrar las sugerencias en el campo de entrada
                            input.autocomplete({
                                source: suggestions
                            });
                        } catch (error) {
                            console.error('Error al analizar la respuesta JSON:', error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar las sugerencias de medicación:', error);
                    }
                });
            }
        }

        // Función para cargar las sugerencias de droga desde la base de datos
        function cargarSugerenciasDroga(input) {
            if (input && input.val) {
                // Realiza una solicitud AJAX para obtener las sugerencias de droga
                $.ajax({
                    url: 'obtener_sugerencias.php',
                    method: 'GET',
                    data: { tipo: 'droga', input: input.val() },
                    success: function(response) {
                        console.log(response); // Imprimir la respuesta en la consola
                        try {
                            // Intenta analizar la respuesta JSON
                            var suggestions = JSON.parse(response);
                            // Mostrar las sugerencias en el campo de entrada
                            input.autocomplete({
                                source: suggestions
                            });
                        } catch (error) {
                            console.error('Error al analizar la respuesta JSON:', error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar las sugerencias de droga:', error);
                    }
                });
            }
        }

        // Manejar el evento de entrada en los campos de medicación
        $('.medicacion-table').on('input', 'input[name^="medicacion"]', function() {
            var input = $(this);
            cargarSugerenciasMedicacion(input);
        });

        // Manejar el evento de entrada en los campos de droga
        $('.medicacion-table').on('input', 'input[name^="droga"]', function() {
            var input = $(this);
            cargarSugerenciasDroga(input);
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
