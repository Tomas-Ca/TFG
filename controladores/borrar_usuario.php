<?php
session_start();

require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes haber iniciado sesión";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

$sql_relaciones = "
    DELETE publicaciones_etiquetas
    FROM publicaciones_etiquetas
    JOIN publicaciones
    ON publicaciones.id = publicaciones_etiquetas.publicacion_id
    WHERE publicaciones.usuario_id = ?
";

$stmt_relaciones = $conn->prepare($sql_relaciones);
$stmt_relaciones->bind_param("i", $usuario_id);
$stmt_relaciones->execute();

$sql_publicaciones = "
    DELETE FROM publicaciones
    WHERE usuario_id = ?
";

$stmt_publicaciones = $conn->prepare($sql_publicaciones);
$stmt_publicaciones->bind_param("i", $usuario_id);
$stmt_publicaciones->execute();

$sql_usuario = "
    DELETE FROM usuarios
    WHERE id = ?
";

$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);



if ($stmt_usuario->execute()) {

    session_unset();
    session_destroy();

    echo "Cuenta eliminada";

} else {

    echo "Error: " . $stmt_usuario->error;
}
?>