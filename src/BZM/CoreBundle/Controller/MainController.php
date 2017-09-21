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
use Doctrine\ORM\EntityManagerInterface;
use BZM\CoreBundle\Form\ProjectType;
use BZM\CoreBundle\Entity\Project;
use BZM\CoreBundle\Service\ParametersManager;

class MainController extends Controller
{
    /**
     * Redirects to correct home page
     *
     * @Route("/", name="bizom_core_homepage")
     * @Method("GET")
     */
    public function homeAction(EntityManagerInterface $em, ParametersManager $pm) {
        $authChecker = $this->get('security.authorization_checker');
            
        if ($authChecker->isGranted('ROLE_USER') || $authChecker->isGranted('ROLE_ADMIN')) {
            $project    = $em->getRepository(Project::class)->findAll();
            $parameters = $pm->decodeParameters();
            
            if ($project == null || ($project && !array_search($project[0]->getProjectName(), $parameters['parameters']))) {
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
     * @Method({"GET", "POST"})
     */
    public function installAction(Request $request, EntityManagerInterface $em, ParametersManager $pm) {
        $form        = $this->createForm(ProjectType::class);
        $project     = $em->getRepository(Project::class)->findAll();
        $parameters  = $pm->decodeParameters();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $pm->saveParameters($parameters, $data);

            if ($project) {
                $em->remove($project[0]);
            } 
            
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('bizom_core_homepage');
        } 

        if (!array_search($project[0]->getProjectName(), $parameters['parameters'])) {
            return $this->render('BZMCoreBundle:Core:install.html.twig', array('form' => $form->createView()));
        } else {
            throw $this->createNotFoundException('The product does not exist');
        }
    }
}
