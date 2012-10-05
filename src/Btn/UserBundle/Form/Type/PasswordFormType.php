<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'constraints' => new UserPassword(),
        ));

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