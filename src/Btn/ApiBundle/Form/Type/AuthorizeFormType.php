<?php
namespace Btn\ApiBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class AuthorizeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('allowAccess', 'checkbox', array(
            'label' => 'Allow access',
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Btn\ApiBundle\Form\Model\Authorize');
    }

    public function getName()
    {
        return 'btn_oauth_server_authorize';
    }

}