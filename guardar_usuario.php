<?php
require "conexion.php";

$usuario = $_POST["usuario"];
$email = $_POST["email"];
$password = $_POST["password"];

$password_segura = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (usuario, email, password) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $usuario, $email, $password_segura);

if ($stmt->execute()) {
    echo "Usuario registrado correctamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>