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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BZM\CoreBundle\Service\FormGenerator;
use BZM\CoreBundle\Entity\Project;
class MainController extends Controller
{
    /**
     * Redirects to correct home page
     *
     * @Route("/", name="bizom_core_homepage")
     * @Method("GET")
     */
    public function homeAction(EntityManagerInterface $em) {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('fos_user_registration_register');
        } else {
            $project = $em->getRepository(Project::class);
            
            if (!$this->container->hasParameter('project_name') | $project->findAll() == null) {
                return $this->render('BZMCoreBundle:Core:cover.html.twig', array('page' => 'cover'));
            } else {
                return $this->redirectToRoute('bizom_website_home');
            }
        }
    }

    /**
     * Installs project parameters
     *
     * @Route("/install", name="bizom_core_install")
     * @Method({"GET", "POST"})
     */
    public function installAction(Request $request, FormGenerator $formGenerator, EntityManagerInterface $em) { 
        $form = $formGenerator->getInstallForm();
        $project = $em->getRepository(Project::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $data = $form->getData();  

            // Add project data to parameters
            if (!$this->container->hasParameter('project_name')) {
                $this->setParameters($data);
            }
            
            // Persist project data and go to registration, if error go to login
            if ($project->findAll() == null) {
                $em->persist($data);
                $em->flush();
            } 
            
            return $this->redirectToRoute('bizom_website_home');
        }

        return $this->render('BZMCoreBundle:Core:install.html.twig', array('form' => $form->createView()));
    }

    // Appends new parameters in parameters file
    public function setParameters($data) {
        $parametersFile = $this->get('kernel')->getRootDir() . '\config\parameters.yml';
        $projectParameters = "\n\n    # Project parameters\n";
        $projectParameters .= "\n    project_name: " . $data->getProjectName();
        $projectParameters .= "\n    company_name: " . $data->getCompanyName();
        $projectParameters .= "\n    company_adress: " . $data->getCompanyAdress();
        
        file_put_contents($parametersFile, $projectParameters, FILE_APPEND | LOCK_EX);
    }
}
