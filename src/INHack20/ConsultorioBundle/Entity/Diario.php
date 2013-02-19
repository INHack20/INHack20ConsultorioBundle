<?php

namespace INHack20\ConsultorioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Diario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="INHack20\ConsultorioBundle\Repository\DiarioRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Diario
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
     * @ORM\Column(name="municipio", type="string", length=100)
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="asic", type="string", length=80)
     */
    private $asic;

    /**
     * @var string
     *
     * @ORM\Column(name="consultorio", type="string", length=80)
     */
    private $consultorio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreado", type="datetime")
     */
    private $fechaCreado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaModificado", type="datetime", nullable=true)
     */
    private $fechaModificado;
    
    /**
     * @var Medico
     *
     * @ORM\ManyToOne(targetEntity="Medico",inversedBy="diarios")
     * @ORM\JoinColumn(name="medico_id",referencedColumnName="id")
     */
    protected $medico;
    
    /**
     * @ORM\OneToMany(targetEntity="Paciente",mappedBy="diario")
     * @var ArrayCollection(Pacientes)
     */
    protected $pacientes;
    
    public function __construct(){
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
     * Set municipio
     *
     * @param string $municipio
     * @return Diario
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    
        return $this;
    }

    /**
     * Get municipio
     *
     * @return string 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set asic
     *
     * @param string $asic
     * @return Diario
     */
    public function setAsic($asic)
    {
        $this->asic = $asic;
    
        return $this;
    }

    /**
     * Get asic
     *
     * @return string 
     */
    public function getAsic()
    {
        return $this->asic;
    }

    /**
     * Set consultorio
     *
     * @param string $consultorio
     * @return Diario
     */
    public function setConsultorio($consultorio)
    {
        $this->consultorio = $consultorio;
    
        return $this;
    }

    /**
     * Get consultorio
     *
     * @return string 
     */
    public function getConsultorio()
    {
        return $this->consultorio;
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
     * Get fechaModificado
     *
     * @return \DateTime 
     */
    public function getFechaModificado()
    {
        return $this->fechaModificado;
    }
        
    /**
     * Add pacientes
     *
     * @param \INHack20\ConsultorioBundle\Entity\Paciente $pacientes
     * @return Diario
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
    
    public function getDetalle()
    {
        return $this->getMedico() . '['.$this->getConsultorio().']';
    }

    /**
     * Set medico
     *
     * @param \INHack20\ConsultorioBundle\Entity\Medico $medico
     * @return Diario
     */
    public function setMedico(\INHack20\ConsultorioBundle\Entity\Medico $medico = null)
    {
        $this->medico = $medico;
    
        return $this;
    }

    /**
     * Get medico
     *
     * @return \INHack20\ConsultorioBundle\Entity\Medico 
     */
    public function getMedico()
    {
        return $this->medico;
    }
}