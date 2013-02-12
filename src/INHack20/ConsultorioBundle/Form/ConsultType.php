<?php

namespace INHack20\ConsultorioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultType  extends AbstractType{
    
    private $option;

    public function __construct($option){
        $this->option = $option;
    }


    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('busqueda','choice',array(
                'label' => 'Busqueda',
                'choices' => $this->option,
                'empty_value' => '.: Seleccione :.',
                'required' => false,
            ))
            ->add('criterio',null,array(
                'label' => 'Descripción',
                'required' => false,
            ))
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