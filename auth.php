<?php
session_start();

// Verificar si la variable de sesión 'authenticated' está establecida y es verdadera
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // La autenticación es exitosa, obtener el ID del usuario desde la base de datos y almacenarlo en la sesión
    include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

    // Obtener el ID del usuario basado en el nombre de usuario (suponiendo que 'usuario' sea único)
    $consulta_usuario = $conexion->prepare("SELECT id FROM formulario WHERE usuario = ?");
    $consulta_usuario->bind_param("s", $_POST["usuario"]); // Reemplaza $_POST["usuario"] con la variable que contiene el nombre de usuario
    $consulta_usuario->execute();
    $consulta_usuario->bind_result($idUsuario);
    $consulta_usuario->fetch();
    $consulta_usuario->close();

    // Verificar si se obtuvo el ID del usuario y establecerlo en la sesión
    if ($idUsuario) {
        $_SESSION['user_id'] = $idUsuario;
    }
} else {
    // La autenticación falló, redirigir al usuario a la página de inicio de sesión
    header('Location: index');
    exit;
}
?>
