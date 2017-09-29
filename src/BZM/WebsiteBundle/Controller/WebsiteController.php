<?php

namespace BZM\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class WebsiteController extends Controller
{
    /**
     * @Route("/website", name="bzm_website_home")
     */
    public function homeAction()
    {
        if (!$this->container->hasParameter('project_name')) {
            return $this->redirectToRoute('bzm_core_cover');
        } else {
            return $this->render('BZMWebsiteBundle:Website:home.html.twig');
        }
    }
}
