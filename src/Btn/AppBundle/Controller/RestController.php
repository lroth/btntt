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

	public function preExecute()
	{
		$this->manager 		= $this->getDoctrine()->getManager();
		$this->serializer 	= $this->container->get('serializer');
		$this->translator  = $this->get('translator');
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

	/**
     * @Route("/{resourceName}", name="actionAdd")
     * @Method({"POST"})
     */
	public function addAction($resourceName)
	{
		$modelName 	= $this->getResource('entity', $resourceName);
		$formName 	= $this->getResource('form', $resourceName);

		$requestJson 	= $this->getRequest()->getContent();
		$request 		= (array) json_decode($requestJson);

		$model 		= new $modelName();
		$form 		= $this->createForm(new $formName(), $model);

		$this->getRequest()->request->set($form->getName(), $request);
		$form->bind($this->getRequest());

		if(!$form->isValid()) {
			$errors = $this->getFormErrors($form);
			return new Response($this->serializer->serialize(array('errors' => $errors), 'json'), 400);
		}
		else {
			$em = $this->getManager();
			$em->persist($model);
			$em->flush();

			return new Response($this->serializer->serialize(array('message' => $this->translator->trans(ucfirst($resourceName) . ' saved!')), 'json'));
		}
	}

	public function defaultAction($resourceName)
	{
		//@lukasz - throw exception here 
		die('no api method');
	}
}

?>