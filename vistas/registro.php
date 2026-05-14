<?php include "layout.php"; ?>

<h2 class="text-3xl font-bold text-center mb-6">
    Registro de usuario
</h2>


<div class="flex justify-center">

    <form action="../controladores/guardar_usuario.php" method="POST"
    class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">

        <label class="block mb-2 font-semibold">Usuario</label>
        <input type="text" name="usuario" required class="w-full mb-4 border border-gray-300 rounded-lg px-4 py-2">


        <label class="block mb-2 font-semibold">Email</label>
        <input type="email" name="email" required  class="w-full mb-4 border border-gray-300 rounded-lg px-4 py-2">


        <label class="block mb-2 font-semibold">Contraseña</label>
        <input type="password" name="password" required  class="w-full mb-6 border border-gray-300 rounded-lg px-4 py-2">


        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
            Registrarse
        </button>

    </form>
</div>
<?php include "footer.php"; ?>