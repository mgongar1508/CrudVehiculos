<?php
namespace App\Bbdd;

use Exception;
use PDO;
use PDOException;

class Vehiculo extends Conexion{
    private int $id;
    private string $marca;
    private string $modelo;
    private string $tipo;
    private float $precio;
    private string $descripcion;
    private int $usuario_id;

    private static function executeQuery(string $q, array $opciones = [], bool $devolverAlgo=false){
        $stmt = self::getConexion() -> prepare($q);

        try{
           $stmt->execute($opciones);
        }catch(PDOException $e){
            throw new Exception("Error en query ". $e->getMessage());
        }
        if($devolverAlgo) return $stmt;
    }

    public static function read(?int $id=null){
        $q = $id ==null ? "select * from vehiculo order by id desc" : "select * from vehiculo where id=:i";
        $parametros = $id==null ? [] : [':i'=>$id];
        $stmt = self::executeQuery($q, $parametros, true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create(){
        $q = "insert into vehiculo(marca, modelo, tipo, precio, descripcion, usuario_id) values(:ma, :mo, :t, :p, :d, :u)";
        self::executeQuery($q, [':ma'=>$this->marca, ':mo'=>$this->modelo, ':t'=>$this->tipo, ':p'=>$this->precio, 
        ':d'=>$this->descripcion, ':u'=>$this->usuario_id,]);
    }

    public function update(int $id){
        $q = "update vehiculo set marca=:ma, modelo=:mo, tipo=:t, precio=:p, descripcion=:d where id=:i";
        self::executeQuery($q, [':ma'=>$this->marca, ':mo'=>$this->modelo, ':t'=>$this->tipo, ':p'=>$this->precio, 
        ':d'=>$this->descripcion, ':i'=>$id]);
    }

    public static function vehiculoPerteneceUsuario(int $id_v, int $id_u){
        $q = "select id from vehiculo where id=:idv and usuario_id=:idu";
        $stmt = self::executeQuery($q, [':idv'=>$id_v, ':idu'=>$id_u], true);
        $dato = $stmt->fetchAll(PDO::FETCH_OBJ);
        return count($dato);
    }

    public static function crearVehiculos(int $cantidad){
        $faker = \Faker\Factory::create('es_ES');
        $ids = Usuario::devolverId();
        for($i=0;$i<$cantidad;$i++){
            $marca = $faker->company();
            $modelo = $faker->sentence();
            $tipo = $faker->randomElement(['Coche', 'Moto', 'Camión', 'Furgoneta', 'Otro']);
            $precio = $faker->randomFloat(2, 0, 999999.99);
            $descripcion = $faker->sentence(mt_rand(8, 15));
            $usuario_id = $faker->randomElement($ids);

            (new Vehiculo) 
                ->setMarca($marca)
                ->setModelo($modelo)
                ->setTipo($tipo)
                ->setPrecio($precio)
                ->setDescripcion($descripcion)
                ->setUsuarioId($usuario_id)
                ->create();
        }
    }

    public static function delete(int $id){
        $q = "delete from vehiculo where id=:i";
        self::executeQuery($q, [':i'=>$id]);
    }

    public static function deleteAll(){
        $q = "delete from vehiculo";
        self::executeQuery($q, []);
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of marca
     */
    public function getMarca(): string
    {
        return $this->marca;
    }

    /**
     * Set the value of marca
     */
    public function setMarca(string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get the value of modelo
     */
    public function getModelo(): string
    {
        return $this->modelo;
    }

    /**
     * Set the value of modelo
     */
    public function setModelo(string $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     */
    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of usuario_id
     */
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    /**
     * Set the value of usuario_id
     */
    public function setUsuarioId(int $usuario_id): self
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }
}