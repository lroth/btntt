<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Btn\AppBundle\Validator\Unique;

class EmailFormType extends AbstractType
{
    private $user = false;

    public function __construct($user) {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array(
                      'label' => 'form.email',
                      'translation_domain' => 'FOSUserBundle',
                      'constraints' => array(
                            new Unique(array(
                                'repository' => 'BtnUserBundle:User',
                                'field' => 'email',
                                'object' => $this->user
                            )),
                            new Email(array('message' => 'Wrong email format'))
                        )))
        ;

    }

    public function getName()
    {
        return 'btn_user_change_email';
    }
}