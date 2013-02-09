<?php

namespace INHack20\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('PlainPassword', 'repeated',
                array(
                    'type' => 'password',
                    'first_name' => 'Nueva_contrasena',
                    'second_name' => 'Verificacion'));
    }

    public function getName()
    {
        return 'fos_user_change_password';
    }
}
