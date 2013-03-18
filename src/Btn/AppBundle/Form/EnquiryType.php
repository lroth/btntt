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
            ->add('estimationTime', '')
            ->add('budget', 'text')
            ->add('content')
            ->add('title')
            ->add('projectStartTime')
            ->add('projectEndTime')
            ->add('enquiryDeadline')
            ->add('status')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('lead');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Btn\AppBundle\Entity\Enquiry'
        ));
    }

    public function getName()
    {
        return 'btn_appbundle_enquirytype';
    }
}
