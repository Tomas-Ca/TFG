<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

$id = $_GET["id"];

$sql = "SELECT * FROM publicaciones WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {

    echo "Publicación no encontrada";
    exit();
}

$publicacion = $resultado->fetch_assoc();

if ($publicacion["usuario_id"] != $_SESSION["usuario_id"] && $_SESSION["role"] != "admin") {

    echo "No tienes permiso para hacer eso";
    exit();
}

$sql_etiquetas = "SELECT * FROM etiquetas ORDER BY nombre ASC";
$resultado_etiquetas = $conn->query($sql_etiquetas);



$sql_actuales = "
    SELECT etiqueta_id
    FROM publicaciones_etiquetas
    WHERE publicacion_id = ?
";

$stmt_actuales = $conn->prepare($sql_actuales);
$stmt_actuales->bind_param("i", $id);
$stmt_actuales->execute();
$resultado_actuales = $stmt_actuales->get_result();
$etiquetas_actuales = [];

while ($fila = $resultado_actuales->fetch_assoc()) {

    $etiquetas_actuales[] = $fila["etiqueta_id"];
}
?>

<?php include "layout.php"; ?>

<h2 class="text-3xl font-bold text-center mb-8">
    Editar publicación
</h2>

<div class="flex justify-center">

    <form action="../controladores/actualizar_publicacion.php" method="POST" class="bg-white p-8 rounded-2xl shadow-md w-full max-w-2xl">

        <input type="hidden" name="id" value="<?php echo $publicacion["id"]; ?>">

        
        <div class="mb-6">

            <img src="../uploads/<?php echo $publicacion["imagen"]; ?>" class="w-full max-h-96 object-contain bg-gray-100 rounded-xl">

        </div>

        
        <label class="block mb-2 font-semibold">
            Título
        </label>

        <input type="text" name="titulo" value="<?php echo $publicacion["titulo"]; ?>" required class="w-full mb-6 border border-gray-300 rounded-xl px-4 py-3">

     
        <label class="block mb-2 font-semibold">
            Descripción
        </label>

        <textarea name="descripcion" class="w-full mb-6 border border-gray-300 rounded-xl px-4 py-3 h-40"><?php echo $publicacion["descripcion"]; ?></textarea>

      
        <label class="block mb-3 font-semibold">
            Etiquetas
        </label>

        <div class="flex flex-wrap gap-3 mb-8">
            <?php while ($etiqueta = $resultado_etiquetas->fetch_assoc()) { ?>
                <?php
                $checked = "";

                if (in_array($etiqueta["id"], $etiquetas_actuales)) {

                    $checked = "checked";
                }
                ?>

                <label class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition cursor-pointer">
                    <input
                    type="checkbox"
                    name="etiquetas[]"
                    value="<?php echo $etiqueta["id"]; ?>"
                    <?php echo $checked; ?>>

                    <span>
                        <?php echo $etiqueta["nombre"]; ?>
                    </span>

                </label>

            <?php } ?>

        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition">

            Actualizar publicación

        </button>
    </form>
</div>
<?php include "footer.php"; ?>