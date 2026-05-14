<?php
session_start();
require "config/conexion.php";

$busqueda = $_GET["busqueda"] ?? "";
$etiquetas = $_GET["etiquetas"] ?? [];


$sql = "SELECT DISTINCT publicaciones.*, usuarios.usuario
        FROM publicaciones
        JOIN usuarios
        ON publicaciones.usuario_id = usuarios.id
        LEFT JOIN publicaciones_etiquetas
        ON publicaciones.id = publicaciones_etiquetas.publicacion_id
        WHERE 1=1";

$tipos = "";
$valores = [];


if (!empty($busqueda)) {

    $sql .= " AND publicaciones.titulo LIKE ?";
    $tipos .= "s";
    $busqueda_like = "%" . $busqueda . "%";
    $valores[] = $busqueda_like;
}

if (!empty($etiquetas)) {

    $placeholders = implode(",", array_fill(0, count($etiquetas), "?"));
    $sql .= " AND publicaciones_etiquetas.etiqueta_id IN ($placeholders)";
    $tipos .= str_repeat("i", count($etiquetas));
    foreach ($etiquetas as $etiqueta_id) {

        $valores[] = $etiqueta_id;
    }
}



$sql .= " ORDER BY publicaciones.fecha DESC";
$stmt = $conn->prepare($sql);

if (!empty($valores)) {

    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ToyTagged</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-md p-4 mb-8">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="index.php" class="text-3xl font-bold text-blue-600">
                ToyTagged
            </a>

            <div class="flex items-center gap-4">
                <a href="index.php" class="text-gray-700 hover:text-blue-600">
                    Inicio
                </a>

                <?php if (isset($_SESSION["usuario_id"])) { ?>

                    <a href="vistas/subir_publicacion.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Subir publicación
                    </a>

                    <a href="vistas/panel.php" class="text-gray-700 hover:text-blue-600">
                        Perfil
                    </a>

                    <?php if ($_SESSION["role"] == "admin") { ?>

                        <a href="vistas/admin.php" class="text-red-500 hover:text-red-700 font-bold">

                            Admin

                        </a>

                    <?php } ?>



                    <span class="text-gray-500">
                        <?php echo $_SESSION["usuario_nombre"]; ?>
                    </span>

                    <a href="controladores/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        Cerrar sesión
                    </a>

                <?php } else { ?>

                    <a href="vistas/login.php" class="text-gray-700 hover:text-blue-600">
                        Login
                    </a>

                    <a href="vistas/registro.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Registro
                    </a>

                <?php } ?>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION["mensaje"])) { ?>
    <div class="max-w-4xl mx-auto mb-6">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
          
            <?php echo $_SESSION["mensaje"]; unset($_SESSION["mensaje"]);?>

        </div>
    </div>
    <?php } ?>





   
    <div class="max-w-6xl mx-auto">

        <h2 class="text-4xl font-bold text-center mb-8">
            Publicaciones
        </h2>
    
        <div class="max-w-4xl mx-auto mb-10">

            <form method="GET"
            class="bg-white rounded-2xl shadow-md p-6">

                <h3 class="text-2xl font-bold mb-6">
                    Buscar publicaciones
                </h3>

    
                <div class="flex flex-col md:flex-row gap-3 mb-6">

                    <input type="text" name="busqueda" placeholder="Buscar publicaciones..." value="<?php echo $_GET['busqueda'] ?? ''; ?>" class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition">

                        Buscar

                    </button>

                </div>

                
                <div class="flex flex-wrap gap-3 mb-6">
                    <?php

                    $sql_todas_etiquetas = "SELECT * FROM etiquetas ORDER BY nombre ASC";
                    $resultado_todas_etiquetas = $conn->query($sql_todas_etiquetas);

                    while ($etiqueta = $resultado_todas_etiquetas->fetch_assoc()) {

                        $checked = "";

                        if (  isset($_GET["etiquetas"])  && in_array($etiqueta["id"], $_GET["etiquetas"])) {

                            $checked = "checked";
                        }
                    ?>

                        <label class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition cursor-pointer">

                            <input type="checkbox" name="etiquetas[]" value="<?php echo $etiqueta["id"]; ?>"
                            <?php echo $checked; ?>>

                            <span class="text-gray-700">
                                <?php echo $etiqueta["nombre"]; ?>
                            </span>

                        </label>
                    <?php } ?>
                </div>

            
                <div class="flex gap-3">

                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition">

                        Filtrar

                    </button>

                    <a href="index.php" class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-xl transition">

                        Ver todas

                    </a>
                </div>
            </form>
        </div>

    
        <div class="text-center mb-8">

            <a href="index.php" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg transition">
                Ver todas
            </a>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                

                <div class="bg-white rounded-xl shadow-md overflow-hidden p-4 hover:shadow-lg transition">
                  
                        <a href="vistas/ver_publicacion.php?id=<?php echo $fila["id"]; ?>">
                        <img src="uploads/<?php echo $fila["imagen"]; ?>" class="w-full max-h-80 object-contain bg-gray-100 rounded-lg mb-4 hover:scale-[1.02] transition">

                   

                    <div class="p-4">

                        <h3 class="text-2xl font-bold mb-2">
                            <?php echo $fila["titulo"]; ?>
                        </h3>

                        <p class="text-gray-700 mb-4">
                            <?php echo $fila["descripcion"]; ?>
                        </p>

                        <p class="text-sm text-gray-500 mb-4">
                            Subido por:
                            <?php echo $fila["usuario"]; ?>
                        </p>
                        </a>

                        <?php
                        $publicacion_id = $fila["id"];

                        $sql_etiquetas = "SELECT etiquetas.id, etiquetas.nombre
                                        FROM etiquetas
                                        JOIN publicaciones_etiquetas
                                        ON etiquetas.id = publicaciones_etiquetas.etiqueta_id
                                        WHERE publicaciones_etiquetas.publicacion_id = ?";

                        $stmt_etiquetas = $conn->prepare($sql_etiquetas);
                        $stmt_etiquetas->bind_param("i", $publicacion_id);
                        $stmt_etiquetas->execute();

                        $resultado_etiquetas = $stmt_etiquetas->get_result();
                        ?>

                        <div class="mb-4">

                            <?php while ($et = $resultado_etiquetas->fetch_assoc()) { ?>

                                <a href="index.php?etiqueta=<?php echo $et["id"]; ?>" class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm mr-2 mb-2 hover:bg-blue-200 transition">
                                    <?php echo $et["nombre"]; ?>
                                </a>

                            <?php } ?>
                        </div>

                        <?php if ( isset($_SESSION["usuario_id"]) && $_SESSION["usuario_id"] == $fila["usuario_id"]) { ?>

                            <div class="flex gap-3">

                                <a href="vistas/editar_publicacion.php?id=<?php echo $fila["id"]; ?>" class="flex-1 bg-green-500 text-white text-center py-2 rounded-lg hover:bg-green-600 transition">
                                    Editar
                                </a>

                                <a href="controladores/borrar_publicacion.php?id=<?php echo $fila["id"]; ?>" onclick="return confirm('¿Seguro que quieres borrar esta publicación?');" class="flex-1 bg-red-500 text-white text-center py-2 rounded-lg hover:bg-red-600 transition">
                                    Borrar
                                </a>

                            </div>
                        <?php } ?>
                    </div>
                    </div>
                               
            <?php } ?>
        </div>
    </div>
</body>
</html>