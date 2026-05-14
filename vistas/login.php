<?php include "layout.php"; ?>

<div class="flex items-center justify-center min-h-[70vh]">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-6">
            Iniciar sesión
        </h2>

        <form action="../controladores/procesar_login.php" method="POST">
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Correo
                </label>
                <input type="text" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-semibold">
                    Contraseña
                </label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Entrar
            </button>

        </form>

        <p class="text-center mt-6 text-gray-600">
            ¿Aún no tienes cuenta?
            <a href="registro.php" class="text-blue-500 hover:underline">
                Regístrate
            </a>
        </p>
    </div>
</div>
<?php include "footer.php"; ?>