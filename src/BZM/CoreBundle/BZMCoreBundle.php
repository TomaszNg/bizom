<?php

/**
 * BZMCoreBundle class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BZMCoreBundle extends Bundle
{
    public function getParent() {
        return 'TwigBundle';
    }
}
