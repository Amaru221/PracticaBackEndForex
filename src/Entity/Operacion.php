<?php
// src/Entity/Operacion.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity @ORM\Table(name="operacion")
 */
class Operacion
{
	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint",name="id")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name = "usuario", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $usuario;

    /**
     * @ORM\Column(type="bigint", name = "fecha_op")
     */
    private $fecha_op;

    /**
     * @ORM\Column(type="boolean", name = "tipo_op")
     */
    private $tipo_op;

    /**
     * @ORM\Column(type="float", name = "entrada")
     */
    private $entrada;

    /**
     * @ORM\Column(type="float", name = "salida")
     */
    private $salida;

    /**
     * @ORM\Column(type="boolean", name = "estado")
     */
    private $estado;

    /**
     * @ORM\Column(type="bigint", name = "fecha_cierre")
     */
    private $fecha_cierre;

    public function getId(){
    	return $this->id;
    }

    public function getUsuario(){
    	return $this->usuario;
    }

    public function getFecha_op(){
    	return $this->fecha_op;
    }

    public function getTipo_op(){
    	return $this->tipo_op;
    }

    public function getEntrada(){
    	return $this->entrada;
    }

    public function getSalida(){
    	return $this->salida;
    }

    public function getEstado(){
    	return $this->estado;
    }

    public function getFecha_Cierre(){
    	return $this->fecha_cierre;
    }

    public function setUsuario($usuario)
    {
    	$this->usuario = $usuario;
    }

    public function setFecha_op($fecha){
    	$this->fecha_op = $fecha;
    }

    public function setTipo_op($tipo){
    	$this->tipo_op = $tipo;
    }

    public function setEntrada($entrada){
    	$this->entrada = $entrada;
    }

    public function setSalida($salida){
    	$this->salida = $salida;
    }

    public function setEstado($estado){
    	$this->estado = $estado;
    }

    public function setFecha_Cierre($fecha_cierre){
    	$this->fecha_cierre = $fecha_cierre;
    }


}

?>