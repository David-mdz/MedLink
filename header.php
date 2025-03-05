<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="favicon" href="Imagenes\Logo.png" type="image/ico">
    <link rel="icon" href="Imagenes\Logo.png" type="image/ico">
    <link rel="shortcut icon" href="Imagenes\Logo.png" type="image/x-icon">
    <link rel="icon" href="http://localhost/medlink/Imagenes/Logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>
<header>
        <nav class="no-imprimir">
            <ul>
            <li class="icon-only" id="pacientes-dropdown">
                <span class="mobile-icon"><i class="fas fa-users"></i></span>
                <select id="pacientes-select" class="hidden-select">
                        <option value="selecciona" hidden>Pacientes</option>
                        <?php
                        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                        if ($user_id !== null) {
                            $sql = "SELECT id, nombre_apellido FROM pacientes WHERE id_usuario = $user_id ORDER BY nombre_apellido ASC"; 
                            $result = $conexion->query($sql);
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["id"] . "'>" . $row["nombre_apellido"] . "</option>";
                                }
                            } else {
                                echo "No hay pacientes disponibles.";
                            }
                        } else {
                            echo "El usuario no está autenticado.";
                        }
                    ?>
                    </select>
                </li>
                <li><a href="lista"><span class="mobile-icon" ><i class="fas fa-list"></i></span ><span class="ocular-texto">Lista</a></li>
                <li class="submenu-parent">
                <span class="mobile-icon"><i class="fas fa-user"></i></span> <span class="ocular-texto" style="font-family: 'Poppins', sans-serif;font-size: 20px;color: var(--text-light-grey);">Datos Personales</span>
                    <ul class="submenu">
                        <li><a href="home">Editar Datos Personales</a></li>
                        <li><a href="certificado-supervivencia">Certificado de Supervivencia</a></li>
                    </ul>
                </li>
                <li class="submenu-parent">
                <span class="mobile-icon"><i class="fas fa-medkit"></i></span> <span class="ocular-texto" style="font-family: 'Poppins', sans-serif;font-size: 20px;color: var(--text-light-grey);">Medicación</span>
                    <ul class="submenu">
                        <li><a href="medicacion-evolucion">Medicación y Evolucion</a></li>
                        <li><a href="recetas">Recetas</a></li>
                        <li><a href="insumos">Insumos</a></li>
                    </ul>
                </li>         
                <li><a href="examen-fisico"><span class="mobile-icon"><i class="fas fa-stethoscope"></i></span><span class="ocular-texto">Examen Físico</a></li>
            </ul>
        </nav>
    </header>
    <script>
        // Obtener el elemento del ícono en la lista de pacientes
        const pacientesDropdown = document.getElementById('pacientes-dropdown');
        // Obtener la lista de pacientes
        const pacientesSelect = document.getElementById('pacientes-select');

        // Agregar un evento de clic/touch al ícono
        pacientesDropdown.addEventListener('click', function() {
            // Hacer clic en el select al tocar el ícono (solo en dispositivos móviles)
            if (window.innerWidth <= 768) {
                pacientesSelect.click();
            }
        });
    </script>
</body>
</html>