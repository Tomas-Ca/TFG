<?php
session_start();


require "../config/conexion.php";


$id = $_POST["id"];
$usuario = $_POST["usuario"];
$password = $_POST["password"];


if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios 
            SET usuario = ?, password = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $usuario, $password_hash, $id);


} else {

    $sql = "UPDATE usuarios 
            SET usuario = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $usuario, $id);
}



if ($stmt->execute()) {

    $_SESSION["usuario"] = $usuario;

    
    echo "Usuario actualizado";

} else {

    echo "Error: " . $stmt->error;
}
?>