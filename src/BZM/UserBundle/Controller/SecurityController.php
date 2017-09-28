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
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BZM\UserBundle\Entity\User;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    /**
    * Log in user
    *
    * @Route("/login")
    * @Route("/{_locale}/login", requirements={"_locale" = "fr|en"})
    */
    public function loginAction(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_ANONYMOUSLY');
        $countAdmin = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_ADMIN');
        
        if ($countAdmin == null) {
            return $this->redirectToRoute('bizom_core_homepage');
        } else {
            $session = $request->getSession();
            $authErrorKey    = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

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