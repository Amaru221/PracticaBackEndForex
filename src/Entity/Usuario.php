<?php
// src/Entity/Usuario.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity @ORM\Table(name="usuario")
 */
class Usuario implements UserInterface, \Serializable
{
	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name = "nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", name = "contrasena")
     */
    private $contrasena;


    public function getId()
    {
        return $this->id;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getContrasena(){
        return $this->contrasena;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setContrasena($contrasena){
        $this->contrasena = $contrasena;
    }


    public function serialize(){
        return serialize(array(
            $this->id,
            $this->nombre,
            $this->contrasena,
        ));
    }

    public function unserialize($serialized){
        list (
            $this->id,
            $this->nombre,
            $this->contrasena,
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        return array('ROLE_USER');          
    }

     public function getPassword()
    {
        return $this->getContrasena();
    }

    public function getSalt()
    {
        return;
    }

    public function getUsername()
    {
        return $this->getNombre();
    }

    public function eraseCredentials()
    {
        return;
    }

}


?>