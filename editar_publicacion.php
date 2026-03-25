<?php
session_start();
require "conexion.php";

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

if ($resultado->num_rows === 1) {

    $publicacion = $resultado->fetch_assoc();

    if ($publicacion["usuario_id"] != $_SESSION["usuario_id"]) {
        echo "No tienes permisos";
        exit();
    }

} else {
    echo "Publicación no encontrada";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar publicación</title>
</head>
<body>

<h2>Editar publicación</h2>

<form action="actualizar_publicacion.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $publicacion["id"]; ?>">

    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?php echo $publicacion["titulo"]; ?>" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion"><?php echo $publicacion["descripcion"]; ?></textarea><br><br>

    <button type="submit">Actualizar</button>

</form>

</body>
</html>