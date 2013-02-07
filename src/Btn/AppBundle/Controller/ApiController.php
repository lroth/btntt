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
		$formName		= $this->getResource('form', $resourceName);

		$model 			= new $modelName();
		$form 			= $this->createForm(new $formName());

		return new Response(
			$this->serializer->serialize(
				array( 'form' => $this->createJsonForm($form, $model) ), 'json'
			)
		);
	}

	/**
     * @Route("/get/token/", name="actionGetToken")
     */
	public function getTokenAction() 
	{
		$csrf 	= $this->get('form.csrf_provider');
		$token 	= $csrf->generateCsrfToken('');

		return new Response($this->serializer->serialize(array('token' => $token), 'json'));
	}

	/**
     * @Route("/get/init-data/", name="actionInitApp")
     * @Method({"GET"})
     */
	public function appInitAction()
	{
		$user = $this->getCurrentUser();
        $base = $this->container->get('router')->getContext()->getBaseUrl();

        $response = array(
            'user' 		=> $this->getUserInitData($user),
            'baseUrl' 	=> $base
        );

        return new Response($this->serializer->serialize($response, 'json'));
	}

	private function getUserInitData($user)
	{
		$userData = array();

        if(is_string($user)) { 
        	$userData['auth'] = false; 
        } else {
        	$userData['auth'] 		= true;
        	$userData['username'] 	= $user->getUsername();
        	$userData['email']		= $user->getEmail();
        	$userData['id']			= $user->getId();
        	
        }

		return $userData;
	}

	/**
     * @Route("/exception/{type}", name="actionException")
     * @Method({"GET"})
     */
	public function exceptionAction($type)
	{
		$serializer = $this->container->get('serializer');

		/* move to rest.yml */
		$map = array(
			'not_authenticated' => array(
				'message' 	=> 'Not authenticated',
				'code'		=> 401
			)
		);

		$response = array('message' => $map[$type]['message']);
		
		return new Response(
			$serializer->serialize($response, 'json'), 
			$map[$type]['code']
		);
	}
}

?>