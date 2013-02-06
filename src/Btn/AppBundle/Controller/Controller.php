<?php

namespace Btn\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    private $namespaces = array(
        'form'      => 'Btn\\AppBundle\\Form\\',
        'entity'    => 'Btn\\AppBundle\\Entity\\'
    );

    /**
     * Set flash message
     *
     * @param string $type
     * @param string $message
     *
     */
    public function setFlash($message = 'success!', $type = 'success')
    {
        $this->getRequest()->getSession()->setFlash($type, $message);
    }


    /**
     * Renders JSON for our MooTools View wrapper.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function renderJson($verdict = 'success', $content = '', $param = '')
    {
        $result = array(
            'verdict' => $verdict,
            'content' => $content,
            'param'   => $param
        );


        return $this->json($result);
    }

    /**
     * Renders array into JSON.
     *
     * @param array    $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function json(array $array, Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent(json_encode($array));
        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

    /**
     * Shortcut to return Doctrine EntityManager service.
     *
     * @param string $name
     *
     * @return EntityManager
     */
    public function getManager($name = null)
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Shortcut to return Doctrine Entity Repository.
     *
     * @param string $repositoryName
     * @param string $managerName
     *
     * @return EntityRepository
     */
    public function getRepository($repositoryName, $managerName = null)
    {
        return $this->getManager($managerName)->getRepository($repositoryName);
    }

    /**
     * Shortcut to find an entity by id
     *
     * @param string $class
     * @param mixed  $id
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntity($class, $id, $managerName = null)
    {
        return $this->getRepository($class, $managerName)->find($id);
    }

    /**
     * Shortcut to find an entity by criteria
     *
     * @param string $class
     * @param array  $criteria    An array of criteria (field => value)
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntityBy($class, array $criteria, $managerName = null)
    {
        return $this->getRepository($class, $managerName)->findOneBy($criteria);
    }

    /**
     * Finds the entity by id or throws a NotFoundHttpException
     *
     * @param string $class
     * @param mixed  $id
     * @param string $managerName
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityOr404($class, $id, $managerName = null)
    {
        $entity = $this->findEntity($class, $id, $managerName);

        if (null === $entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with id "%s" was not found.',
                $class,
                is_array($id) ? implode(' ', $id) : $id
            ));
        }

        return $entity;
    }

    /**
     * Finds the entity matching the specified criteria or throws a NotFoundHttpException
     *
     * @param string $class
     * @param array  $criteria An array of criteria (field => value)
     * @param string $class
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityByOr404($class, array $criteria, $managerName = null)
    {
        $entity = $this->findEntityBy($class, $criteria, $managerName);

        if (null === $entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with %s was not found.',
                $class,
                implode(' and ', array_map(
                    function ($k, $v) { sprintf('%s "%s"', $k, $v); },
                    array_flip($criteria),
                    $criteria
                ))
            ));
        }

        return $entity;
    }

    public function getMetadata($entity) {
        return $this->getManager()->getClassMetadata(get_class($entity));
    }

    public function getResource($type, $name)
    {
        $fullName  = $this->namespaces[$type] . ucfirst($name);
        $fullName .= ($type == 'form') ? 'Type' : '';

        return $fullName;
    }

    public function createJsonForm($model)
    {
        $fieldsMap  = $this->getMetadata($model)->fieldMappings;
        $form       = array();
        
        foreach ($fieldsMap as $name => $field) {
            $form[] = array(
                'name' => $name, //$field['columnName'],
                'type' => $field['type'],
                'placeholder' => ucfirst($field['columnName'])
            );
        }

        return $form;
    }
}
