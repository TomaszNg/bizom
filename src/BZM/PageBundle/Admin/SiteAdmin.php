<?php

/**
 * SiteAdmin class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\PageBundle\Admin;

use Sonata\PageBundle\Admin\SiteAdmin as SonataSiteAdmin;

class SiteAdmin extends SonataSiteAdmin
{
    protected $baseRoutePattern = 'site';
}