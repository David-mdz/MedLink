<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
include 'conexion.php';
include 'auth.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/estilo-movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="css/estilo-lista.css">
    <link rel="stylesheet" href="css/estilo-general.css">
    <link rel="stylesheet" type="text/css" media="print" href="css/estilo-impresion-lista.css">
    <script src="js/time_out.js"></script>
    <title>Lista de pacientes</title>
</head>
<body>
<?php include 'header.php'; ?>
    <section>
    <div class="lista-pacientes">
        <h3>Lista de pacientes</h3>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th class="numero-celda">N°</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Obra Social 1</th>
                        <th>Obra Social 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, nombre_apellido, dni, fecha_nacimiento, obra_social1, obra_social2 FROM pacientes WHERE id_usuario = $user_id ORDER BY nombre_apellido ASC";
                        $result = $conexion->query($sql);
                        if ($result && $result->num_rows > 0) {
                            $numero = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $numero . "</td>";
                                echo "<td><a href='https://medlink.sytes.net/home?id=" . $row["id"] . "'class='link-paciente'>" . $row["nombre_apellido"] . "</a></td>";                                echo "<td>" . $row["dni"] . "</td>";
                                echo "<td>" . date("d/m/Y", strtotime($row["fecha_nacimiento"])) . "</td>";
                                echo "<td>" . $row["obra_social1"] . "</td>";
                                echo "<td>" . $row["obra_social2"] . "</td>";
                                echo "</tr>";
                                $numero++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No hay pacientes disponibles.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("pacientes-select").addEventListener("change", function() {
        var selectedPatientId = this.value;
        if (selectedPatientId) {
            window.location.href = "lista?id=" + selectedPatientId;
        }
    });
    </script>  
<?php include 'footer.php'; ?>
</body>
</html>