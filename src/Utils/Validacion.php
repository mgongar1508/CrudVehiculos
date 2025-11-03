<?php
namespace App\Utils;

use App\Bbdd\Usuario;

class Validacion{
     public static function sanearCadena(string $str): string{
        return htmlspecialchars(trim($str));
    }

    public static function longitudCampoValida(string $str, string $campo, int $min, int $max): bool{
        if(strlen($str) < $min || strlen($str) > $max){
            $_SESSION["err_".$campo] = "*** error el campo $campo esperaba entre $min y $max de longitud";
            return false;
        }   
        return true;
    }


    public static function pintarErr(string $err){
        if(isset($_SESSION[$err])){
            echo "<p class='text-red-500 italic text-sm'>{$_SESSION[$err]}</p>";
            unset($_SESSION[$err]);
        }
    }

    public static function emailValido(string $email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        $_SESSION["err_email"] = "*** EMAIL no valido";
        return false;
    }

    public static function isLoginValido(string $email, string $password){
        if(!Usuario::validarUsuario($email, $password)){
            $_SESSION["err_validacion"] = "Login no valido, email o password incorrecto";
            return false;
        }
        return true;
    }
}