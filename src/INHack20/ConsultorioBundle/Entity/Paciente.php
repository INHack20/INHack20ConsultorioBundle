<?php

namespace INHack20\ConsultorioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paciente
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="INHack20\ConsultorioBundle\Repository\PacienteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Paciente
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
     * @ORM\Column(name="nombreCompleto", type="string", length=100)
     */
    private $nombreCompleto;

    /**
     * @var integer
     *
     * @ORM\Column(name="edad", type="integer")
     */
    private $edad;
    
    /**
     *
     * @var type 
     * @ORM\Column(name="sexo", type="string", length=5)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostico", type="text")
     */
    private $diagnostico;

    /**
     * @var string
     *
     * @ORM\Column(name="tratamiento", type="text")
     */
    private $tratamiento;

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
     * @ORM\ManyToOne(targetEntity="Diario",inversedBy="pacientes")
     * @ORM\JoinColumn(name="diario_id",referencedColumnName="id")
     * @var Diario 
     */
    protected $diario;
    
    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="TipoConsulta",inversedBy="pacientes")
     * @ORM\JoinColumn(name="tipoConsulta_id",referencedColumnName="id")
     */
    protected $tipoConsulta;

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
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     * @return Paciente
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
     * @return Paciente
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
     * Set diagnostico
     *
     * @param string $diagnostico
     * @return Paciente
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;
    
        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return string 
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set tratamiento
     *
     * @param string $tratamiento
     * @return Paciente
     */
    public function setTratamiento($tratamiento)
    {
        $this->tratamiento = $tratamiento;
    
        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return string 
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Paciente
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
     * @return Paciente
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
     * Set diario
     *
     * @param \INHack20\ConsultorioBundle\Entity\Diario $diario
     * @return Paciente
     */
    public function setDiario(\INHack20\ConsultorioBundle\Entity\Diario $diario = null)
    {
        $this->diario = $diario;
    
        return $this;
    }

    /**
     * Get diario
     *
     * @return \INHack20\ConsultorioBundle\Entity\Diario 
     */
    public function getDiario()
    {
        return $this->diario;
    }

    /**
     * Set tipoConsulta
     *
     * @param \INHack20\ConsultorioBundle\Entity\TipoConsulta $tipoConsulta
     * @return Paciente
     */
    public function setTipoConsulta(\INHack20\ConsultorioBundle\Entity\TipoConsulta $tipoConsulta = null)
    {
        $this->tipoConsulta = $tipoConsulta;
    
        return $this;
    }

    /**
     * Get tipoConsulta
     *
     * @return \INHack20\ConsultorioBundle\Entity\TipoConsulta 
     */
    public function getTipoConsulta()
    {
        return $this->tipoConsulta;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return Paciente
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
}