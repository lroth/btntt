<?php

namespace Btn\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BtnApiBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
