<?php

namespace INHack20\ConsultorioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultType  extends AbstractType{
    
    private $option;
    private $tipoConsulta;
    private $medico;
    public function __construct($option, $tipoConsulta = false,$medico = false){
        $this->option = $option;
        $this->tipoConsulta = $tipoConsulta;
        $this->medico = $medico;
    }


    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('busqueda','choice',array(
                'label' => 'Busqueda',
                'choices' => $this->option,
                'empty_value' => '',
                'required' => false,
            ))
            ->add('criterio',null,array(
                'label' => 'Descripción',
                'required' => false,
            ));
        if($this->medico){
           $builder
               ->add('medico','entity',array(
                   'label' => 'Médico',
                   'class' => 'INHack20\\ConsultorioBundle\\Entity\\Medico',
                   //'property' => 'nombre',
                   'empty_value' => '',
                   'required' => false,
               ));
        }
        if($this->tipoConsulta){
            $builder->add('tipoconsulta','entity',array(
                'label' => 'Tipo de Consulta',
                'class' => 'INHack20\\ConsultorioBundle\\Entity\\TipoConsulta',
                'property' => 'significado',
                'empty_value' => '',
                'required' => false,
            ));
        }
        $builder
            ->add('fechaDesde','datetime',array(
                'label' => 'Desde',
                'required' => false,
                'widget' => 'single_text',
            ))
            ->add('fechaHasta','datetime',array(
                'label' => 'Hasta',
                'required' => false,
                'widget' => 'single_text',
            ));
    }

    public function getName() {
        return 'inhack20_consultoriobundle_consultype';
    }
}

?>