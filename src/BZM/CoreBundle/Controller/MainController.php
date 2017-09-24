<?php

/**
 * Main controller
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BZM\CoreBundle\Form\ProjectType;
use BZM\CoreBundle\Service\ParametersManager;

class MainController extends Controller
{
    /**
     * Redirects to correct home page
     *
     * @Route("/", name="bizom_core_homepage")
     * @Method("GET")
     */
    public function homeAction(Request $request, ParametersManager $pm) {
        $authChecker = $this->get('security.authorization_checker');
        $parameters  = $pm->decodeParameters();
        
        if ($authChecker->isGranted('ROLE_USER') || $authChecker->isGranted('ROLE_ADMIN')) {
            if (!array_key_exists('project_name', $parameters['parameters'])) {
                if (!$this->get('session')->get('installation') == 'Done') {
                    $this->get('session')->set('installation', 'Setting');
                }

                return $this->redirectToRoute('bizom_core_install');
            } else {
                return $this->redirectToRoute('bizom_website_home');
            }
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * Installs project parameters
     *
     * @Route("/install", name="bizom_core_install")
     * @Route("/{_locale}/install", requirements={"_locale" = "fr|en"})
     * @Method({"GET", "POST"})
     */
    public function installAction(Request $request, ParametersManager $pm) {
        if ($this->get('session')->get('installation') == 'Setting') {
            $form = $this->createForm(ProjectType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $parameters = $pm->decodeParameters();
                $data = $form->getData();
                $pm->saveParameters($parameters, $data);
                $this->get('session')->set('installation', 'Done');

                return $this->redirectToRoute('bizom_core_homepage');
            } 

            return $this->render('BZMCoreBundle:Core:install.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            throw $this->createNotFoundException();
        }
    }
}
