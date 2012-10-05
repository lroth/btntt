<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class MessagesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('messageFromMe',     null, array('label'  => 'profile.message_from_me'))
            ->add('messageToOwner', null, array('label'  => 'profile.message_to_owner'))
        ;
    }

    public function getName()
    {
        return 'btn_user_messages';
    }
}