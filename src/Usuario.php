<?php

namespace Src;

use PDO;
use PDOException;
use Src\Conexion;

class Usuario extends Conexion{

    private int $id;
    private string $email;
    private string $perfil;
    private string $ciudad;
    private string $pass;

    public function __construct(){
        parent::__construct();
    }

    //------------------------------------------------------------------ CRUD
    
    public function delete(){}
    public function create(){
        $q="insert into usuarios(email,pass,ciudad,perfil) values (:e,:p,:c,:a)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$this->email,
                ':p'=>$this->pass,
                ':c'=>$this->ciudad,
                ':a'=>"Usuario",
            ]);
        }catch(PDOException $e){
            die("Error en create: ".$e->getMessage());
        }
        parent::$conexion=null;
    }
    public static function read(){
        parent::crearConexion();
        $q="select * from usuarios order by id desc";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $e){
            die("Error en read: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function update($email,$perfil){
        parent::crearConexion();
        $q=($perfil=="Administrador") ? "update usuarios set perfil='Usuario' where email=:e" : "update usuarios set perfil='Administrador' where email=:e";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$email,
            ]);
        }catch(PDOException $e){
            die("Error en update: ".$e->getMessage());
        }
        parent::$conexion=null;
    }
    
    //------------------------------------------------------------------ Metodos

    public static function devolverCiudades(){
        return ['Almeria', 'Cadiz', 'Cordoba', 'Granada', 'Huelva', 'Jaen', 'Malaga', 'Sevilla'];
    }

    public static function permisosUsuario($email):bool{
        parent::crearConexion();
        $q="select perfil from usuarios where email=:e";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$email,
            ]);
        }catch(PDOException $e){
            die("Error en permisosUsuario: ".$e->getMessage());
        }
        parent::$conexion=null;
        if($stmt->fetch(PDO::FETCH_OBJ)->perfil=='Administrador') return true;
        return false;
    }

    public static function existeEmail($email):bool{
        parent::crearConexion();
        $q="select email from usuarios where email=:e";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$email,
            ]);
        }catch(PDOException $e){
            die("Error en existeEmail: ".$e->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount(); // 0->false  1->true
    }

    public static function comprobarCuenta(string $e, string $p):bool{
        parent::crearConexion();
        $q = "select pass from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':e' => $e,
            ]);
        } catch (PDOException $ex) {
            die("Error en comprobarCredenciales: " . $ex->getMessage());
        }
        parent::$conexion = null;
        if($stmt->rowCount()==0) return false;
        $pass=$stmt->fetch(PDO::FETCH_OBJ)->pass;
        return password_verify($p, $pass);
    }

    //------------------------------------------------------------------ Setters
    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */ 
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Set the value of ciudad
     *
     * @return  self
     */ 
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */ 
    public function setPass($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);

        return $this;
    }
}

?>