<?php

namespace INHack20\ConsultorioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use INHack20\ConsultorioBundle\Model\Persona;

/**
 * Medico
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Medico extends Persona
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="turno", type="string", length=10)
     */
    private $turno;

    /**
     * @var string
     *
     * @ORM\Column(name="especialidad", type="string", length=50)
     */
    private $especialidad;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=30)
     */
    private $telefono;
    
    /**
     * @var Diario
     * @ORM\OneToMany(targetEntity="Diario",mappedBy="medico")
     */
    protected $diarios;

    public function __construct() {
        $this->diarios = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set turno
     *
     * @param integer $turno
     * @return Medico
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;
    
        return $this;
    }

    /**
     * Get turno
     *
     * @return integer 
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set especialidad
     *
     * @param string $especialidad
     * @return Medico
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;
    
        return $this;
    }

    /**
     * Get especialidad
     *
     * @return string 
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Medico
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }
    
    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Diario
     * @ORM\PrePersist
     */
    public function setFechaCreado()
    {
        $this->fechaCreado = new \DateTime();
    
        return $this;
    }

    /**
     * Set fechaModificado
     *
     * @param \DateTime $fechaModificado
     * @return Diario
     * @ORM\PreUpdate
     */
    public function setFechaModificado()
    {
        $this->fechaModificado = new \DateTime();
    
        return $this;
    }
    
    /**
     * Set cedula
     *
     * @param integer $cedula
     * @return Medico
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    
        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     * @return Medico
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;
    
        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string 
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * Set edad
     *
     * @param integer $edad
     * @return Medico
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;
    
        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return Medico
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    
        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
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
     * Get fechaModificado
     *
     * @return \DateTime 
     */
    public function getFechaModificado()
    {
        return $this->fechaModificado;
    }

    /**
     * Add diarios
     *
     * @param \INHack20\ConsultorioBundle\Entity\Diario $diarios
     * @return Medico
     */
    public function addDiario(\INHack20\ConsultorioBundle\Entity\Diario $diarios)
    {
        $this->diarios[] = $diarios;
    
        return $this;
    }

    /**
     * Remove diarios
     *
     * @param \INHack20\ConsultorioBundle\Entity\Diario $diarios
     */
    public function removeDiario(\INHack20\ConsultorioBundle\Entity\Diario $diarios)
    {
        $this->diarios->removeElement($diarios);
    }

    /**
     * Get diarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDiarios()
    {
        return $this->diarios;
    }
    
    public function __toString() {
        return $this->nombreCompleto .'['.$this->especialidad.']';
    }

}