<?php

/**
 * BlockAdmin class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\PageBundle\Admin;

use Sonata\PageBundle\Admin\SharedBlockAdmin as SonataSharedBlockAdmin;

class SharedBlockAdmin extends SonataSharedBlockAdmin
{
    protected $baseRoutePattern = 'block';
}