<?php
use App\Bbdd\Usuario;
use App\Bbdd\Vehiculo;

require __DIR__. "/../vendor/autoload.php";

Usuario::deleteAll();
Vehiculo::deleteall();
Usuario::crearUsuarios(10);
Vehiculo::crearVehiculos(50);
echo "datos creados";