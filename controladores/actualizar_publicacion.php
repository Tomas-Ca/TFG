<?php
session_start();

require "../config/conexion.php";

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

if ($resultado->num_rows !== 1) {

    echo "Publicación no encontrada";
    exit();
}

$publicacion = $resultado->fetch_assoc();


if (
    $publicacion["usuario_id"] != $_SESSION["usuario_id"]
    && $_SESSION["role"] != "admin"
) {

    echo "No tienes los permisos necesarios";
    exit();
}


$sql_update = "
    UPDATE publicaciones
    SET titulo = ?, descripcion = ?
    WHERE id = ?";

$stmt_update = $conn->prepare($sql_update);

$stmt_update->bind_param(
    "ssi",
    $titulo,
    $descripcion,
    $id
);

$stmt_update->execute();


$sql_delete = "
    DELETE FROM publicaciones_etiquetas
    WHERE publicacion_id = ?";

$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $id);
$stmt_delete->execute();


if (isset($_POST["etiquetas"])) {

    foreach ($_POST["etiquetas"] as $etiqueta_id) {

        $sql_insert = "
            INSERT INTO publicaciones_etiquetas
            (publicacion_id, etiqueta_id)
            VALUES (?, ?)";

        $stmt_insert = $conn->prepare($sql_insert);

        $stmt_insert->bind_param(
            "ii",
            $id,
            $etiqueta_id);

        $stmt_insert->execute();
    }
}



$_SESSION["mensaje"] = "Publicación actualizada correctamente";
header("Location: ../index.php");
exit();
?>