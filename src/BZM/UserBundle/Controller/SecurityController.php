<?php

/**
 * Security controller
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use BZM\UserBundle\Entity\User;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    /**
    * Log in user
    *
    * @Route("/login")
    * @Route("/{_locale}/login", requirements={"_locale" = "fr|en"})
    * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
    */
    public function loginAction(Request $request) {
        $countAdmin = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_ADMIN');
        
        if (!$this->container->hasParameter('project_name')) {
            return $this->redirectToRoute('bzm_core_cover');
        } if ($countAdmin == null) {
            return $this->redirectToRoute('fos_user_registration_register');
        } else {
            $session = $request->getSession();
            $authErrorKey    = CoreSecurity::AUTHENTICATION_ERROR;
            $lastUsernameKey = CoreSecurity::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->has('security.csrf.token_manager')
                ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
                : null;

            return $this->renderLogin(array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }
    }
}