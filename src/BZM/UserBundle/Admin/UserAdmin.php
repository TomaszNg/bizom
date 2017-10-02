<?php

/**
 * UserAdmin class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\UserBundle\Admin;

use Sonata\UserBundle\Admin\Entity\UserAdmin as SonataUserAdmin;

class UserAdmin extends SonataUserAdmin
{
    protected $baseRoutePattern = 'user';
}