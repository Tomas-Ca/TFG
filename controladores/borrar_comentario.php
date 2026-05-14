<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

$id = $_GET["id"];


$sql = "SELECT * FROM comentarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {

    echo "Comentario no encontrado";
    exit();
}

$comentario = $resultado->fetch_assoc();


if (
    $comentario["usuario_id"] != $_SESSION["usuario_id"]
    && $_SESSION["role"] != "admin"
) {

    echo "No tienes permisos necesarios";
    exit();
}


$publicacion_id = $comentario["publicacion_id"];


$sql_delete = "DELETE FROM comentarios WHERE id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $id);

if ($stmt_delete->execute()) {

    $_SESSION["mensaje"] = "Comentario eliminado";

    header("Location: ../vistas/ver_publicacion.php?id=" . $publicacion_id);
    exit();

} else {

    echo "Error al eliminar comentario";
}
?>