<?php

namespace INHack20\ConsultorioBundle\Model;
use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Persona
 * 
 * @ORM\MappedSuperclass
 */
abstract class Persona {
    
    /**
     *
     * @ORM\Column(name="cedula", type="integer")
     */
    protected $cedula;
    
    /**
     * @ORM\Column(name="nombreCompleto", type="string", length=100)
     */
    protected $nombreCompleto;

    /**
     * @ORM\Column(name="edad", type="integer")
     */
    protected $edad;
    
    /**
     * @ORM\Column(name="sexo", type="string", length=1)
     */
    protected $sexo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreado", type="datetime")
     */
    protected $fechaCreado;

    /**
     * @var \Text 
     * @ORM\Column(name="direccion", type="text")
     */
    protected $direccion;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaModificado", type="datetime", nullable=true)
     */
    protected $fechaModificado;

    public function getCedula() {
        return $this->cedula;
    }

    public function setCedula($cedula) {
        $this->cedula = $cedula;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }

    public function getEdad() {
        return $this->edad;
    }

    public function setEdad($edad) {
        $this->edad = $edad;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }
    
    /**
     * Set fechaCreado
     *
     * @ORM\PrePersist
     */
    public function setFechaCreado()
    {
        $this->fechaCreado = new \DateTime();
    
        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set fechaModificado
     *
     * @ORM\PreUpdate
     */
    public function setFechaModificado()
    {
        $this->fechaModificado = new \DateTime();
    
        return $this;
    }

    /**
     * Get fechaModificado
     *
     * @return \DateTime 
     */
    public function getFechaModificado()
    {
        return $this->fechaModificado;
    }
    
    public function getDireccion() {
       return $this->direccion;
    }

    public function setDireccion($direccion) {
       $this->direccion = $direccion;
    }
}