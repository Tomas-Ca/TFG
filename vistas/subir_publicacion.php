<?php include "layout.php"; ?>

<?php

require "../config/conexion.php";
$sql_etiquetas = "SELECT * FROM etiquetas ORDER BY nombre ASC";
$resultado_etiquetas = $conn->query($sql_etiquetas);

?>

<h2 class="text-2xl font-bold mb-6 text-center">
    Nueva publicación
</h2>

<div class="flex justify-center">

    <form action="../controladores/guardar_publicacion.php" method="POST"  enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">

        <label class="block mb-2 font-semibold">Título</label>
        <input type="text" name="titulo" required class="w-full mb-4 border border-gray-300 rounded-lg px-4 py-2">

        <label class="block mb-2 font-semibold">Descripción</label>
        <textarea name="descripcion" class="w-full mb-4 border border-gray-300 rounded-lg px-4 py-2"></textarea>

        <label class="block mb-2 font-semibold">Imagen</label>
        <input type="file" name="imagen" required class="w-full mb-6">

        <label class="block mb-3 font-semibold">
            Etiquetas
        </label>

        <div class="flex flex-wrap gap-3 mb-6">

            <?php while ($etiqueta = $resultado_etiquetas->fetch_assoc()) { ?>

                <label class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition cursor-pointer">

                    <input type="checkbox" name="etiquetas[]" value="<?php echo $etiqueta["id"]; ?>">

                    <span>
                        <?php echo $etiqueta["nombre"]; ?>
                    </span>

                </label>

            <?php } ?>

        </div>


        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
            Subir publicación
        </button>

    </form>
</div>
<?php include "footer.php"; ?>