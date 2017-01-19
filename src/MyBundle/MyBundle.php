<?php

namespace MyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
