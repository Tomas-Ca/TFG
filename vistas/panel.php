<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

$sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();


$sql_publicaciones = "SELECT *
                      FROM publicaciones
                      WHERE usuario_id = ?
                      ORDER BY fecha DESC";

$stmt_publicaciones = $conn->prepare($sql_publicaciones);
$stmt_publicaciones->bind_param("i", $usuario_id);
$stmt_publicaciones->execute();
$resultado_publicaciones = $stmt_publicaciones->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mi perfil</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-md p-4 mb-8">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="../index.php"
            class="text-3xl font-bold text-blue-600">
                ToyTagged
            </a>

            <a href="../index.php"
            class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Volver
            </a>

        </div>

    </nav>

    <div class="max-w-6xl mx-auto">

        <div class="bg-white rounded-2xl shadow-md p-8 mb-10">

            <h1 class="text-4xl font-bold mb-6">
                Mi perfil
            </h1>

            <div class="space-y-3 text-lg">

                <p>
                    <span class="font-bold">Usuario:</span>
                    <?php echo $usuario["usuario"]; ?>
                </p>

                <p>
                    <span class="font-bold">Email:</span>
                    <?php echo $usuario["email"]; ?>
                </p>

            </div>

            <div class="flex gap-4 mt-8">

                <a href="editar_usuario.php" class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition">
                    Editar perfil
                </a>

                <a href="../controladores/borrar_usuario.php" onclick="return confirm('¿Seguro que quieres eliminar tu cuenta?');" class="bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 transition">
                    Eliminar cuenta
                </a>

            </div>
        </div>

        <h2 class="text-3xl font-bold mb-6">
            Mis publicaciones
        </h2>

        <?php if ($resultado_publicaciones->num_rows > 0) { ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php while ($pub = $resultado_publicaciones->fetch_assoc()) { ?>

                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                        <img  src="../uploads/<?php echo $pub["imagen"]; ?>" class="w-full h-64 object-contain bg-gray-100">

                        <div class="p-4">

                            <h3 class="text-2xl font-bold mb-2">
                                <?php echo $pub["titulo"]; ?>
                            </h3>

                            <p class="text-gray-700 mb-4">
                                <?php echo $pub["descripcion"]; ?>
                            </p>

                            <div class="flex gap-3">

                                <a href="editar_publicacion.php?id=<?php echo $pub["id"]; ?>" class="flex-1 bg-green-500 text-white text-center py-2 rounded-lg hover:bg-green-600 transition">

                                    Editar

                                </a>

                                <a href="../controladores/borrar_publicacion.php?id=<?php echo $pub["id"]; ?>" onclick="return confirm('¿Seguro que quieres borrar esta publicación?');"  class="flex-1 bg-red-500 text-white text-center py-2 rounded-lg hover:bg-red-600 transition">

                                    Borrar

                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="bg-white rounded-2xl shadow-md p-8">

                <p class="text-gray-500 text-lg">
                    Aún no hay publicaciones.
                </p>

            </div>

        <?php } ?>
    </div>
</body>
</html>