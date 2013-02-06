<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Btn\AppBundle\Controller\Controller as BaseController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
*	@Route("/api")
*/
class ApiController extends BaseController {

	public function preExecute()
	{
		$this->manager 		= $this->getDoctrine()->getManager();
		$this->serializer 	= $this->container->get('serializer');
		$this->translator   = $this->get('translator');
	}

	/**
     * @Route("/get/form/{resourceName}/", name="actionGetForm")
     * @Method({"GET"})
     */
	public function getFormAction($resourceName) 
	{
		$modelName		= $this->getResource('entity', $resourceName);
		$model 			= new $modelName();

		return new Response(
			$this->serializer->serialize(
				array( 'form' => $this->createJsonForm($model) ), 
				'json'
			)
		);
	}

	/**
     * @Route("/get/token/", name="actionGetToken")
     * @Method({"GET"})
     */
	public function getTokenAction() 
	{
		$csrf 	= $this->get('form.csrf_provider');
		$token 	= $csrf->generateCsrfToken('');

		return new Response($this->serializer->serialize(array('token' => $token), 'json'));
	}
}

?>