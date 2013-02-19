<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Btn\AppBundle\Form\DataTransformer\StringToDateTimeTransformer;

class LeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateTransformer = new StringToDateTimeTransformer();

        $builder
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('description', 'textarea')
            ->add('alert', 'date')->prependNormTransformer($dateTransformer)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Btn\AppBundle\Entity\Lead',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'btn_appbundle_leadtype';
    }
}
