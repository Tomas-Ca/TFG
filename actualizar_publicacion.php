<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión";
    exit();
}

$id = $_POST["id"];
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];

$sql = "SELECT * FROM publicaciones WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $publicacion = $resultado->fetch_assoc();

    if ($publicacion["usuario_id"] == $_SESSION["usuario_id"]) {

        $sql_update = "UPDATE publicaciones SET titulo = ?, descripcion = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $titulo, $descripcion, $id);
        $stmt_update->execute();

        echo "Publicación actualizada";

    } else {
        echo "No tienes permisos";
    }

} else {
    echo "Publicación no encontrada";
}
?>