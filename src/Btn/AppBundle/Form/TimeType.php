<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time', 'text', array('attr' =>
                array(
                    'class'       => 'input-small',
                    'placeholder' => 'Time',
                )
            ))
            ->add('description', 'text', array('attr' =>
                array(
                    'class'       => 'input-xxlarge',
                    'placeholder' => 'Description'
                )
            ))
            ->add('project', 'text', array('attr' =>
                array(
                    'class'       => 'input-small',
                    'placeholder' => 'Project'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Btn\AppBundle\Entity\Time'
        ));
    }

    public function getName()
    {
        return 'btn_appbundle_timetype';
    }
}
