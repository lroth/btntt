<?php

namespace Btn\AppBundle\Controller;

use Btn\AppBundle\Entity\Lead;
use Btn\AppBundle\Entity\Enquiry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * User controller.
 *
 */
class LeadController extends BaseController
{
    /**
     * @Route("/lead/", name="leadList")
     * @Method({"GET"})
     */
    public function indexAction()
    {
    	
        return array();
    }

    /**
     * @Route("/lead/{id}", name="leadShow")
     * @Method({"GET"})
     */
    public function Action()
    {
        
        return array();
    }

    /**
     * @Route("/lead", name="leadAdd")
     * @Method({"POST"})
     */
    public function addAction()
    {
        
        return array();
    }

    /**
     * @Route("/lead", name="leadUpdate")
     * @Method({"PUT"})
     */
    public function updateAction()
    {
        return array();
    }

    /**
     * @Route("/lead", name="leadDelete")
     * @Method({"DELETE"})
     */
    public function deleteAction()
    {
        return array();
    }    
}