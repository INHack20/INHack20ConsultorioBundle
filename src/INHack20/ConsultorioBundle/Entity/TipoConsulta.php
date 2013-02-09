<?php

namespace INHack20\ConsultorioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TipoConsulta
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class TipoConsulta
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
     * @var string
     *
     * @ORM\Column(name="acronimo", type="string", length=5)
     */
    private $acronimo;

    /**
     * @var string
     *
     * @ORM\Column(name="significado", type="string", length=30)
     */
    private $significado;

    /**
     * @var ArrayCollection(Paciente)
     * 
     * @ORM\OneToMany(targetEntity="Paciente",mappedBy="tipoConsulta")
     */
    protected $pacientes;
    
    /**
     * @var type 
     * 
     * @ORM\Column(name="fechaCreado", type="datetime")
     */
    private $fechaCreado;
    
    public function __contruct(){
        $this->pacientes = new ArrayCollection();
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
     * Set acronimo
     *
     * @param string $acronimo
     * @return TipoConsulta
     */
    public function setAcronimo($acronimo)
    {
        $this->acronimo = $acronimo;
    
        return $this;
    }

    /**
     * Get acronimo
     *
     * @return string 
     */
    public function getAcronimo()
    {
        return $this->acronimo;
    }

    /**
     * Set significado
     *
     * @param string $significado
     * @return TipoConsulta
     */
    public function setSignificado($significado)
    {
        $this->significado = $significado;
    
        return $this;
    }

    /**
     * Get significado
     *
     * @return string 
     */
    public function getSignificado()
    {
        return $this->significado;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pacientes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add pacientes
     *
     * @param \INHack20\ConsultorioBundle\Entity\Paciente $pacientes
     * @return TipoConsulta
     */
    public function addPaciente(\INHack20\ConsultorioBundle\Entity\Paciente $pacientes)
    {
        $this->pacientes[] = $pacientes;
    
        return $this;
    }

    /**
     * Remove pacientes
     *
     * @param \INHack20\ConsultorioBundle\Entity\Paciente $pacientes
     */
    public function removePaciente(\INHack20\ConsultorioBundle\Entity\Paciente $pacientes)
    {
        $this->pacientes->removeElement($pacientes);
    }

    /**
     * Get pacientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPacientes()
    {
        return $this->pacientes;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return TipoConsulta
     * @ORM\PrePersist
     */
    public function setCreado()
    {
        $this->fechaCreado = new \DateTime();
    
        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return TipoConsulta
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;
    
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
}