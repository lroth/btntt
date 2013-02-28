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
}