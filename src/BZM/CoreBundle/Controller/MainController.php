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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BZM\CoreBundle\Form\ProjectType;
use BZM\CoreBundle\Service\ParametersManager;

class MainController extends Controller
{
    /**
     * Switch home/cover actions
     *
     * @Route("/", name="bzm_core_homepage")
     * @Route("/{_locale}/", requirements={"_locale" = "fr|en"})
     * @Method("GET")
     */
    public function homeAction() {
        if ($this->container->hasParameter('project_name')) {
            return $this->redirectToRoute('bzm_website_home');
        } else {
            return $this->redirectToRoute('bzm_core_cover');
        }
    }

    /**
     * Renders cover page
     *
     * @Route("/cover", name="bzm_core_cover")
     * @Route("/{_locale}/cover", requirements={"_locale" = "fr|en"})
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @Method("GET")
     */
     public function coverAction() {
        if ($this->container->hasParameter('project_name')) {
            return $this->redirectToRoute('bzm_core_homepage');
        } else {
            return $this->render('BZMCoreBundle:Install:cover.html.twig');
        }
    }

    /**
     * GET: Renders Install view
     * POST: Installs project parameters and redirects to home
     *
     * @Route("/install", name="bzm_core_install")
     * @Route("/{_locale}/install", requirements={"_locale" = "fr|en"})
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @Method({"GET", "POST"})
     */
    public function installAction(Request $request, ParametersManager $pm) {
        if ($this->container->hasParameter('project_name')) {
            return $this->redirectToRoute('bzm_core_homepage');
        } else {
            $form = $this->createForm(ProjectType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $pm->saveParameters($form->getData());
                
                return $this->redirectToRoute('bzm_core_homepage');
            } 

            return $this->render('BZMCoreBundle:Install:install.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }
}
