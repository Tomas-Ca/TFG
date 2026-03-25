<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h2>Registro de usuario</h2>

<form action="guardar_usuario.php" method="POST">
    
    <label>Usuario:</label><br>
    <input type="text" name="usuario" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Registrarse</button>

</form>

</body>
</html>