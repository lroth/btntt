<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;
use Btn\AppBundle\Util\Form;

class TimeFilterType extends AbstractType
{

    protected $filters = array(
                'user'    => 'u.email',
                'project' => 't.project'
              );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', array(
                'label' => 'Użytkownik',
                'class' => 'Btn\UserBundle\Entity\User',
                'empty_value' => 'wszyscy',
                'required' => false,
            ))
            ->add('project', 'entity', array(
                'label' => 'Projekt',
                'class' => 'Btn\AppBundle\Entity\Project',
                'empty_value' => 'wszystkie',
                'required' => false,
            ))
            //filter time from

            //filter time to
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false
        );
    }

    public function getName()
    {
        return 'filter';
    }

    public function getProject($project, $expr)
    {
        return $expr->eq('p.name', $expr->literal($project));
    }

    public function getUser($user, $expr)
    {
        return $expr->eq('u.email', $expr->literal($user));
    }

    public function getExpr($binded_data, $expr)
    {
        return Form::getExpr($binded_data, $expr, $this->filters, $this);
    }

}
