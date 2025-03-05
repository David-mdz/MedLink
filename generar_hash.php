<?php
$password = "prueba";
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Contraseña original: $password<br>";
echo "Hash de contraseña: $hash";
?>