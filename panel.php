<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión";
    exit();
}
?>

<!-- Esto lo voy a convertir en el perfil de usuario más adelante -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel</title>
</head>
<body>

<h2>Bienvenido <?php echo $_SESSION["usuario_nombre"]; ?> </h2>

<p>Has iniciado sesión.</p>

</body>
</html>