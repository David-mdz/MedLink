<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

$idPaciente = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";
$fechaSeleccionada = "";


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fechaSeleccionada = isset($_POST['fechas-guardadas']) ? $_POST['fechas-guardadas'] : null;
    $fechaActual = date('Y-m-d'); // Obtener la fecha actual en formato 'AAAA-MM-DD'

    if ($fechaSeleccionada === null || $fechaSeleccionada === "") {
        $fechaSeleccionada = date('n');
    }
    $insumos = [
        "Gasas", "Cinta", "Cinta Hipolergenica", "Solucion fisiologica", "Dextroza",
        "Renger", "Abocat", "Jeringas", "Jeringas 10ml", "Jeringas 20ml", "Agujas",
        "Frasco Estéril", "k 29", "Guia de suero", "Foley", "Foley siliconada",
        "Mascara de oxigeno", "Mascara de Nebulizacion", "Bolsa colectora de urina", "Bisturi",
        "Lidocaina", "Pervinox", "Agua oxigenada"
    ];
    // Ordenar el array alfabéticamente
    sort($insumos);

    if ($user_id === null) {
        $mensaje = "El usuario no está autenticado.";
    } else {
        
        if ($idPaciente <= 0) {
            $mensaje = "Seleccione un Paciente antes de guardar o modificar.";
        } else {
            $index = 0;
            foreach ($insumos as $index => $insumo) {
                $cantidad = isset($_POST["cantidad_$index"]) ? intval($_POST["cantidad_$index"]) : 0;
                $fechaActual = date('Y-m-d'); // Obtener la fecha actual en el formato adecuado
            
                $sqlVerificar = "SELECT id_insumo, cantidad, fecha_registro FROM insumos WHERE id_paciente = ? AND fecha_registro = ? AND insumo = ? AND id_usuario = ?";
                $stmtVerificar = $conexion->prepare($sqlVerificar);
            
                if ($stmtVerificar) {
                    $stmtVerificar->bind_param("issi", $idPaciente, $fechaActual, $insumo, $user_id);
                    $stmtVerificar->execute();
                    $resultVerificar = $stmtVerificar->get_result();
            
                    if ($resultVerificar->num_rows === 1) {
                        // Si existe un registro para el insumo en esa fecha, actualiza la cantidad
                        $row = $resultVerificar->fetch_assoc();
                        $id_insumo_existente = $row['id_insumo'];
                        $cantidad_existente = $row['cantidad'];
            
                        // Realiza la actualización solo si la fecha de registro coincide con la fecha actual
                        if ($row['fecha_registro'] === $fechaActual) {
                            $cantidad_existente = $cantidad;
            
                            $sqlUpdate = "UPDATE insumos SET cantidad = ? WHERE id_insumo = ?";
                            $stmtUpdate = $conexion->prepare($sqlUpdate);
            
                            if ($stmtUpdate) {
                                $stmtUpdate->bind_param("ii", $cantidad_existente, $id_insumo_existente);
                                $stmtUpdate->execute();
                                $stmtUpdate->close();
                            } else {
                                $mensaje = "Error al actualizar datos: " . $conexion->error;
                            }
                        } else {
                            // Si la fecha de registro no coincide, realiza un insert
                            $sqlInsert = "INSERT INTO insumos (id_usuario, id_paciente, insumo, cantidad, fecha_registro) VALUES (?, ?, ?, ?, ?)";
                            $stmtInsert = $conexion->prepare($sqlInsert);
                            
                            if ($stmtInsert) {
                                $stmtInsert->bind_param("iisss", $user_id, $idPaciente, $insumo, $cantidad, $fechaActual);
                                $stmtInsert->execute();
                                $stmtInsert->close();
                            } else {
                                $mensaje = "Error al insertar datos: " . $conexion->error;
                            }
                            
                        }
                    } elseif ($resultVerificar->num_rows === 0) {
                        // Si no existe, inserta un nuevo registro
                        $sqlInsert = "INSERT INTO insumos (id_usuario, id_paciente, insumo, cantidad, fecha_registro) VALUES (?, ?, ?, ?, ?)";
                        $stmtInsert = $conexion->prepare($sqlInsert);
                        
                        if ($stmtInsert) {
                            $stmtInsert->bind_param("iisss", $user_id, $idPaciente, $insumo, $cantidad, $fechaActual);
                            $stmtInsert->execute();
                            $stmtInsert->close();
                        } else {
                            $mensaje = "Error al insertar datos: " . $conexion->error;
                        }
                        
                    } else {
                        $mensaje = "Error en la verificación de datos: múltiples registros encontrados.";
                    }
                    $stmtVerificar->close();
                } else {
                    $mensaje = "Error en la consulta de verificación: " . $conexion->error;
                }
            }
            $mensaje = "Datos guardados correctamente.";
        }
    }
    // Consulta para obtener el consumo mensual por insumo y paciente
    $sqlConsultaConsumoMensual = "SELECT insumo, SUM(cantidad) AS consumo_mensual FROM insumos WHERE id_paciente = ? GROUP BY insumo";
    $stmtConsultaConsumoMensual = $conexion->prepare($sqlConsultaConsumoMensual);

    if ($stmtConsultaConsumoMensual) {
        $stmtConsultaConsumoMensual->bind_param("i", $idPaciente);
        $stmtConsultaConsumoMensual->execute();
        $resultConsultaConsumoMensual = $stmtConsultaConsumoMensual->get_result();

        $consumoMensual = []; // Array para almacenar el consumo mensual por insumo
        while ($row = $resultConsultaConsumoMensual->fetch_assoc()) {
            $consumoMensual[$row['insumo']] = $row['consumo_mensual'];
        }

        $stmtConsultaConsumoMensual->close();
    } else {
        $mensaje = "Error en la consulta de consumo mensual: " . $conexion->error;
    }
}
// Consulta para obtener el consumo total por insumo
$sqlConsumoTotal = "SELECT insumo, SUM(cantidad) AS consumo_total FROM insumos GROUP BY insumo";
$stmtConsumoTotal = $conexion->prepare($sqlConsumoTotal);

if ($stmtConsumoTotal) {
    $stmtConsumoTotal->execute();
    $resultConsumoTotal = $stmtConsumoTotal->get_result();

    $consumoTotal = []; // Array para almacenar el consumo total por insumo
    while ($row = $resultConsumoTotal->fetch_assoc()) {
        $consumoTotal[$row['insumo']] = $row['consumo_total'];
    }

    $stmtConsumoTotal->close();
} else {
    $mensaje = "Error en la consulta de consumo total: " . $conexion->error;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" href="css/estilo-insumos.css">
    <title>Insumos</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="contenedor">
    <form method="POST" action="#">
        <div class="paciente-fecha">
            <div class="paciente">
                <label class="label-paciente" for="paciente">Paciente:</label>
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
                <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            </div>
            <div class="contenedor-fecha">
                <label for="fechas-guardadas"></label>
                <select name="fechas-guardadas" id="fechas-guardadas">
                    <option value="">Seleccionar Mes</option>
                    <?php
                    $meses = [
                        1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio",
                        7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
                    ];

                    // Obtener el mes seleccionado
                    $mesSeleccionado = isset($_POST['fechas-guardadas']) ? $_POST['fechas-guardadas'] : date('n');

                    foreach ($meses as $numeroMes => $nombreMes) {
                        $selected = ($mesSeleccionado == $numeroMes) ? 'selected' : '';
                        echo "<option value='$numeroMes' $selected>$nombreMes</option>";
                    }
                    ?>
                </select>
                <button class="boton-guardar" type="submit" id="guardar-datos">Guardar</button>
                <button class="boton-eliminar" type="button" id="eliminar-datos">Eliminar</button>
            </div>

            </div>
            <table class="tabla-insumos">
                <thead>
                    <tr>
                        <th class="numero-celda">N°</th>
                        <th>Insumo</th>
                        <th>Stock</th>
                        <th>Cantidad</th> 
                        <th>Consumo Mensual</th>
                        <th>Consumo Total</th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php
                    // Tu array de insumos
                    $insumos = [
                        "Gasas", "Cinta", "Cinta Hipolergenica", "Solucion fisiologica", "Dextroza",
                        "Renger", "Abocat", "Jeringas", "Jeringas 10ml", "Jeringas 20ml", "Agujas",
                        "Frasco Estéril", "k 29", "Guia de suero", "Foley", "Foley siliconada",
                        "Mascara de oxigeno", "Mascara de Nebulizacion", "Bolsa colectora de orina", "Bisturi",
                        "Lidocaina", "Pervinox", "Agua oxigenada"
                    ];
                    
                    // Ordenar el array alfabéticamente
                    sort($insumos);
                    
                    foreach ($insumos as $index => $insumo) {
                        echo "<tr>";
                            echo "<td class='numero-celda'>" . ($index + 1) . "</td>";
                            echo "<td>$insumo</td>";
                            echo "<td><input type='number' class='input-stock' name='stock_$index' min='0'></td>";
                            echo "<td><input type='number' class='input-cantidad' name='cantidad_$index' min='0'></td>";
                            echo "<td><class='input-mensual' name='mensual_$index' min='0'></td>";
                            echo "<td><class='input-total' name='total_$index' min='0'></td>";
                        echo "</tr>";
                    }

                    ?>

                </tbody>
            </table>
        </div>
    </form>
</div>
<script>
        document.getElementById("pacientes-select").addEventListener("change", function() {
        var selectedPatientId = this.value;
        if (selectedPatientId) {
            window.location.href = "insumos?id=" + selectedPatientId;
        }
    });
</script>




<?php include 'footer.php'; ?>
</body>
</html>
