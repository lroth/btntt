<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Btn\AppBundle\Form\DataTransformer\StringToDateTimeTransformer;
use Btn\AppBundle\Form\DataTransformer\ProjectToNameTransformer;
use Doctrine\ORM\EntityManager;

class TimeType extends AbstractType
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer     = new ProjectToNameTransformer($this->em);
        $dateTransformer = new StringToDateTimeTransformer();

        $builder
            ->add(
                $builder->create('createdAt', 'text', array(
                    'required' => false,
                    'data' => 'today',
                    'attr' => array(
                        'class'       => 'timeContainer',
                    )
                ))
                ->prependNormTransformer($dateTransformer)
            )
            ->add('time', 'text', array('attr' =>
                array(
                    'class'       => 'input-small',
                    'placeholder' => 'Time',
                )
            ))
            ->add('description', 'textarea', array('attr' =>
                array(
                    'class'       => 'input-xxlarge',
                    'placeholder' => 'Description'
                )
            ))
            ->add(
                $builder->create('project', 'text', array('attr' =>
                array(
                    'class'       => 'input-small autocomplete',
                    'placeholder' => 'Project',
                    'data-widget' => 'autocomplete'
                )))
                ->prependNormTransformer($transformer)
            )
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
