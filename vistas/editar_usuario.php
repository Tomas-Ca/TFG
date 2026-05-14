<?php
session_start();

require "../config/conexion.php";

if (!isset($_SESSION["usuario_id"])) {

    echo "Debes iniciar sesión";
    exit();
}

$id = $_SESSION["usuario_id"];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {

    echo "Usuario no encontrado";
    exit();
}

$usuario = $resultado->fetch_assoc();
?>

<?php include "layout.php"; ?>

<h2 class="text-3xl font-bold text-center mb-8">
    Editar usuario
</h2>

<div class="flex justify-center">

    <form
    action="../controladores/actualizar_usuario.php"
    method="POST"
    class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">

        <input
        type="hidden"
        name="id"
        value="<?php echo $usuario["id"]; ?>">


        <label class="block mb-2 font-semibold">
            Usuario
        </label>



        <input type="text" name="usuario" value="<?php echo $usuario["usuario"]; ?>" required class="w-full mb-6 border border-gray-300 rounded-xl px-4 py-3">

      

        <label class="block mb-2 font-semibold">
            Nueva contraseña
        </label>

        <input type="password" name="password" placeholder="Dejar en blanco para no cambiarla" class="w-full mb-8 border border-gray-300 rounded-xl px-4 py-3">

        <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition">

            Actualizar usuario

        </button>
    </form>
</div>
<?php include "footer.php"; ?>