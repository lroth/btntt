<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Btn\AppBundle\Controller\Controller as BaseController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
*	@Route("/rest")
*/
class RestController extends BaseController {
	private $resources = array();

	public function preExecute($event, $resolver)
	{
		$this->manager 		= $this->getDoctrine()->getManager();
		$this->serializer 	= $this->container->get('serializer');
		$this->translator  	= $this->get('translator');

		if(is_string($this->getCurrentUser())) {
			$request = $event->getRequest();

			$request->attributes->set('_controller', 'BtnAppBundle:Api:exception');
			$request->attributes->set('_route', 'actionException');
			$request->attributes->set('type', 'not_authenticated');

			$event->setController($resolver->getController($request));
		}
	}

	public function postExecute()
	{
		//@lukasz
	}

	/* get repository by resource name */
	private function getRepoByResource($name)
	{
		return $this->manager->getRepository('BtnAppBundle:' . ucfirst($name));
	}

	/**
     * @Route("/{resourceName}/{resourceId}", name="actionDelete")
     * @Method({"DELETE"})
     */
	public function deleteAction($resourceName, $resourceId)
	{
		$this->manager->remove($this->getRepoByResource($resourceName)->find($resourceId));
		$this->manager->flush();

		return new Response($this->serializer->serialize(array(
			'message' => $this->translator->trans(ucfirst($resourceName) . ' with id ' . $resourceId . ' deleted successfully!')
		), 'json'));
	}

	/**
     * @Route("/{resourceName}/", name="actionGetAll")
     * @Method({"GET"})
     */
	public function getAllAction($resourceName)
	{
        return new Response(
        	$this->serializer->serialize(
        		$this->getRepoByResource($resourceName)->findAll(), 'json'
    		)
    	);
	}

	/**
     * @Route("/{resourceName}/{id}", name="actionGet")
     * @Method({"GET"})
     */
	public function getAction($resourceName, $id)
	{
		return new Response(
        	$this->serializer->serialize(
        		$this->getRepoByResource($resourceName)->find($id), 'json'
    		)
    	);
	}

	/**
     * @Route("/{resourceName}/{id}", name="actionEdit")
     * @Method({"PUT"})
     */
	public function editAction($resourceName, $id)
	{
		die('Edit ' .$resourceName . ' with id = ' . $id);
	}

	private function getRestRequest()
	{
		return ((array) json_decode(
			$this->getRequest()->getContent()
		));
	}

	private function validateRequest($resourceName)
	{
		$resourceObjs 	= $this->getResourceObjects($resourceName);

		$form 			= $resourceObjs['form'];
		$entity 		= $resourceObjs['entity'];

		$requestObj = $this->getRequest();
		$requestObj->request->set( $form->getName(), $this->getRestRequest());

		$form->bind($requestObj);

		$validationArr = array(
			'isValid' 	=> $form->isValid(),
			'entity'	=> $entity
		);
		
		$validationArr['errors'] = 
			(!$validationArr['isValid']) ? $this->getFormErrors($form) : null;

		return $validationArr;
	}

	/**
     * @Route("/{resourceName}", name="actionAdd")
     * @Method({"POST"})
     */
	public function addAction($resourceName)
	{
		$validation = $this->validateRequest($resourceName);	

		if(!$validation['isValid']) {
			return $this->getRestResponse($validation, 400);
		}
		else {
			$entity = $validation['entity'];
			$this->doCustomActions($entity);

			$this->manager->persist($entity);
			$this->manager->flush();

			return $this->getRestResponse($entity);
		}
	}

	public function defaultAction($resourceName)
	{
		//@lukasz - throw exception here 
		die('no api method');
	}

	/* kind of magic - move to repo */
	private function doCustomActions(&$entity)
	{
		foreach ($entity->customCallbacks as $action) {
			if(method_exists($this, $action)) {
				call_user_func_array(array($this, $action), array(&$entity));
			}
		}
	}

	/* move this to service or something */
	private function setCurrentUser(&$entity)
	{
		$entity->setUser($this->getCurrentUser());
	}
}

?>