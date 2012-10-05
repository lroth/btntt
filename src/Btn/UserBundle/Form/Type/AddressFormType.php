<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street',   null, array('label'  => 'profile.street'))
            ->add('city',     null, array('label'  => 'profile.city'))
            ->add('postcode', null, array('label'  => 'profile.postcode'))
            ->add('region', 'entity', array(
                'label' => 'profile.region',
                'multiple' => false,
                'class' => 'Mi\ControlBundle\Entity\Region',
                'empty_value' => '-- Wszystkie --',
                ))
        ;

    }

    public function getName()
    {
        return 'btn_user_address';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btn\UserBundle\Entity\User',
        );
    }
}