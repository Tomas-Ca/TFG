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

    if ($publicacion["usuario_id"] == $_SESSION["usuario_id"]) {

        $sql_delete = "DELETE FROM publicaciones WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();

        echo "Publicación eliminada";

    } else {
        echo "No tienes permiso para borrar esta publicación";
    }

} else {
    echo "Publicación no encontrada";
}
?>