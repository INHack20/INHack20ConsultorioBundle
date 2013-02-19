<?php

namespace INHack20\ConsultorioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medico','entity',array(
                'label' => 'Médico',
                'class' => 'INHack20\\ConsultorioBundle\\Entity\\Medico',
                //'property' => 'nombre',
                'empty_value' => '.: Seleccione :.'
            ))
            ->add('municipio')
            ->add('asic')
            ->add('consultorio')
            //->add('fechaCreado')
            //->add('fechaModificado')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'INHack20\ConsultorioBundle\Entity\Diario'
        ));
    }

    public function getName()
    {
        return 'inhack20_consultoriobundle_diariotype';
    }
}
