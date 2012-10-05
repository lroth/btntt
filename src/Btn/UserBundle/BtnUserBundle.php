<?php

namespace Btn\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BtnUserBundle extends Bundle
{
    //define bundle as a FOS extension (custom template, controller and forms)
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
