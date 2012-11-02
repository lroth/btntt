<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;


class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',  null, array('label'  => 'user.username'))
            ->add('email',     null, array('label'  => 'user.email'))
        ;
    }

    public function getName()
    {
        return 'btn_user';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'        => 'Btn\UserBundle\Entity\User'
        );
    }
}