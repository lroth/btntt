<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Btn\AppBundle\Entity\Time;
use Btn\AppBundle\Form\TimeType;
use Btn\AppBundle\Form\TimeFilterType;

class ReportController extends BaseController
{
    public function preExecute()
    {
       $this->getRequest()->request->set('control_nav', 'report');
    }

    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        //get time reports for current user
        $manager = $this->container->get('btn.time_manager')
            ->setNs('reports')
            ->createForm(new TimeFilterType())
            ->setQueryMethod('getReportQuery')
            ->enablePageSession()
            ->filter()
            ->paginate()
        ;

        return array(
            'pagination' => $manager->getPagination(),
            'form'       => $manager->getForm()->createView()
        );
    }
}
