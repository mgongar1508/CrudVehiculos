<?php
namespace App\Bbdd;

use \PDO;
use \PDOException;

class Conexion{
    private static ?PDO $conexion = null;
    
    public static function getConexion(): PDO{
        if(self::$conexion == null){
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion(){
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
        $dotenv->load();

        $usuario = $_ENV['USUARIO'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $port = $_ENV['PORT'];
        $base = $_ENV['DATABASE'];

        $dsn = "mysql:host=$host;dbname=$base;port=$port;charset=utf8mb4";

        $opciones = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try{
            self::$conexion = new PDO($dsn, $usuario, $pass, $opciones);
        }catch(PDOException $e){
            die("Error en la conexion ". $e->getMessage());
        }
    }
}