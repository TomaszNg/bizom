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
use Symfony\Component\Serializer\Encoder\YamlEncoder;
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
    public function installAction(Request $request, EntityManagerInterface $em, FormGenerator $formGenerator) { 
        $form = $formGenerator->getInstallForm();
        $project = $em->getRepository(Project::class);
        $yamlEncoder = new YamlEncoder();
        $parametersFile = $this->get('kernel')->getRootDir() . '\config\parameters.yml';
        $parametersYml = $yamlEncoder->decode(file_get_contents($parametersFile), 1);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $data = $form->getData();  // todo: serialize form data in array (find function) + 
            $dataArray = ['parameters'=> array(
                'project_name' => $data->getProjectName(),
                'company_name' => $data->getCompanyName(),
                'company_adress' => $data->getCompanyAdress()
            )];
            
            $parametersYml['parameters'] = array_merge($parametersYml['parameters'], $dataArray['parameters']);
            // Add project data to parameters
            if (!mb_stripos($parametersFile ,$data->getProjectName())) {
                $parameters = $yamlEncoder->encode($parametersYml, 'yaml', ['yaml_inline' => 2, 'yaml_indent' => 0]);
                file_put_contents($parametersFile, $parameters);
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
}
