<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión";
    exit();
}
?>

<?php
$sql_etiquetas = "SELECT * FROM etiquetas ORDER BY nombre ASC";
$resultado_etiquetas = $conn->query($sql_etiquetas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir publicación</title>
</head>
<body>

<h2>Nueva publicación</h2>

<form action="guardar_publicacion.php" method="POST" enctype="multipart/form-data">

    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion"></textarea><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" required><br><br>

    <button type="submit">Subir</button>

    <label>Etiquetas:</label><br>

    <?php while ($etiqueta = $resultado_etiquetas->fetch_assoc()) { ?>

        <input type="checkbox" name="etiquetas[]" value="<?php echo $etiqueta["id"]; ?>">
        <?php echo $etiqueta["nombre"]; ?><br>

    <?php } ?>

<br>
</form>

</body>
</html>