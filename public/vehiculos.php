<!DOCTYPE html>
<?php

use App\Bbdd\Usuario;
use App\Bbdd\Vehiculo;

session_start();
require __DIR__ . "/../vendor/autoload.php";
if (!isset($_SESSION["email"])) {
    header("Location:index.php");
    die();
}
$email = $_SESSION['email'];
$vehiculos = Vehiculo::read();

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
    <title>Productos</title>
</head>

<body class="bg-blue-200">
    <div class="relative overflow-x-auto p-8">
        <div class="flex flex-row-reverse mb-2">
            <a href="nuevo.php" class="p-2 rounded-xl text-white bg-green-500 hover:bg-green-700">
                <i class="fas fa-add mr-1"></i>NUEVO
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Marca
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Modelo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descripcion
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vehiculos as $item):
                $id_usuario = Usuario::devolverId($email)[0]; 
                $colorFilaUsuario=($item->usuario_id==$id_usuario) ? "bg-green-200" : "bg-gray-800";
                
                ?>
                
                <tr class="<?= $colorFilaUsuario; ?> border-b dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?= $item->marca ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= $item->modelo ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $item->tipo ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $item->precio ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $item->descripcion ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($item->usuario_id==$id_usuario): ?>
                        <form method="POST" action="borrar.php">  
                            <input type="hidden" name="id" value="<?$item->id?>">
                            <button type="submit">
                                <i class="fas fa-trash text-red-600"></i>
                            </button>
                            <a href="update.php?id=<?= $item->id?>"> 
                                <i class="fas fa-edit text-orange-500 mr-2"></i>
                            </a>
                        </form>
                        <?php else: ?>
                            No Disponible
                        <?php endif; ?> 
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php 
        if(isset($_SESSION['mensaje'])){
            echo <<< TXT
            <script>
                Swal.fire({
                icon: "success",
                title: "{$_SESSION['mensaje']}",
                showConfirmButton: false,
                timer: 1500
                });
            </script>
            TXT;
            unset($_SESSION['mensaje']);
        }
    ?>

</body>

</html>