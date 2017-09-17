<?php

/**
 * UserBundle class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BZMUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
