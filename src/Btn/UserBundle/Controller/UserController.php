<?php
namespace Btn\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Btn\AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


// use Btn\UserBundle\Form\Type\ProfileFormType;

// use Symfony\Component\Validator\Constraints\Email;
// use Symfony\Component\Validator\Constraints\NotBlank;

use Btn\UserBundle\Form\Type\PasswordFormType;
use Btn\UserBundle\Form\Type\ProfileFormType;
use Btn\UserBundle\Form\Type\AddressFormType;
use Btn\UserBundle\Form\Type\InvoiceFormType;
use Btn\UserBundle\Form\Type\BankFormType;
use Btn\UserBundle\Form\Type\AllegroFormType;
use Btn\UserBundle\Form\Type\MessagesFormType;
use Mi\AppBundle\Form\UserImageFormType;
use Mi\ControlBundle\Entity\Image;
use Btn\AppBundle\Util\Text;

/**
 * @Route("/profile")
 */

class UserController extends Controller
{
    /**
     * pre executed method
     *
     **/
    public function preExecute()
    {
        $this->getRequest()->attributes->set('menu', 'user');
    }

    /**
     * @Route("/edit_bank", name="edit_bank")
     * @Template()
     */
    public function editBankAction()
    {
        //change bank data event
        $bankForm = $this->createForm(new BankFormType(), $this->getUser());
        $bankForm = $this->handleEditProfile($bankForm);

        return array(
            'bankForm' => $bankForm->createView(),
        );
    }

    /**
     * @Route("/edit_address", name="edit_address")
     * @Template()
     */
    public function editAddressAction()
    {
        //change address event
        $addressForm = $this->createForm(new AddressFormType(), $this->getUser());
        $addressForm = $this->handleEditProfile($addressForm);

        //change invoice data event
        $invoiceForm = $this->createForm(new InvoiceFormType(), $this->getUser());
        $invoiceForm = $this->handleEditProfile($invoiceForm);

        return array(
            'addressForm'  => $addressForm->createView(),
            'invoiceForm'  => $invoiceForm->createView(),
        );
    }

    /**
     * @Route("/edit_profile", name="edit_profile")
     * @Template()
     */
    public function editAction()
    {
        //change password event
        $passwordForm = $this->createForm(new PasswordFormType());
        $passwordForm = $this->handleChangePassword($passwordForm);

        //change profile event
        $profileForm = $this->createForm(new ProfileFormType(), $this->getUser());
        $profileForm = $this->handleEditProfile($profileForm);


        return array(
            'passwordForm' => $passwordForm->createView(),
            'profileForm'  => $profileForm->createView()
        );
    }

    /**
     * @Route("/edit_allegro", name="edit_allegro")
     * @Template()
     */
    public function editAllegroAction()
    {
        //change allegro data event
        $allegroForm = $this->createForm(new AllegroFormType(), $this->getUser());
        $allegroForm = $this->handleEditProfile($allegroForm);

        return array(
            'allegroForm' => $allegroForm->createView()
        );
    }

    /**
     * @Route("/edit_messages", name="edit_messages")
     * @Template()
     */
    public function editMessagesAction()
    {
        //change messages data event
        $messagesForm = $this->createForm(new MessagesFormType(), $this->getUser());
        $messagesForm = $this->handleEditProfile($messagesForm);

        return array(
            'messagesForm' => $messagesForm->createView()
        );
    }

    /**
     * @Route("/orders", name="orders")
     * @Template()
     */
    public function ordersAction()
    {
        return array();
    }

    /**
     * @Route("/show/{id}", name="public_profile")
     * @ParamConverter("user", class="BtnUserBundle:User")
     * @Template()
     *
     * TODO: complete template data
     */
    public function showAction($user)
    {
        $em = $this->getManager()->getRepository('MiControlBundle:Ad');

        //get user comments ( resolved ads )
        $options = array('active' => 0, 'resolved' => 1);
        $userAds = $em->getCustomUserAds($user, $options);

        //get user active ads
        $data['search'] = count($em->findBy(array('type' => 0, 'active' => 1, 'resolved' => 0, 'user' => $user)));
        $data['make']   = count($em->findBy(array('type' => 1, 'active' => 1, 'resolved' => 0, 'user' => $user)));

        $data['positive'] = $em->getUserRating($user, 0);
        $data['neutral']  = $em->getUserRating($user, 1);
        $data['negative'] = $em->getUserRating($user, 2);

        $images = $this->getManager()->getRepository('MiControlBundle:Image')->findBy(array('user' => $user));

        return array(
            'user'    => $user,
            'data'    => $data,
            'userAds' => $userAds,
            'images'  => $images
        );
    }

    /**
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction()
    {
        //$form = $this->createForm(new UserImageFormType());
        $form   = $this->handleAddImage();
        $images = $this->getManager()->getRepository('MiControlBundle:Image')->findBy(array('user' => $this->getUser()));

        return array(
            'form'     => $form->createView(),
            'images'   => $images,
            'jewelery' => $this->getUser()->getJeweleryProjects()
        );
    }

    /**
     * @Route("/remove_image/{id}", name="remove_image")
     * @Template()
     */
    public function removeImageAction($id)
    {
        //$form = $this->createForm(new UserImageFormType());
        //$form = $this->handleRemoveImage();
        $em = $this->getManager();

        $image = $em->getRepository('MiControlBundle:Image')->findOneBy(array('id' => $id, 'user' => $this->getUser()));

        if (!$image) {
            $this->setFlash('image.flash.del_error', 'error');
            die();
        } else {
            $em->remove($image);
            $em->flush();
            $this->setFlash('image.flash.del_ok');
        }

        return $this->redirect($this->generateUrl('profile'));
    }

    /**
     * @Route("/user_loggedin", name="user_loggedin")
     */
    public function userLoggedInAction()
    {
        $response = new Response();
        $response->setContent(0);
        $response->headers->set('Content-type', 'application/json');
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $response->setContent($this->getUser()->getId());
        }

        return $response;
    }

    /**
     * @Route("/finance/invoices", name="user_finance_invoices")
     * @Template()
     */
    public function financeInvoicesAction()
    {
        $manager = $this->container->get('btn.invoice_manager');
        $invoices = $manager->findAllBy(array('parentId' => $this->getUser()->getId()));

        return array('invoices' => $invoices);
    }

    /**
     * @Route("/finance/ads", name="user_finance_ads")
     * @Template()
     */
    public function financeAdsAction()
    {
        $ads = $this->getRepository('MiControlBundle:Ad')->getResolvedByUser($this->getUser());

        return array('ads' => $ads);
    }

    /**
     * @Route("/finance/invoice/download/{id}", name="invoice_download")
     * @Template()
     */
    public function downloadInvoiceAction($id)
    {
        $manager = $this->container->get('btn.invoice_manager');
        $result = $manager->download($id, $this->getUser()->getId());

        if (!$result) {
            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * @Route("/activity", name="activity")
     * @Template()
     */
    public function activityAction()
    {
        return array();
    }

    /**
    * Edit profile
    *
    * @param  ProfileFormType $form
    * @return ProfileFormType $form
    */
    private function handleEditProfile($form)
    {
        if ('POST' === $this->getRequest()->getMethod() && $this->getRequest()->get($form->getName())) {
            $userRegion = $this->getUser()->getRegion()->getId();
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $em = $this->getManager();
                //change user ads region, if needed
                $formData = $form->getData();
                if ($userRegion !== $formData->getRegion()->getId()) {
                    $userAds = $em->getRepository('MiControlBundle:Ad')->findby(array('user' => $this->getUser()));
                    foreach ($userAds as $ad) {
                        $ad->setRegion($this->getUser()->getRegion());
                        $em->persist($ad);
                    }
                }
                $em->persist($formData);
                $em->flush();
                $this->setFlash('profile.flash.success');

                new RedirectResponse('edit_profile');
            }
        }

        return $form;
    }

    /**
    * Change password
    *
    * @param  PasswordFormType $form
    * @return PasswordFormType $form
    */
    private function handleChangePassword($form)
    {
        $user = $this->getUser();
        if ('POST' === $this->getRequest()->getMethod() && $this->getRequest()->get($form->getName())) {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                $data = $form->getData();
                $user->setPlainPassword($data['new']);
                $userManager->updateUser($user);
                $this->setFlash('change_password.flash.success');

                new RedirectResponse('edit_profile');
            }
        }

        return $form;
    }

    /**
    * Add user gallery image
    *
    * @return UserImageFormType $form
    */
    private function handleAddImage()
    {
        $form = $this->createForm(new UserImageFormType());

        if ('POST' === $this->getRequest()->getMethod() && $this->getRequest()->get($form->getName())) {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $formData = $form->getData();
                $image    = new Image();
                $image->setUser($this->getUser());
                $image->setName($formData['name']);
                //set image source
                if (!empty($formData['src'])) {
                    $filename = basename($formData['src']);
                    $written = file_put_contents($image->getUploadPath() . $filename, file_get_contents($formData['src']));
                    if (!$written) {
                        $this->setFlash('image.flash.fail', 'error');

                        return $form;
                    }
                    $image->setSrc($filename);
                } else {
                    $data = $formData['file'];
                    if (is_object($data) && $data->getClientOriginalName() !== '') {
                        //shrotcut root dir
                        $dir      = $image->getUploadPath();
                        //unique name
                        $filename = Text::slugifyUnique($data->getClientOriginalName()) . '.' . $data->guessExtension();

                        $data->move($dir, $filename);
                        $image->setSrc($filename);
                        //resize image
                        $imageUrl = $dir . $filename;
                        $this->container->get('mi.image_manager')->resizeImage($imageUrl);
                    } else {
                        $this->setFlash('image.flash.fail', 'error');

                        return $form;
                    }
                }
                $em = $this->getManager();
                $em->persist($image);
                $em->flush();
                $this->setFlash('image.flash.success');

                //return $this->
            }
        }

        return $form;
    }
}