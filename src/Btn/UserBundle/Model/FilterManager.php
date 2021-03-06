<?php

namespace Btn\UserBundle\Model;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Btn\AppBundle\Model\Manager;

/**
 * Filter Manager
 *
 **/
class FilterManager extends Manager
{

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param Paginator $paginator
     * @param Request $request
     * @param Twig_Enviroment $twig
     * @param FormFactory $formFactory
     */
    public function __construct(EntityManager $em, Paginator $paginator, Request $request, \Twig_Environment $twig, $formFactory)
    {
        parent::__construct($em, $paginator, $request, $twig, $formFactory);

        $this->repo = $this->em->getRepository('BtnUserBundle:User');
    }
}