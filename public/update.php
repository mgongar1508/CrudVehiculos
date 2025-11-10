<?php

use App\Bbdd\Usuario;
use App\Bbdd\Vehiculo;
use App\Utils\Validacion;

session_start();
require __DIR__ . "/../vendor/autoload.php";

if (!isset($_SESSION['email'])) {
    header("Location:index.php");
}
$email = $_SESSION['email'];

$id_vehiculo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$id_vehiculo){
    header("Location:vehiculos.php");
    exit;
}

$id_usuario = Usuario::devolverId($email)[0];
if(!Vehiculo::vehiculoPerteneceUsuario($id_vehiculo, $id_usuario)){
    $_SESSION['mensaje'] = "ID erronea, no se pudo actualizar";
    header("Location:vehiculos.php");
    die();
}

$vehiculo = Vehiculo::read($id_vehiculo)[0];

$tipos = ['Coche'=> "", 'Moto'=>"", 'Camión'=>"", 'Furgoneta'=>"", 'Otro'=>""];
foreach($tipos as $k=>$v){
    $tipos[$k] = $k == $vehiculo->tipo ? "checked" : "";
}

if(isset($_POST['marca'])){
    $marca = Validacion::sanearCadena($_POST['marca']);
    $modelo = Validacion::sanearCadena($_POST['modelo']);
    $tipo = $_POST['tipo'] ?? "error";
    $tipo = Validacion::sanearCadena($tipo);
    $precio = Validacion::sanearCadena($_POST['precio']);
    $precio = (float) $precio;
    $descripcion = Validacion::sanearCadena($_POST['descripcion']);
    $usuario_id = Usuario::devolverId($email)[0];
    $errores = false;

    if(!Validacion::longitudCampoValida($marca, 'marca', 2, 100)) $errores=true;
    if(!Validacion::longitudCampoValida($modelo, 'modelo', 2, 100)) $errores=true;
    if(!Validacion::longitudCampoValida($precio, 'precio', 0, 999999.99)) $errores=true;
    if(!Validacion::longitudCampoValida($descripcion, 'descripcion', 2, 500)) $errores=true;
    if(!Validacion::esTipoValido($tipo)) $errores = true;

    if($errores){
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }

    (new Vehiculo)
        ->setMarca($marca)
        ->setModelo($modelo)
        ->setTipo($tipo)
        ->setPrecio($precio)
        ->setDescripcion($descripcion)
        ->update($id_vehiculo);

    $_SESSION['mensaje'] = "El vehiculo ha sido actualizado con exito";
    header("Location:vehiculos.php");
}

?>

<!DOCTYPE html>
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
    <title>Document</title>
</head>

<body class="p-8 bg-blue-200">
    <h3 class="text-center text-xl font-bold mb-2">Actualizar Vehiculo</h3>
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md space-y-4">
        <form method="POST" action="<?= $_SERVER['PHP_SELF']."?id=".$id_vehiculo ?>">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Registro de Vehículo</h2>

            <!-- Marca -->
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                <input type="text" id="marca" name="marca" value="<?= $vehiculo->marca ?>"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <?php Validacion::pintarErr("err_marca") ?>

            <!-- Modelo -->
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                <input type="text" id="modelo" name="modelo" value="<?= $vehiculo->modelo ?>"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <?php Validacion::pintarErr("err_modelo") ?>

            <!-- Tipo de vehículo -->
            <div>
                <span class="block text-sm font-medium text-gray-700 mb-1">Tipo de vehículo</span>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="Coche" <?= $tipos['Coche'] ?>  class="text-blue-600 focus:ring-blue-500" />
                        Coche
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="Moto" <?= $tipos['Moto'] ?> class="text-blue-600 focus:ring-blue-500" />
                        Moto
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="Camión" <?= $tipos['Camión'] ?> class="text-blue-600 focus:ring-blue-500" />
                        Camión
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="Furgoneta" <?= $tipos['Furgoneta'] ?> class="text-blue-600 focus:ring-blue-500" />
                        Furgoneta
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="tipo" value="Otro" <?= $tipos['Otro'] ?> class="text-blue-600 focus:ring-blue-500" />
                        Otro
                    </label>
                </div>
            </div>
            <?php Validacion::pintarErr("err_tipo") ?>

            <!-- Precio -->
            <div>
                <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio (€)</label>
                <input type="number" id="precio" name="precio" min="0" step="0.01" value="<?= $vehiculo->precio ?>"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <?php Validacion::pintarErr("err_precio") ?>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"><?= $vehiculo->descripcion ?></textarea>
            </div>
            <?php Validacion::pintarErr("err_descripcion") ?>

            <!-- Botón de envío -->
            <button type="submit"
                class="p-4 bg-blue-600 text-white font-medium py-2 rounded-xl hover:bg-blue-700 transition">
                <i class="fa-solid fa-paper-plane mr-2"></i>Enviar
            </button>
            <a href="vehiculos.php"
                class="bg-red-400 hover:bg-red-500 text-gray-800 font-semibold px-4 py-2 rounded-lg shadow">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </form>
    </div>
</body>

</html>