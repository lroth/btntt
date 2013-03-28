<?php

namespace Btn\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    protected $namespaces = array(
        'form'   => 'Btn\\AppBundle\\Form\\',
        'entity' => 'Btn\\AppBundle\\Entity\\'
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
     * @param array $array
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
     * @param array $array
     * @param Response $response
     *
     * @return Response A Response instance
     */
    public function json(array $array, Response $response = NULL)
    {
        if (NULL === $response) {
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
    public function getManager($name = NULL)
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
    public function getRepository($repositoryName, $managerName = NULL)
    {
        return $this->getManager($managerName)->getRepository($repositoryName);
    }

    /**
     * Shortcut to find an entity by id
     *
     * @param string $class
     * @param mixed $id
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntity($class, $id, $managerName = NULL)
    {
        return $this->getRepository($class, $managerName)->find($id);
    }

    /**
     * Shortcut to find an entity by criteria
     *
     * @param string $class
     * @param array $criteria    An array of criteria (field => value)
     * @param string $managerName
     *
     * @return object or NULL if the entity was not found
     */
    public function findEntityBy($class, array $criteria, $managerName = NULL)
    {
        return $this->getRepository($class, $managerName)->findOneBy($criteria);
    }

    /**
     * Finds the entity by id or throws a NotFoundHttpException
     *
     * @param string $class
     * @param mixed $id
     * @param string $managerName
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityOr404($class, $id, $managerName = NULL)
    {
        $entity = $this->findEntity($class, $id, $managerName);

        if (NULL === $entity) {
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
     * @param array $criteria An array of criteria (field => value)
     * @param string $class
     *
     * @return object The found entity
     *
     * @throws NotFoundHttpException if the entity was not found
     */
    public function findEntityByOr404($class, array $criteria, $managerName = NULL)
    {
        $entity = $this->findEntityBy($class, $criteria, $managerName);

        if (NULL === $entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with %s was not found.',
                $class,
                implode(' and ', array_map(
                    function ($k, $v) {
                        sprintf('%s "%s"', $k, $v);
                    },
                    array_flip($criteria),
                    $criteria
                ))
            ));
        }

        return $entity;
    }

    public function getMetadata($entity)
    {
        return $this->getManager()->getClassMetadata(get_class($entity));
    }

    public function getResourceFullName($type, $name, $editMode = FALSE)
    {
        $fullName = $this->namespaces[$type] . ucfirst($name);

        if ($type == 'form') {
            $fullName .= ($editMode) ? 'Edit' : '';
            $fullName .= 'Type';
        }

        return $fullName;
    }

    public function createJsonForm($form)
    {
        $jsonForm = array();
        foreach ($form->createView()->getChildren() as $key => $child) {

            $types  = $child->vars['block_prefixes'];
            $type   = ($types[2] == 'text' && isset($types[3]) && (strpos($types[3], '_') === FALSE)) ? $types[3] : $types[2];
            $format = '';

            if ($type == 'datetime' || $type == 'date') {
                $type   = 'text';
                $format = 'datetime';
            }

            $jsonForm[] = array(
                'name'   => $key,
                'type'   => $type,
                'format' => $format
            );

            if ($type == 'choice') {
                $choicesArr = array();
                $choices    = $child->vars['choices'];

                foreach ($choices as $choice) {
                    $choicesArr[] = array('label' => $choice->label, 'value' => $choice->value);
                }

                $jsonForm[count($jsonForm) - 1]['choices'] = $choicesArr;
            }
        }

        return $jsonForm;
    }

    public function getAllErrors($children, $template = TRUE)
    {
        $this->getAllFormErrors($children);
        return $this->allErrors;
    }

    private function getAllFormErrors($children, $template = TRUE)
    {
        foreach ($children as $child) {
            if ($child->hasErrors()) {
                $vars   = $child->createView()->getVars();
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

    private function convertFormErrorObjToString($error)
    {
        $errorMessageTemplate = $error->getMessageTemplate();
        foreach ($error->getMessageParameters() as $key => $value) {
            $errorMessageTemplate = str_replace($key, $value, $errorMessageTemplate);
        }
        return $errorMessageTemplate;
    }

    public function getResourceObjects($resourceName, $entityId = NULL)
    {
        $editMode   = ($entityId != NULL);
        $entityName = $this->getResourceFullName('entity', $resourceName);
        $formName   = $this->getResourceFullName('form', $resourceName, $editMode);

        /* grab entity here */
        $entity = ($editMode) ? $this->manager->getRepository($entityName)->find($entityId) : (new $entityName());

        $form = $this->createForm(
            new $formName, $entity
        );

        return array('form' => $form, 'entity' => $entity);
    }

    public function getFormErrors($form)
    {
        $this->translator = $this->get('translator');

        foreach ($form->getErrors() as $e) {
            $errors[] = $this->translator->trans($this->convertFormErrorObjToString($e), array(), 'validators');
        }

        foreach ($this->getAllErrors($form->getChildren()) as $key => $error) {
            $errors[$key] = $this->translator->trans($error[0], array(), 'validators');
        }

        return $errors;
    }

    public function isAuthenticated()
    {
        return $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function getRestResponse($content, $statusCode = 200)
    {
        //TODO: doesn't work in IE
        $response = new Response();

        $response->setStatusCode($statusCode);
        $response->setContent($this->serializer->serialize($content, 'json'));
        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

    protected function getPaginator($resourceName, $phrase = '')
    {
        //get manager
        $manager = $this->container->get('btn.' . $resourceName . '_manager');
        $manager->setNs($resourceName);

        if (!empty($phrase)) {
            //set custom condition
            $conditions = array(
                $manager->getQueryBuilder()->expr()->like('l.name', $manager->getQueryBuilder()->expr()->literal('%' . $phrase . '%'))
            );

            $manager->setCustomConditions($conditions);
        }

        //paginate 2 items per page
        $manager->paginate(1);

        //we have a sliding pagination here with iterator interface
        return $manager->getPagination();
    }

    public function getPaginatorResources($paginator)
    {
        $resources = array();

        foreach ($paginator as $resource) {
            $resources[] = $resource;
        }

        return $resources;
    }

    public function getPaginatorDetails($paginator)
    {
        return array(
            'current'      => $paginator->getCurrentPageNumber(),
            'totalRecords' => $paginator->getTotalItemCount(),
            'perPage'      => $paginator->getItemNumberPerPage()
        );
    }
}