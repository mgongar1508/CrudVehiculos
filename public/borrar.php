<?php

use App\Bbdd\Usuario;
use App\Bbdd\Vehiculo;

session_start();
require __DIR__."/../vendor/autoload.php";

if(!isset($_SESSION['email'])){
    header("Location:vehiculos.php");
    die();
}


$id_vehiculo = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if($id_vehiculo){
    $id = Usuario::devolverId($_SESSION['email']);
    $id_usuario = $id[0];
    if(!Vehiculo::vehiculoPerteneceUsuario($id_vehiculo, $id_usuario)){
        $_SESSION['mensaje'] = "ID erronea, no se pudo borrar";
        header("Location:vehiculos.php");
        die();
    }
}

Vehiculo::delete($id_vehiculo);
$_SESSION['mensaje'] = "vehiculo borrado exitosamente";
header("Location:vehiculos.php");
die();