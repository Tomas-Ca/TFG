<?php
session_start();
require "../config/conexion.php";


if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}


if ($_SESSION["role"] != "admin") {

    echo "Acceso denegado";
    exit();
}


$id = $_GET["id"];


if ($id == $_SESSION["usuario_id"]) {

    echo "No es posible eliminar esta cuenta.";
    exit();
}



$sql_relaciones = "
    DELETE publicaciones_etiquetas
    FROM publicaciones_etiquetas
    JOIN publicaciones
    ON publicaciones.id = publicaciones_etiquetas.publicacion_id
    WHERE publicaciones.usuario_id = ?
";

$stmt_relaciones = $conn->prepare($sql_relaciones);
$stmt_relaciones->bind_param("i", $id);
$stmt_relaciones->execute();



$sql_comentarios = "
    DELETE FROM comentarios
    WHERE usuario_id = ?
";

$stmt_comentarios = $conn->prepare($sql_comentarios);
$stmt_comentarios->bind_param("i", $id);
$stmt_comentarios->execute();


$sql_publicaciones = "
    DELETE FROM publicaciones
    WHERE usuario_id = ?
";

$stmt_publicaciones = $conn->prepare($sql_publicaciones);
$stmt_publicaciones->bind_param("i", $id);
$stmt_publicaciones->execute();


$sql_usuario = "
    DELETE FROM usuarios
    WHERE id = ?
";

$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id);

if ($stmt_usuario->execute()) {

    $_SESSION["mensaje"] = "Usuario eliminado correctamente";
    header("Location: ../vistas/admin.php");
    exit();

} else {

    echo "Error al eliminar usuario";
}
?>