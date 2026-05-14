<?php
session_start();
require "../config/conexion.php";



if (!isset($_GET["id"])) {
    echo "Publicación no encontrada";
    exit();
}

$id = $_GET["id"];

$sql = "SELECT publicaciones.*, usuarios.usuario
        FROM publicaciones
        JOIN usuarios
        ON publicaciones.usuario_id = usuarios.id
        WHERE publicaciones.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo "Publicación no encontrada";
    exit();
}

$publicacion = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $publicacion["titulo"]; ?></title>
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

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-md overflow-hidden">

        <img src="../uploads/<?php echo $publicacion["imagen"]; ?>" class="w-full max-h-[600px] object-contain bg-gray-100">

        <div class="p-8">

            <h1 class="text-4xl font-bold mb-4">
                <?php echo $publicacion["titulo"]; ?>
            </h1>


            <p class="text-gray-500 mb-6">
                Subido por:
                <?php echo $publicacion["usuario"]; ?>
            </p>


            <p class="text-gray-700 text-lg leading-relaxed mb-8">
                <?php echo $publicacion["descripcion"]; ?>
            </p>


            <?php
            $sql_etiquetas = "SELECT etiquetas.id, etiquetas.nombre
                            FROM etiquetas
                            JOIN publicaciones_etiquetas
                            ON etiquetas.id = publicaciones_etiquetas.etiqueta_id
                            WHERE publicaciones_etiquetas.publicacion_id = ?";

            $stmt_etiquetas = $conn->prepare($sql_etiquetas);
            $stmt_etiquetas->bind_param("i", $id);
            $stmt_etiquetas->execute();

            $resultado_etiquetas = $stmt_etiquetas->get_result();


            ?>

            <div class="flex flex-wrap gap-3">

                <?php while ($et = $resultado_etiquetas->fetch_assoc()) { ?>

                    <a href="../index.php?etiqueta=<?php echo $et["id"]; ?>" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full hover:bg-blue-200 transition">

                        <?php echo $et["nombre"]; ?>

                    </a>

                <?php } ?>
            </div>


            <?php if (isset($_SESSION["usuario_id"])) { ?>

                <div class="mt-10">

                    <h2 class="text-2xl font-bold mb-4">
                        Añadir comentario
                    </h2>

                    <form action="../controladores/guardar_comentario.php" method="POST">

                        <input type="hidden" name="publicacion_id"  value="<?php echo $publicacion["id"]; ?>">

                        <textarea name="comentario" required placeholder="Escribe un comentario..."  class="w-full border border-gray-300 rounded-xl p-4 mb-4 h-32 resize-none"></textarea>

                        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition">

                            Comentar

                        </button>
                    </form>
                </div>
            <?php } ?>



            <div class="mt-12">

                <h2 class="text-2xl font-bold mb-6">
                    Comentarios
                </h2>

                <?php

                $sql_comentarios = "SELECT comentarios.*, usuarios.usuario
                                    FROM comentarios
                                    JOIN usuarios
                                    ON comentarios.usuario_id = usuarios.id
                                    WHERE comentarios.publicacion_id = ?
                                    ORDER BY comentarios.fecha DESC";

                $stmt_comentarios = $conn->prepare($sql_comentarios);
                $stmt_comentarios->bind_param("i", $id);
                $stmt_comentarios->execute();
                $resultado_comentarios = $stmt_comentarios->get_result();

                ?>

                <?php if ($resultado_comentarios->num_rows > 0) { ?>

                    <div class="space-y-4">

                        <?php while ($comentario = $resultado_comentarios->fetch_assoc()) { ?>

                            <div class="bg-gray-100 rounded-xl p-4">

                                <div class="flex justify-between items-center mb-2">

                                    <p class="font-bold text-gray-800">
                                        <?php echo $comentario["usuario"]; ?>
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        <?php echo $comentario["fecha"]; ?>
                                    </p>

                                </div>

                                <p class="text-gray-700">
                                    <?php echo $comentario["comentario"]; ?>
                                </p>



                                <?php if (isset($_SESSION["usuario_id"]) && ($_SESSION["usuario_id"] == $comentario["usuario_id"] || $_SESSION["role"] == "admin")) { ?>

                                    <div class="mt-3">

                                        <a href="../controladores/borrar_comentario.php?id=<?php echo $comentario["id"]; ?>" onclick="return confirm('¿Seguro que quieres borrar este comentario?');" class="text-red-500 hover:text-red-700 text-sm font-semibold">

                                            Borrar comentario

                                        </a>

                                    </div>

                                <?php } ?>

                            </div>
                        <?php } ?>
                    </div>

                    <?php } else { ?>

                        <p class="text-gray-500">
                            Aún no hay comentarios.
                        </p>

                    <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>