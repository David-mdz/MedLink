<?php
include "conexion.php"; // Reemplaza "conexion.php" con la ruta correcta si es necesario


session_start(); // Inicia la sesión

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"]; 

    // Realizar consulta para verificar si el usuario existe y obtener el hash de la contraseña
    $consulta_usuario = $conexion->prepare("SELECT id, contrasena_hash FROM formulario WHERE usuario = ?");
    $consulta_usuario->bind_param("s", $usuario);
    $consulta_usuario->execute();
    $consulta_usuario->store_result(); // Almacenar los resultados antes de ejecutar otra consulta
    $consulta_usuario->bind_result($id, $contrasena_hash);
    $consulta_usuario->fetch();
    $consulta_usuario->close(); // Cerrar el resultado para liberar los recursos

    // Verificar si el usuario existe y si la contraseña es válida
    if ($contrasena_hash && password_verify($contrasena, $contrasena_hash)) {
        // Inicio de sesión exitoso, establecer la variable de sesión y redireccionar
        $_SESSION['authenticated'] = true;
    
        // Asignar el id del usuario a la sesión
        $_SESSION['user_id'] = $id;
    
        header("Location: home");
        exit;
    } else {
        // Credenciales inválidas, mostrar un mensaje de error
        $mensaje_error = "Credenciales inválidas. Por favor, verifica tu usuario y contraseña.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="css/estilos-login.css">
    <link rel="favicon" href="Imagenes\Logo.png" type="image/ico">
    <link rel="icon" href="Imagenes\Logo.png" type="image/ico">
    <link rel="shortcut icon" href="Imagenes\Logo.png" type="image/x-icon">
    <link rel="icon" href="http://localhost/medlink/Imagenes/Logo.png" type="image/png">
</head>
<body>

    <section class="forma-main">

        <div class="form-content">
            <div class="box">
                <div class="imagen-logo">
                    <img src="Imagenes\Logo-Historiaclinica.png" alt="Logo de la empresa">
                </div>
                <h3>Med-Link</h3>
                <form action="" method="post">
                    <div class="input-box">
                        <input type="text" name="usuario" placeholder="Usuario" class="input-control" autocomplete="username">
                    </div>
                    <div class="input-box">
                        <input type="password" name="contrasena" placeholder="Contraseña" class="input-control" autocomplete="current-password">
                    </div>
                    <button type="submit" class="btm">Iniciar Sesión</button>
                </form>
                <?php if (isset($mensaje_error)) { ?>
                    <p><?php echo $mensaje_error; ?></p>
                <?php } ?>
            </div>
        </div>
        <footer class="no-imprimir">
            <p>&copy; 2023 Med-Link</p>
        </footer>
    </section>
    
</body>
</html>
