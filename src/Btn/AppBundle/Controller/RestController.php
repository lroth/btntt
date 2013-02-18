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
     * @Route("/{resourceName}/{id}/", name="actionGet")
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

	private function getRestRequest()
	{
		return ((array) json_decode(
			$this->getRequest()->getContent()
		));
	}

	private function validateRequest($resourceName, $id = null)
	{
		$resourceObjs 	= $this->getResourceObjects($resourceName, $id);

		$form 			= $resourceObjs['form'];
		$entity 		= $resourceObjs['entity'];

		$requestObj = $this->getRequest();
		$requestObj->request->set($form->getName(), $this->getRestRequest());

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
     * @Route("/{resourceName}/{id}", name="actionOperateOn", defaults={"id" = null})
     * @Method({"POST", "PUT", "PATCH"})
     */
	public function operateAction($resourceName, $id = null)
	{
		/* grab request, preserve resources, validate data */
		$validation = $this->validateRequest($resourceName, $id);	

		/* if no entity, $id was wrong, or entity doesn't exists */
		if($validation['entity'] == null) { 
			return $this->getRestResponse(
						array('message' => 'No ' . $resourceName . ' with id ' . $id), 400); 
		}

		/* validation goes wrong, return errors */
		if(!$validation['isValid']) { return $this->getRestResponse($validation, 400); }
		
		/* do some custom actions, like setting current user */
		$this->doCustomActions($validation['entity']);

		/* if id == null, */
		if($id == null) { $this->manager->persist($validation['entity']); }
		$this->manager->flush();

		return $this->getRestResponse($validation['entity']);
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