<?php

namespace Btn\AppBundle\Controller;

use Btn\AppBundle\Entity\Lead;
use Btn\AppBundle\Entity\Enquiry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * User controller.
 *
 */
class TestController extends BaseController
{
    /**
     * @Route("/test", name="test")
     */
    public function indexAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	/* add lead */
        $user = $em->getRepository('BtnUserBundle:User')->find(1);

    	$lead = new Lead();
    	$lead->setName('Dropr');
    	$lead->setEmail('cypherq@gmail.com');
    	$lead->setDescription('Project for Dropr.com');
    	$lead->setAlert(new \DateTime());
    	$lead->setUpdatedAt(new \DateTime());
    	$lead->setUser($user);

    	$em->persist($lead);
    	$em->flush();
    	/**/

    	/* update lead *
    	$lead = $em->getRepository('BtnAppBundle:Lead')->find(1);
		$lead->setName('Dropr.com');
		$em->flush();
		/**/

		/* add Enquiry connected with lead.id = 1*
		$lead = $em->getRepository('BtnAppBundle:Lead')->find(1);

		$enq = new Enquiry();
		$enq->setEstimationTime(10);
		$enq->setBudget('20000 zl');
		$enq->setContent('Blablalbalbalba');
		$enq->setTitle('Zapytanie o taski dla Benego');
		$enq->setProjectStartTime(new \DateTime());
		$enq->setProjectEndTime(new \DateTime());
		$enq->setEnquiryDeadline(new \DateTime());
		$enq->setUpdatedAt(new \DateTime());
		$enq->setStatus('toSent');
		$enq->setLead($lead);

		$em->persist($enq);
    	$em->flush();
    	/**/

    	
    	ldd($request);
        return array();
    }
}