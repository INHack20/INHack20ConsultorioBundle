<?php

namespace INHack20\ConsultorioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('turno','choice',array(
                'choices' => array('Mañana' => 'Mañana',
                                   'Tarde' => 'Tarde',
                                   'Noche' => 'Noche',
                                    ),
                'empty_value' => '.: Seleccione :.'
            ))
            ->add('especialidad')
            ->add('telefono')
            ->add('cedula')
            ->add('nombreCompleto',null,array(
                'label' => 'Nombre Completo'
            ))
            ->add('edad')
            ->add('sexo','choice',array(
                'choices' => array('M' => 'Masculino',
                                   'F' => 'Femenino'),
                'empty_value' => '.: Seleccione :.'
            ))
            //->add('fechaCreado')
           // ->add('fechaModificado')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'INHack20\ConsultorioBundle\Entity\Medico'
        ));
    }

    public function getName()
    {
        return 'inhack20_consultoriobundle_medicotype';
    }
}
