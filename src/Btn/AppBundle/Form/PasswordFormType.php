<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('new', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' =>  array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
        ));
    }

    public function getName()
    {
        return 'btn_user_change_password';
    }
}