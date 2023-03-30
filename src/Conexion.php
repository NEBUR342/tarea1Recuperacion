<?php
namespace Src;
use PDO;
use PDOException;
class Conexion{
    protected static $conexion;
    public function __construct(){
        self::crearConexion();
    }
    protected static function crearConexion(){
        if (self::$conexion!=null) return;
        $dotenv =\Dotenv\Dotenv::CreateImmutable(__DIR__."/../");
        $dotenv->load();
        // cargamos los datos de .env
        $db = $_ENV['DATABASE'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        try{
            self::$conexion = new PDO ($dsn, $user, $pass, $opciones);
        }catch(PDOException $ex){
            die("Error en conexión: " . $ex->getMessage());
        }
    }
}
?>