<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class BankFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accountNb', null, array('label' => 'profile.account_nb'))
        ;

    }

    public function getName()
    {
        return 'btn_user_bank';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btn\UserBundle\Entity\User',
        );
    }
}