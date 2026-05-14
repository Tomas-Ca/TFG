<?php
session_start();
require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión";
    exit();
}

$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$usuario_id = $_SESSION["usuario_id"];

$extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
$nombre_imagen = uniqid() . "." . $extension;

$ruta_temporal = $_FILES["imagen"]["tmp_name"];
$ruta_destino = "../uploads/" . $nombre_imagen;

if (!is_dir("../uploads")) {
    mkdir("../uploads", 0777, true);
}


move_uploaded_file($ruta_temporal, $ruta_destino);


$sql = "INSERT INTO publicaciones (usuario_id, titulo, descripcion, imagen)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $usuario_id, $titulo, $descripcion, $nombre_imagen);

if ($stmt->execute()) {

    $publicacion_id = $stmt->insert_id;

    if (isset($_POST["etiquetas"])) {

        foreach ($_POST["etiquetas"] as $etiqueta_id) {

            $sql_etiqueta = "INSERT INTO publicaciones_etiquetas (publicacion_id, etiqueta_id)
                            VALUES (?, ?)";

            $stmt_etiqueta = $conn->prepare($sql_etiqueta);
            $stmt_etiqueta->bind_param("ii", $publicacion_id, $etiqueta_id);
            $stmt_etiqueta->execute();
            
        }
    }

    $_SESSION["mensaje"] = "Publicación creada correctamente";
    header("Location: ../index.php");
    exit();

} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>