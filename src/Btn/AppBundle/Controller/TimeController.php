<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Btn\AppBundle\Entity\Time;
use Btn\AppBundle\Form\TimeType;

class TimeController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        //get time reports for current user
        $manager = $this->container
            ->get('btn.time_manager')
            ->setNs('time')
            ->fetchQuery($this->getRepository('BtnAppBundle:Time')->getQueryForUser($this->getUser()))
            ->setPaginationTpl('BtnAppBundle:Time:pagination.html.twig')
            ->paginate(1)
        ;

        //setup form
        $time = new Time();
        $time->setUser($this->getUser());
        $time->setBillable(true);
        $form = $this->createForm(new TimeType($this->getDoctrine()->getEntityManager()), $time);

        //add some post save here
        $this->processForm($request, $form, $time);

        return array(
            'pagination' => $manager->getPagination(),
            'form'       => $form->createView()
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
            }

            $em->persist($time);
            $em->flush();

            $msg = $this->get('translator')->trans('crud.flash.saved');
            $this->getRequest()->getSession()->setFlash('success', $msg);

            return $this->redirect($this->generateUrl('homepage'));
        }
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

        $em->remove($time);
        $em->flush();

        $msg = $this->get('translator')->trans('Time item deleted');
        $this->getRequest()->getSession()->setFlash('success', $msg);

        return $this->redirect($this->generateUrl('homepage'));
    }

}
