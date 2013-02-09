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

    private $allErrors = array();
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

    public function getResourceFullName($type, $name)
    {
        $fullName  = $this->namespaces[$type] . ucfirst($name);
        $fullName .= ($type == 'form') ? 'Type' : '';

        return $fullName;
    }

    public function createJsonForm($form)
    {
        $jsonForm   = array();

        foreach ($form->createView()->getChildren() as $key => $child) {
            $types  = $child->vars['block_prefixes'];
            $type   = ($types[2] == 'text' && isset($types[3]) && (strpos($types[3], '_') === false)) ? $types[3] : $types[2];
            $format = '';
            
            if($type == 'datetime' || $type == 'date') {
                $type   = 'text';
                $format = 'datetime';
            }

            $jsonForm[] = array(
                'name'      => $key,
                'type'      => $type,
                'format'    => $format
            );
        }

        return $jsonForm;
    }

    public function getAllErrors($children, $template = true) {
        $this->getAllFormErrors($children);
        return $this->allErrors;
    }
   
    
    private function getAllFormErrors($children, $template = true) {
        foreach ($children as $child) {
            if ($child->hasErrors()) {
                $vars = $child->createView()->getVars();
                $errors = $child->getErrors();
                foreach ($errors as $error) {
                    $this->allErrors[$vars["name"]][] = $this->convertFormErrorObjToString($error);
                }
            }
    
            if ($child->hasChildren()) {
                $this->getAllErrors($child);
            }
        }
    }
    
    private function convertFormErrorObjToString($error) {
        $errorMessageTemplate = $error->getMessageTemplate();
        foreach ($error->getMessageParameters() as $key => $value) {
            $errorMessageTemplate = str_replace($key, $value, $errorMessageTemplate);
        }
        return $errorMessageTemplate;
    }

    public function getResourceObjects($resourceName)
    {
        $entityName  = $this->getResourceFullName('entity', $resourceName);
        $formName    = $this->getResourceFullName('form', $resourceName);

        $entity      = new $entityName();

        $form = $this->createForm(
            new $formName,  $entity
        );

        return array( 'form' => $form, 'entity' => $entity );
    }

    public function getFormErrors($form)
    {
        $this->translator = $this->get('translator');

        foreach($form->getErrors() as $e) {
            $errors[]=$this->translator->trans($this->convertFormErrorObjToString($e), array(), 'validators');
        }
        
        
        foreach($this->getAllErrors($form->getChildren()) as $key => $error) {
            $errors[$key] = $this->translator->trans($error[0], array(), 'validators');
        }

        return $errors;
    }

    public function getCurrentUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }

    public function getRestResponse($content, $statusCode = 200)
    {
        $response = new Response();
        
        $response->setStatusCode($statusCode);
        $response->setContent($this->serializer->serialize( $content, 'json' ));
        $response->headers->set('Content-type', 'application/json');

        return $response;
    }
}
