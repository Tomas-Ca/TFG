<?php
session_start();
require "conexion.php";

if (isset($_GET["etiqueta"])) {

    $etiqueta_id = $_GET["etiqueta"];

    $sql = "SELECT publicaciones.*, usuarios.usuario 
            FROM publicaciones
            JOIN usuarios ON publicaciones.usuario_id = usuarios.id
            JOIN publicaciones_etiquetas 
            ON publicaciones.id = publicaciones_etiquetas.publicacion_id
            WHERE publicaciones_etiquetas.etiqueta_id = $etiqueta_id
            ORDER BY fecha DESC";

} else {

    $sql = "SELECT publicaciones.*, usuarios.usuario 
            FROM publicaciones 
            JOIN usuarios ON publicaciones.usuario_id = usuarios.id
            ORDER BY fecha DESC";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicaciones</title>
</head>
<body>

<h2>Publicaciones</h2>
<a href="index.php">Ver todas</a>
<br><br>

<?php if (isset($_SESSION["usuario_id"])) { ?>
    <a href="logout.php">Cerrar sesión</a>
<?php } ?>

<?php while ($fila = $resultado->fetch_assoc()) { ?>

    <div style="border:1px solid black; margin:10px; padding:10px;">
        
        <h3><?php echo $fila["titulo"]; ?></h3>
        
        <p><?php echo $fila["descripcion"]; ?></p>
        
        <p>Subido por: <?php echo $fila["usuario"]; ?></p>

        <?php
        $publicacion_id = $fila["id"];

        $sql_etiquetas = "SELECT etiquetas.id, etiquetas.nombre
                        FROM etiquetas
                        JOIN publicaciones_etiquetas 
                        ON etiquetas.id = publicaciones_etiquetas.etiqueta_id
                        WHERE publicaciones_etiquetas.publicacion_id = $publicacion_id";

        $resultado_etiquetas = $conn->query($sql_etiquetas);
        ?>

        <p>Etiquetas:
        <?php while ($et = $resultado_etiquetas->fetch_assoc()) { ?>
            <a href="index.php?etiqueta=<?php echo $et["id"]; ?>">
                [<?php echo $et["nombre"]; ?>]
            </a>
        <?php } ?>
        </p>
        
        <img src="uploads/<?php echo $fila["imagen"]; ?>" width="200">

    </div>
    <?php if (isset($_SESSION["usuario_id"]) && $_SESSION["usuario_id"] == $fila["usuario_id"]) { ?>

    <a href="borrar_publicacion.php?id=<?php echo $fila["id"]; ?>">
        Borrar
    </a>
    <br>
    <a href="editar_publicacion.php?id=<?php echo $fila["id"]; ?>">
        Editar
    </a>
<?php } ?>

<?php } ?>

</body>
</html>