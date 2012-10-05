<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AllegroFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allegroName',     null, array('label'  => 'profile.allegro_name'))
            ->add('allegroPassword', null, array('label'  => 'profile.allegro_password'))
            ->add('allegroKey',      null, array('label'  => 'profile.allegro_key'))
        ;
    }

    public function getName()
    {
        return 'btn_user_allegro';
    }
}