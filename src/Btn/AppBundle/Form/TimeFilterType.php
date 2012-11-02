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
                'user'     => 'u.username',
                'project'  => 't.project',
                'timeFrom' => 't.createdAt',
                'timeTo'   => 't.createdAt',
              );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', array(
                'label' => 'User',
                'class' => 'Btn\UserBundle\Entity\User',
                'empty_value' => 'All users',
                'required' => false,
            ))
            ->add('project', 'entity', array(
                'label' => 'Project',
                'class' => 'Btn\AppBundle\Entity\Project',
                'empty_value' => 'All projects',
                'required' => false,
            ))
            //filter time from
            ->add('timeFrom', 'text', array(
                'label' => 'Time from',
                'required' => false,
                'attr' => array('class' => 'input-small')
            ))
            //filter time to
            ->add('timeTo', 'text', array(
                'label' => 'Time to',
                'required' => false,
                'attr' => array('class' => 'input-small')
            ))
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

    public function getTimeFrom($timeFrom, $expr)
    {
        $from = new \DateTime($timeFrom);
        return $expr->gte('t.createdAt', $expr->literal($from->format('Y-m-d H:i:s')));
    }

    public function getTimeTo($timeTo, $expr)
    {
        $to = new \DateTime($timeTo);
        return $expr->lte('t.createdAt', $expr->literal($to->format('Y-m-d H:i:s')));
    }

    public function getUser($user, $expr)
    {
        return $expr->eq('u.username', $expr->literal($user));
    }

    public function getExpr($binded_data, $expr)
    {
        return Form::getExpr($binded_data, $expr, $this->filters, $this);
    }

}
