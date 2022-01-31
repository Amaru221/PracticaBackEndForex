<?php
// src/Entity/Vela.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity @ORM\Table(name="vela")
 */
class Vela
{
	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", name = "fecha")
     */
    private $fecha;
    /**
     * @ORM\Column(type="integer", name = "apertura")
     */
    private $apertura;

    /**
     * @ORM\Column(type="integer", name = "alto")
     */
    private $alto;

    /**
     * @ORM\Column(type="integer", name = "bajo")
     */
    private $bajo;

    /**
     * @ORM\Column(type="integer", name = "cierre")
     */
    private $cierre;

    /**
     * @ORM\Column(type="float", name = "volumen")
     */
    private $volumen;


    public function getId(){
        return $this->id;
    }

    public function getFecha(){
        return $this->fecha;
    }

    public function getApertura(){
        return $this->apertura;
    }

    public function getAlto(){
        return $this->alto;
    }

    public function getBajo(){
        return $this->bajo;
    }

    public function getCierre(){
        return $this->cierre;
    }

    public function getVolumen(){
        return $this->volumen;
    }

    public function setFecha($fecha){
        $this->fecha = $fecha;
    }

    public function setApertura($apertura){
        $this->apertura = $apertura;
    }

    public function setAlto($alto){
        $this->alto = $alto;
    }

    public function setBajo($bajo)
    {
        $this->bajo = $bajo;
    }

    public function setCierre($cierre){
        $this->cierre = $cierre;
    }

    public function setVolumen($volumen){
        $this->volumen = $volumen;
    }

}
	
?>