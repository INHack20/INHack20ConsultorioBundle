<?php

namespace INHack20\ConsultorioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreCompleto',null,array(
                'label' => 'Nombre Completo',
            ))
            ->add('cedula')
            ->add('edad')
            ->add('sexo','choice',array(
                'choices' => array('m' => 'Masculino', 'f' => 'Femenino'),
                'empty_value' => '.: Seleccione :.',
            ))
            ->add('tipoConsulta','entity',array(
                'class' => 'INHack20\\ConsultorioBundle\\Entity\\TipoConsulta',
                'property' => 'significado',
                'label' => 'Tipo de Consulta',
                'empty_value' => '.: Seleccione :.',
            ))
            ->add('diagnostico')
            ->add('tratamiento')
            //->add('fechaCreado')
            //->add('fechaModificado')
            ->add('diario','entity',array(
                'class' => 'INHack20\\ConsultorioBundle\\Entity\\Diario',
                'property' => 'detalle',
                'label' => 'Hoja',
                'empty_value' => '.: Seleccione :.',
                'query_builder' => function (EntityRepository $er){
                    $fecha = new \DateTime();
                    $qb = $er->createQueryBuilder('d');
                    //$qb->from('INHack20ConsultorioBundle:Diario', 'd');
                    $qb->where($qb->expr()->like('d.fechaCreado', "'".$fecha->format("Y-m-d")."%'"));
                    return $qb;
                 }
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'INHack20\ConsultorioBundle\Entity\Paciente'
        ));
    }

    public function getName()
    {
        return 'inhack20_consultoriobundle_pacientetype';
    }
}
