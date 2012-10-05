<?php

namespace Btn\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class InvoiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoiceName',     null, array('label'  => 'profile.invoice_name'))
            ->add('invoiceCity',     null, array('label'  => 'profile.invoice_city'))
            ->add('invoiceStreet',   null, array('label'  => 'profile.invoice_street'))
            ->add('invoicePostcode', null, array('label'  => 'profile.invoice_postcode'))
            ->add('nip',             null, array('label'  => 'profile.nip'))
        ;

    }

    public function getName()
    {
        return 'btn_user_invoice';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Btn\UserBundle\Entity\User',
        );
    }
}