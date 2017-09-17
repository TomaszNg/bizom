<?php

namespace BZM\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="bizom_admin_dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('BZMAdminBundle:Admin:dashboard.html.twig');
    }
}
