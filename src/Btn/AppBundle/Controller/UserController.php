<?php

namespace Btn\AppBundle\Controller;

use Btn\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Btn\UserBundle\Entity\User;
use Btn\AppBundle\Entity\Profile;

use Btn\AppBundle\Form\PasswordFormType;
use Btn\AppBundle\Form\EmailFormType;
use Btn\AppBundle\Form\UserFormType;

use Btn\AppBundle\Util\Text;
use Btn\AppBundle\Util\FileUploader\FileUploader;
use Btn\AppBundle\Form\UserFilterType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends BaseController
{
    public function preExecute()
    {
       $this->getRequest()->request->set('control_nav', 'user');
    }

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $manager = $this->container->get('btn.user_manager')
            ->setNs('user_control')
            ->createForm(new UserFilterType())
            ->setRequest($request)
            ->enablePageSession()
            ->filter()
            ->paginate()
        ;

        return array(
            'pagination' => $manager->getPagination(),
            'form'       => $manager->getForm()->createView()
        );
    }


    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserFormType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="user_create")
     * @Method("POST")
     * @Template("BtnAppBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();
        $form = $this->createForm(new UserFormType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $manipulator = $this->container->get('fos_user.util.user_manipulator');
            // public function create($username, $password, $email, $active, $superadmin)
            $entity = $manipulator->create($user->getUsername(),  $user->getUsername(), $user->getEmail(), true, false);

            $msg = $this->get('translator')->trans('crud.flash.saved');
            $this->getRequest()->getSession()->setFlash('success', $msg);

            return $this->redirect($this->generateUrl('user_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        //change profile event
        $editForm   = $this->createForm(new UserFormType($entity), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="user_update")
     * @Method("POST")
     * @Template("BtnAppBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createForm(new UserFormType($entity), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();

            $msg = $this->get('translator')->trans('crud.flash.saved');
            $this->getRequest()->getSession()->setFlash('success', $msg);

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="user_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BtnUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();

            $msg = $this->get('translator')->trans('crud.flash.deleted');
            $this->getRequest()->getSession()->setFlash('success', $msg);
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * email
     *
     * @Route("/{id}/email", name="user_email")
     * @Template()
     */
    public function emailAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User user.');
        }

        $form = $this->createForm(new EmailFormType($user));

        if ('POST' === $this->getRequest()->getMethod() && $this->getRequest()->get($form->getName())) {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                if ($user->getEmail() != $form->get('email')->getData()) {

                    // $user->setUsername($form->get('email')->getData());
                    $user->setEmail($form->get('email')->getData());
                    $user->setConfirmationToken(null);

                    $userManager = $this->get('fos_user.user_manager');
                    $userManager->updateUser($user);

                    $this->setFlash('user.email_success');
                }

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
        }

        return array(
            'entity' => $user,
            'form'   => $form->createView()
        );
    }

    /**
     * password
     *
     * @Route("/{id}/password", name="user_password")
     * @Template()
     */
    public function passwordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new PasswordFormType($user));

        if ('POST' === $this->getRequest()->getMethod() && $this->getRequest()->get($form->getName())) {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                $data = $form->getData();
                $user->setPlainPassword($data['new']);
                $userManager->updateUser($user);
                $this->setFlash('change_password.flash.success');

                return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
            }
        }

        return array(
            'entity' => $user,
            'form'   => $form->createView()

        );

    }

    /**
     * user disable
     *
     * @Route("/{id}/disable", name="user_disable")
     * @Template()
     */
    public function disableAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $userManager = $this->get('fos_user.user_manager');
        $user->setEnabled(false);
        $userManager->updateUser($user);

        $this->setFlash('user.disabled');

        return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
    }

    /**
     * user confirm
     *
     * @Route("/{id}/confirm", name="user_confirm")
     * @Template()
     */
    public function confirmAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BtnUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $userManager = $this->get('fos_user.user_manager');
        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $userManager->updateUser($user);

        $this->setFlash('user.confirmed');

        return $this->redirect($this->generateUrl('user_edit', array('id' => $user->getId())));
    }

}
