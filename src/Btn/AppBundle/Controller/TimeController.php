<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Btn\AppBundle\Entity\Time;
use Btn\AppBundle\Form\TimeType;

/**
 * User controller.
 *
 * @Route("/time")
 */
class TimeController extends BaseController
{
    public function preExecute()
    {
        $this->getRequest()->request->set('control_nav', 'time');
    }

    /**
     * Get all projects as json response with query
     *
     * @Route("/autocomplete", name="projects_autocomplete")
     * @return json
     **/
    public function autocompleteAction(Request $request)
    {
        $projects = $this->getRepository('BtnAppBundle:Project')
            ->findSuggestions($request->get('query'));

        return $this->json($projects);
    }

    /**
     * @Route("/", name="time")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        //setup form for current user
        $time = new Time();
        $time->setUser($this->getUser());

        $form = $this->createForm(new TimeType($this->getManager()), $time);

        //add some post save here
        if ($request->getMethod() == 'POST') {
            return $this->processForm($request, $form, $time);
        }

        //get time reports for current user
        $manager = $this->container
            ->get('btn.time_manager')
            ->setNs('time')
            ->fetchQuery($this->getRepository('BtnAppBundle:Time')->getQueryForUser($this->getUser()))
            ->setPaginationTpl('BtnAppBundle:Time:pagination.html.twig')
            ->paginate(10);

        //take your last 7 days summary
        $lastActivity = $this->container
            ->get('btn.time_manager')
            ->getLastActivity($this->getUser(), 7);

        return array(
            'pagination' => $manager->getPagination(),
            'form' => $form->createView(),
            'lastActivity' => $lastActivity,
            'editPeriod' => new \DateTime('-3 days')
        );
    }


    private function processForm($request, $form, $time)
    {
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //resolve if time is billable or not
            if (substr($time->getDescription(), 0, 1) == $this->container->getParameter('unbillable_char')) {
                $time->setBillable(false);
            } else {
                $time->setBillable(true);
            }

            $em->persist($time);
            $em->flush();

            $msg = $this->get('translator')->trans('crud.flash.saved');
            $this->getRequest()->getSession()->setFlash('success', $msg);

            return $this->redirect($this->generateUrl('time'));
        }
    }

    /**
     * @Route("/edit/{id}", name="edit_time")
     * @ParamConverter("time", class="BtnAppBundle:Time")
     * @Template()
     *
     * @return void
     **/
    public function editAction(Time $time)
    {
        if ($time->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('This is not your Time entity.');
        }

        //check if it is not to late to edit this one
        if ($time->getCreatedAt() < new \DateTime('-3 days')) {
            throw $this->createNotFoundException('It is to late to edit this entity.');
        }

        $form = $this->createForm(new TimeType($this->getManager()), $time);

        return array(
            'time' => $time,
            'form' => $form->createView()
        );
    }

    /**
     *
     * @Route("/update/{id}", name="update_time")
     * @Method("POST")
     * @ParamConverter("time", class="BtnAppBundle:Time")
     * @Template("BtnAppBundle:Time:edit.html.twig")
     */
    public function updateAction(Time $time)
    {
        if ($time->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('This is not your Time entity.');
        }

        //check if it is not to late to edit this one
        if ($time->getCreatedAt() < new \DateTime('-3 days')) {
            throw $this->createNotFoundException('It is to late to edit this entity.');
        }

        $form = $this->createForm(new TimeType($this->getManager()), $time);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            //store changes as history
            $now = new \DateTime('now');
            $history = $time->getHistory();
            $history[] = array($now->format('d-m-Y H:i'), $time->getTime());
            $time->setHistory($history);

            $this->getManager()->persist($time);
            $this->getManager()->flush();

            //render current row and return it
            return new Response($this->renderView('BtnAppBundle:Time:_row.html.twig', array(
                'time' => $time,
                'editPeriod' => new \DateTime('-3 days')
            )));
        }

        return array(
            'time' => $time,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/delete/{id}", name="delete_time")
     * @ParamConverter("time", class="BtnAppBundle:Time")
     *
     * @return void
     **/
    public function delete(Time $time)
    {
        $em = $this->getManager();

        if (!$time) {
            throw $this->createNotFoundException('Unable to find Time entity.');
        }

        if ($time->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('This is not your Time entity.');
        }

        //check if it is not to late to edit this one
        if ($time->getCreatedAt() < new \DateTime('-3 days')) {
            throw $this->createNotFoundException('It is to late to edit this entity.');
        }

        $em->remove($time);
        $em->flush();

        $msg = $this->get('translator')->trans('Time item deleted');
        $this->getRequest()->getSession()->setFlash('success', $msg);

        return $this->redirect($this->generateUrl('time'));
    }

}
