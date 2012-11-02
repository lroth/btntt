<?php

namespace Btn\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;
use Btn\AppBundle\Util\Form;

class UserFilterType extends AbstractType
{

    protected $filters = array(
                'keyword'  => 'e.email'
              );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', 'text')
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

    public function getKeyword($keyword, $expr)
    {
        return $expr->orx(
                $expr->like('e.username', $expr->literal('%'.$keyword.'%')),
                $expr->like('e.email',    $expr->literal('%'.$keyword.'%'))
               );
    }

    public function getExpr($binded_data, $expr)
    {
        return Form::getExpr($binded_data, $expr, $this->filters, $this);
    }

}
