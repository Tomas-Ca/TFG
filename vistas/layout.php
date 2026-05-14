<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ToyTagged</title>
</head>

<body class="bg-gray-100">

<nav class="bg-white shadow p-4 mb-6">
    <div class="max-w-6xl mx-auto flex justify-between">

        <a href="../index.php" class="font-bold text-blue-600 text-xl">
            ToyTagged
        </a>

        <div class="flex gap-4 items-center">
            <a href="../index.php">Inicio</a>

            <?php if (isset($_SESSION["usuario_id"])) { ?>

                <a href="subir_publicacion.php">Subir</a>
                <a href="panel.php">Perfil</a>

                <span class="text-gray-600">
                    <?php echo $_SESSION["usuario_nombre"]; ?>
                </span>

                <a href="../controladores/logout.php" class="text-red-500">
                    Salir
                </a>

            <?php } else { ?>

                <a href="login.php">Login</a>
                <a href="registro.php">Registro</a>

            <?php } ?>

        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto">