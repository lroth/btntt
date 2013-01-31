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
class RestController extends BaseController {

	/**
     * @Route("/{resourceName}/{resourceId}", name="actionDelete")
     * @Method({"DELETE"})
     */
	public function deleteAction($resourceName, $resourceId)
	{
		die('Delete ' . $resourceName);
	}

	/**
     * @Route("/{resourceName}/", name="actionGetAll")
     * @Method({"GET"})
     */
	public function getAllAction($resourceName)
	{
		die('Get all from ' .$resourceName);
	}

	/**
     * @Route("/{resourceName}/{id}", name="actionGet")
     * @Method({"GET"})
     */
	public function getAction($resourceName, $id)
	{
		die('Get from ' .$resourceName . ' with id = ' . $id);
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
     * @Route("/{resourceName}/", name="actionAdd")
     * @Method({"POST"})
     */
	public function addAction($resourceName)
	{
		die('Add to ' . $resourceName);
	}
}

?>