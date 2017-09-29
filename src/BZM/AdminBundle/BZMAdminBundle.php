<?php

namespace BZM\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BZMAdminBundle extends Bundle
{
    public function getParent() {
        return 'SonataAdminBundle';
    }
}
