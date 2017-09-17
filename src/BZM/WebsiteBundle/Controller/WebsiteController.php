<?php

namespace BZM\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class WebsiteController extends Controller
{
    /**
     * @Route("/website", name="bizom_website_home")
     */
    public function homeAction()
    {
        return $this->render('BZMWebsiteBundle:Website:home.html.twig');
    }
}
