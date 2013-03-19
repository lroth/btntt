<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estimationTime', 'number')
            ->add('budget', 'text')
            ->add('content', 'textarea')
            ->add('title', 'text')
            ->add('projectStartTime', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('projectEndTime', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('enquiryDeadline', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('status', 'text')
            ->add('lead', 'entity', array(
                'class'    => 'BtnAppBundle:Lead',
                'property' => 'name',
//                'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('u');
//                }
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Btn\AppBundle\Entity\Enquiry',
            'csrf_protection' => FALSE
        ));
    }

    public function getName()
    {
        return 'btn_appbundle_enquirytype';
    }
}
