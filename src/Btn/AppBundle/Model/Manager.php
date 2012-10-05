<?php

namespace Btn\AppBundle\Model;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
* $manager = $this->container->get('zd.some_entity_manager')
*                 ->setNs('namespace')
*                 ->createForm(new EntityFilterType())
*                 ->filter()
*                 ->paginate();
*
* or without filtering
*
* $manager = $this->container->get('zd.some_entity_manager')
*                 ->setNs('namespace')
*                 ->setQueryMethod('getBySomeRepoMethid') - default is getSearchQuery
*                 ->setCustomConditions(array $customConditions) - custom conditions will be submitted to queryMethod
*                 ->enablePageSession() - read current pase from session if not in request (by defaul is disabled)
*                 ->fetchQuery($query)  - getting custom query - method $this->queryMethod will not be fired
*                 ->paginate();
*
* - now we have manager with filtering and pagination.
*  $pagination = $manager->getPagination()
*  $filterForm = $manager->getForm()->createView();
*
* - if you want to change pagination tpl
*  $pagination->setTemplate(...) by default there is 'BtnCrudBundle:Pagination:default.html.twig'
*  or
*  $manager->...
*           ->filter()
*           ->setPaginationTpl($tpl)
*           ->paginate()
*
* useful methods:
* - getRepo()
* - setCustomConditions(array)
* - getQueryBuilder
*/
class Manager {

    /**
     * EntityManager.
     *
     * @var EntityManager
     */
    public $em;

    /**
     * repository
     */
    protected $repo;

    /**
     * knp_paginator object
     */
    protected $paginator;

    /**
     * $paginator->paginate(...) result
     */
    protected $pagination;

    protected $paginatonTpl = 'BtnCrudBundle:Pagination:default.html.twig';

    /**
     * request object
     */
    protected $request;

    /**
     * twig object
     */
    protected $twig;

    /**
     * formFactory - createForm purpose
     */
    protected $formFactory;

    /**
     * Doctrine\ORM\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * fitlers array - XFilterType client data array
     */
    protected $filters;

    /**
     * custom filters array
     */
    protected $customConditions;

    protected $conditions = array();

    /**
     * form created via formFactory
     */
    protected $form;

    /**
     * form type object, formFactory->createForm($this->type)
     */
    protected $type;

    protected $page = 1;

    /**
     * namespace
     */
    protected $ns = '';

    protected $queryMethod = 'getSearchQuery';

    protected $query = null;

    protected $pageSession = false;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param Paginator $paginator
     */
    public function __construct(EntityManager $em, Paginator $paginator, Request $request, \Twig_Environment $twig, $formFactory)
    {
        $this->em           = $em;
        $this->paginator    = $paginator;
        $this->request      = $request;
        $this->session      = $request->getSession();
        $this->twig         = $twig;
        $this->formFactory  = $formFactory;
        $this->queryBuilder = $this->em->createQueryBuilder();

    }

    /**
     * - fire knp_paginator object paginate action and assign result to the $this->pagination property
     * - set default tpl
     */
    public function paginate($limit = 10)
    {
        //try to get currrent page from request or session
        $this->processPage();

        $this->pagination = $this->paginator->paginate($this->getQuery(), $this->getPage(), $limit);
        $this->pagination->setTemplate($this->getPaginatonTpl());
        return $this;
    }

    /**
     * pagination property getter
     */
    public function getPagination()
    {
        return $this->pagination;
    }


    /**
     * - process filters from request
     * - set conditions and merge with customConditions if there are any
     * - setQuery for paginate
     * @return this
     */
    public function filter()
    {
        $this->processFilter();

        $this->conditions = $this->type->getExpr($this->form->getClientData(), $this->getExpr());

        return $this;
    }

    /**
    * setting query from argument or trying to fire the queryMehod on repository
    */
    public function fetchQuery($query = null)
    {
        if ($query !== null) {
            $this->query = $query;
        } else {
            //set base on $this->repo->queryMethod()
            $this->setQuery();
        }
        return $this;
    }

    /**
     * process filters in request, set page
     */
    protected function processFilter()
    {
        if ($data = $this->request->get($this->form->getName())) {

            $this->form->bind($this->request->get($this->form->getName()));

            if ($this->form->isValid()) {
                $this->setFilters();
                $this->setPage(1);
            }
        } elseif ($this->request->get('remove')) {
            //clean session
            $this->session->remove($this->getNs().'filters');
            $this->setPage(1);
        } else {
            //try to bind from session
            $this->form->bind($this->getFilters());
        }

        //try to get current page from request
        $this->processPage();
    }

    /**
    * set current page from request if doesn't exists
    * set to 1
    */
    protected function processPage()
    {
        if ($this->request->get('page')) {
            $this->setPage($this->request->get('page'));
        } elseif ($this->pageSession === false) {
            $this->setPage(1);
        }
    }

    /**
    * set filters on the base of bound form, if not bound set filters to null
    */
    protected function setFilters() {

        $filters = null;

        if ($this->form->isBound()) {
            foreach ($this->form->getClientData() as $key => $value) {
                $filters[$key] = $this->form[$key]->getClientData();
            }//foreach
        }
        $this->session->set($this->getNs().'filters', $filters);
    }

    /**
    * get filters from session
    */
    protected function getFilters()
    {
        return $this->session->get($this->getNs().'filters', null);
    }

    /**
     * create form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        $this->setForm($this->formFactory->create($type, $data, $options));
        $this->type = $type;
        return $this;
    }

    /**
     * set EntityType form instance
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setNs($ns)
    {
        $this->ns = $ns;
        return $this;
    }

    public function getNs($default = 'core')
    {
        return $this->ns ? $this->ns : $default;
    }

    public function setCustomConditions($filters)
    {
        $this->customConditions = $filters;
        return $this;
    }

    public function getCustomConditions()
    {
        return $this->customConditions;
    }

    protected function setPage($page)
    {
        $this->session->set($this->getNs().'page', $page);
    }

    protected function getPage()
    {
        return $this->session->get($this->getNs().'page', 1);
    }

    public function getPaginator()
    {
        return $this->paginator;
    }

    public function getRepo()
    {
        return $this->repo;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function getExpr()
    {
        return $this->queryBuilder->expr();
    }

    public function setQuery()
    {
        $func = $this->getQueryMethod();

        //merge conditions with custom conditions
        if (!empty($this->customConditions) && is_array($this->customConditions)) {
            $this->conditions = array_merge($this->conditions, $this->customConditions);
        }

        return $this->query = $this->repo->$func($this->conditions);
    }

    public function getQuery($query = null)
    {
        if ($this->query === null) $this->fetchQuery($query);
        return $this->query;
    }

    public function getPaginatonTpl()
    {
        return $this->paginatonTpl;
    }

    public function setPaginationTpl($tpl)
    {
        $this->paginatonTpl = $tpl;
        return $this;
    }

    /**
     * Get upload path
     *
     * @return path to keep avatars
     */
    public function getUploadPath($type = 'avatars')
    {
        $path = __DIR__.'/../../../../web/uploads/'.$type.'/';
        //check directory and create if we don't have it
        if (!is_dir($path)) {
          @mkdir($path, 0777);
        }
        //get upload path
        return $path;
    }

    /**
    *
    * set method for retrieving query from repo
    *
    * @param string $method - be sure method_exists($repo, $method)
    * @return $this
    */
    public function setQueryMethod($method)
    {
        $this->queryMethod = $method;
        return $this;
    }

    public function getQueryMethod()
    {
        return $this->queryMethod;
    }

    /**
     * enable reading current page from session
     * if doesn't exist in request
     *
     * @return this
     */
    public function enablePageSession()
    {
        $this->pageSession = true;
        return $this;
    }

    /**
     * disable reading current page from session
     * if doesn't exist in request
     *
     * @return this
     */
    public function disablePageSession()
    {
        $this->pageSession = false;
        return $this;
    }

    public function setPageSession($value)
    {
        $this->pageSession = $value ? true : false;
        return $this;
    }

    public function getPageSession()
    {
        return $this->pageSession;
    }

    /**
    * proxy functions - set session key => value
    * @param string $key
    * @param mixed $value
    * @return $this
    */
    public function sset($key, $value)
    {
        $this->session->set($this->getNs().'_'.$key, $value);
        return $this;
    }

    /**
    * proxy function - session getter
    *
    * @param string $key
    * @param mixed $value
    *
    * @return $this
    */
    public function sget($key, $default = null)
    {
        return $this->session->get($this->getNs().'_'.$key, $default);
    }

    public function update($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        return $this;
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
        return $this;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->repo, $method), $args);
    }

}