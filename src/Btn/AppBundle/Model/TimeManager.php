<?php

namespace Btn\AppBundle\Model;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Btn\BaseBundle\Model\Manager;

/**
 * Time manager
 *
 **/
class TimeManager extends Manager
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
    public function __construct(EntityManager $em, Paginator $paginator, \Twig_Environment $twig, $formFactory)
    {
        parent::__construct($em, $paginator, $twig, $formFactory);

        $this->repo = $this->em->getRepository('BtnAppBundle:Time');
    }

    public function getLastActivity($user, $days = 7)
    {
        //get user results for provided period
        $results = $this->repo->getUserReportForLastDays($user, $days);

        //convert results to array
        $data = array();
        foreach ($results as $item) {
            if (isset($data[$item->getCreatedAt()->format('d-m-Y')][$item->getProject()->getName()])) {
                $data[$item->getCreatedAt()->format('d-m-Y')][$item->getProject()->getName()] = $data[$item->getCreatedAt()->format('d-m-Y')][$item->getProject()->getName()] + $item->getTime();
            } else {
                $data[$item->getCreatedAt()->format('d-m-Y')][$item->getProject()->getName()] = $item->getTime();
            }
        }

        //fullfill empty days
        $empties = array();
        for($i = $days-1; $i >= 1; $i--) {
            $empties[date('d-m-Y', strtotime('-'. $i .' days'))] = array();
        }

        return array_merge($empties, $data);
    }
}