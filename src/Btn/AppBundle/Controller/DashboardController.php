<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * User controller.
 *
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/dev", name="dev")
     */
    public function devAction(Request $request)
    {
        //get manager
        $manager = $this->container->get('btn.lead_manager');

        //set phrase
        $phrase = '';

        //set custom condition
        $conditions = array(
            $manager->getQueryBuilder()->expr()->like('l.name', $manager->getQueryBuilder()->expr()->literal('%' . $phrase . '%'))
        );

        $manager->setCustomConditions($conditions);

        //paginate 2 items per page
        $manager->paginate(2);

        //we have a sliding pagination here with iterator interface
        $paginator = $manager->getPagination();

        echo "======<br/>";
        foreach ($paginator as $lead) {
            echo $lead->getName() . "<br/>";
        }
        echo "======<br/>";

        echo "Some handy methods: <br/><br/>";


        echo "getCurrentPageNumber: " . $paginator->getCurrentPageNumber() . "<br/>";
        echo "getTotalItemCount: " . $paginator->getTotalItemCount() . "<br/>";
        echo "getItemNumberPerPage: " . $paginator->getItemNumberPerPage() . "<br/>";

        echo "======<br/>";

        exit;

        return array();
        // var_dump($leads);
    }
}