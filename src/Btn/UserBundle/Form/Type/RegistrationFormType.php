<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add or remove your custom field
        $builder
            ->remove('username')
            ->add('firstname', 'text', array('label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastname', 'text', array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle'))
            ->add('acceptNewsletter', null, array('label' => 'form.newsletter', 'translation_domain' => 'FOSUserBundle'))
            ->add('acceptTerms',  null, array('label' => 'form.terms', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getName()
    {
        return 'btn_user_registration';
    }
}