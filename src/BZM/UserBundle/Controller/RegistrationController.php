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
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
 use FOS\UserBundle\Controller\RegistrationController as BaseController;
 use BZM\UserBundle\Entity\User;

 class RegistrationController extends BaseController
 {
    /**
     * Register user
     *
     * @Route("/register")
     * @Route("/{_locale}/register", requirements={"_locale" = "fr|en"})
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function registerAction(Request $request) {
        $countAdmin = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_ADMIN');

        if ($countAdmin != null) {
            return $this->redirectToRoute('fos_user_security_login');
        } else {
            $formFactory = $this->get('fos_user.registration.form.factory');
            $userManager = $this->get('fos_user.user_manager');
            $dispatcher = $this->get('event_dispatcher');
            $user = $userManager->createUser();
            $event = new GetResponseUserEvent($user, $request);
            $form = $formFactory->createForm();

            $user->setEnabled(true);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }
            
            $form->setData($user);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $event = new FormEvent($form, $request);
                    $user->addRole('ROLE_ADMIN');
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

            return $this->render('@FOSUser/Registration/register.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }     
}