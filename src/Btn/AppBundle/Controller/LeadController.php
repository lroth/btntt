<?php

namespace Btn\AppBundle\Controller;

use Btn\AppBundle\Entity\Lead;
use Btn\AppBundle\Entity\Enquiry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Btn\AppBundle\Controller\RestController as RestController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * User controller.
 *
 */
class LeadController extends RestController
{
    /**
     * @Route("/lead/", name="leadList")
     * @Method({"GET"})
     */
    // public function indexAction()
    // {
    //     $serializer = $this->container->get('serializer');
    // 	$repository = $this->getDoctrine()->getRepository('BtnAppBundle:Lead');
    //     $leads      = $repository->findAll();

    //     return new Response($serializer->serialize($leads, 'json'));
    // }
}