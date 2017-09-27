<?php

/**
 * Registration controller
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

 namespace BZM\UserBundle\Controller;

 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\RedirectResponse;
 use FOS\UserBundle\FOSUserEvents;
 use FOS\UserBundle\Event\GetResponseUserEvent;
 use FOS\UserBundle\Event\FilterUserResponseEvent;
 use FOS\UserBundle\Event\FormEvent;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
 use FOS\UserBundle\Controller\RegistrationController as BaseController;
 use BZM\UserBundle\Entity\User;

 class RegistrationController extends BaseController
 {
    /**
     * Register user
     *
     * @Route("/register")
     * @Route("/{_locale}/register", requirements={"_locale" = "fr|en"})
     */
    public function registerAction(Request $request) {
        $authChecker = $this->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_USER') || $authChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException();
        } else {
            /** @var $formFactory FactoryInterface */
            $formFactory = $this->get('fos_user.registration.form.factory');
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            /** @var $dispatcher EventDispatcherInterface */
            $dispatcher = $this->get('event_dispatcher');

            $user = $userManager->createUser();
            $user->setEnabled(true);

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }
            
            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $event = new FormEvent($form, $request);
                    $countUsers = $this->getDoctrine()->getRepository(User::class)->findAll();

                    if ($countUsers == null) {
                        $user->addRole('ROLE_ADMIN');
                    }

                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                    $userManager->updateUser($user);
                    
                    if (null === $response = $event->getResponse()) {
                        $url = $this->generateUrl('fos_user_registration_confirmed');
                        $response = new RedirectResponse($url);
                    }

                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                    return $response;
                }

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

                if (null !== $response = $event->getResponse()) {
                    return $response;
                }
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}