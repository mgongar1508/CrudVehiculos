<!DOCTYPE html>
<?php
session_start();

use App\Utils\Validacion;

require __DIR__."/../vendor/autoload.php";

if(isset($_POST['email'])){
    $email = Validacion::sanearCadena($_POST["email"]);
    $password = Validacion::sanearCadena($_POST["password"]);
    $errores = false;
    if(!Validacion::longitudCampoValida($password, "password", 3, 12)) $errores = true;
    if(!Validacion::emailValido($email)) $errores = true;
    if(!Validacion::isLoginValido($email, $password)) $errores = true;

    if($errores){
        header("Location:index.php");
        die();
    }
    //no errores, login exitoso
    $_SESSION['email'] = $email;
    header("Location:vehiculos.php");
}
?>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Tailwindcss -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDn SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>

<body class="p-8 bg-blue-200">
    <h3 class="text-center text-xl font-bold mb-2">Login</h3>

    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-sm">
        <form method="POST" action="index.php">
            <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Iniciar Sesión</h2>

            <!-- Campo de Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-2">Correo Electrónico</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3">
                    <i class="fa-solid fa-envelope text-gray-400"></i>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        required
                        placeholder="tucorreo@ejemplo.com"
                        class="w-full py-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg" />
                </div>
                <?php Validacion::pintarErr("err_email"); ?>
            </div>

            <!-- Campo de Password -->
            <div class="mb-6">
                <label for="password" class="block text-gray-600 mb-2">Contraseña</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full py-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg" />
                </div>
                <?php Validacion::pintarErr("err_password"); ?>
            </div>

            <!-- Botón de Enviar -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Entrar
            </button>
            <?php Validacion::pintarErr("err_validacion"); ?>

            <!-- Enlace de Registro -->
            <p class="text-center text-gray-500 text-sm mt-4">
                ¿No tienes cuenta?
                <a href="#" class="text-blue-600 hover:underline">Regístrate</a>
            </p>
        </form>
    </div>
</body>

</html>