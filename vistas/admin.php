<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

if ($_SESSION["role"] != "admin") {

    echo "Acceso denegado";
    exit();
}

$sql_usuarios = "SELECT * FROM usuarios ORDER BY id DESC";
$resultado_usuarios = $conn->query($sql_usuarios);


$sql_publicaciones = "SELECT publicaciones.*, usuarios.usuario
                      FROM publicaciones
                      JOIN usuarios
                      ON publicaciones.usuario_id = usuarios.id
                      ORDER BY publicaciones.fecha DESC";

$resultado_publicaciones = $conn->query($sql_publicaciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Panel Admin</title>
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-white shadow-md p-4 mb-8">

        <div class="max-w-6xl mx-auto flex justify-between items-center">

            <a href="../index.php" class="text-3xl font-bold text-blue-600">
                ToyTagged
            </a>

            <a href="../index.php" class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Volver
            </a>

        </div>

    </nav>

    <div class="max-w-6xl mx-auto">

        <h1 class="text-4xl font-bold mb-10">
            Panel de administración
        </h1>


        <div class="bg-white rounded-2xl shadow-md p-8 mb-10">

            <h2 class="text-3xl font-bold mb-6">
                Usuarios
            </h2>

            <div class="space-y-4">
                <?php while ($usuario = $resultado_usuarios->fetch_assoc()) { ?>

                    <div class="border rounded-xl p-4 flex justify-between items-center">

                        <div>

                            <p class="font-bold">
                                <?php echo $usuario["usuario"]; ?>
                            </p>

                            <p class="text-gray-500">
                                <?php echo $usuario["email"]; ?>
                            </p>

                            <p class="text-sm text-gray-400">
                                Role: <?php echo $usuario["role"]; ?>
                            </p>


                            <div class="mt-3">
                                <a href="../controladores/admin_borrar_usuario.php?id=<?php echo $usuario["id"]; ?>" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition inline-block">

                                    Borrar usuario

                                </a>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h2 class="text-3xl font-bold mb-6">
                Publicaciones
            </h2>

            <div class="space-y-4">

                <?php while ($pub = $resultado_publicaciones->fetch_assoc()) { ?>

                    <div class="border rounded-xl p-4 flex justify-between items-center gap-4">

                        <div class="flex items-center gap-4">
                            <img src="../uploads/<?php echo $pub["imagen"]; ?>" class="w-24 h-24 object-contain bg-gray-100 rounded-lg">
                            <div>

                                <p class="font-bold">
                                    <?php echo $pub["titulo"]; ?>
                                </p>

                                <p class="text-gray-500">
                                    <?php echo $pub["usuario"]; ?>
                                </p>

                            </div>
                        </div>

                        <a href="../controladores/borrar_publicacion.php?id=<?php echo $pub["id"]; ?>" onclick="return confirm('¿Seguro que quieres borrar esta publicación?');" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">

                            Borrar

                        </a>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>