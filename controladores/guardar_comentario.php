<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

$comentario = $_POST["comentario"];
$publicacion_id = $_POST["publicacion_id"];
$usuario_id = $_SESSION["usuario_id"];

$sql = "INSERT INTO comentarios (publicacion_id, usuario_id, comentario) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iis",
    $publicacion_id,
    $usuario_id,
    $comentario
);

if ($stmt->execute()) {

    $_SESSION["mensaje"] = "Comentario publicado";

    header("Location: ../vistas/ver_publicacion.php?id=" . $publicacion_id);
    exit();

} else {

    echo "Error: " . $stmt->error;
}
?>