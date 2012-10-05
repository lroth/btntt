<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',  null, array('label'  => 'profile.firstname'))
            ->add('lastname',   null, array('label'  => 'profile.lastname'))
            ->add('screenname', null, array('label'  => 'profile.screenname'))
            ->add('phone',      null, array('label'  => 'profile.phone'))
        ;

    }

    public function getName()
    {
        return 'btn_user_profile';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btn\UserBundle\Entity\User',
        );
    }
}