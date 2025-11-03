<?php
namespace App\Bbdd;

use Exception;
use PDO;
use PDOException;

class Usuario extends Conexion{
    private int $id;
    private string $email;
    private string $password;

    private static function executeQuery(string $q, array $opciones = [], bool $devuelve=false){
        $stmt = self::getConexion() -> prepare($q);

        try{
           count($opciones) ? $stmt->execute($opciones) : $stmt->execute();
        }catch(PDOException $e){
            throw new Exception("Error en query ". $e->getMessage());
        }
        if($devuelve) return $stmt;
    }

    public function create(){
        $q = "insert into usuarios(email, password) values(:e, :p)";
        self::executeQuery($q, [':e'=>$this->email, ':p'=>$this->password], false);
    }

    public static function crearUsuarios(int $cantidad){
        $faker = \Faker\Factory::create('es_ES');
        for($i=0;$i<$cantidad;$i++){
            $email = $faker->unique()->freeEmail();
            $password = "secreto1234";

            (new Usuario) 
                ->setEmail($email)
                ->setPassword($password)
                ->create();
        }
    }

    public static function devolverId(?string $email = null){
        $q = $email == null ? "select id from usuarios" : "select id from usuarios where email=:e";
        $opciones = $email == null? [] : [":e"=>$email];
        $stmt = self::executeQuery($q, $opciones, true);
        $datos = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ids= [];
        foreach($datos as $item){
            $ids[] = $item->id;
        }
        return $ids;
    }

    public static function validarUsuario(string $email, string $password){
        $q = "select password from usuarios where email=:e";
        $stmt = self::executeQuery($q, [':e'=>$email], true);
        $p = $stmt->fetch(PDO::FETCH_OBJ);
        return ($p && password_verify($password, $p->password));

    }

    public static function deleteAll(){
        $q = "delete from usuarios";
        self::executeQuery($q);
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }
}