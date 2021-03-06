<?php

namespace Cmex\Libraries\System;

use TwigBridge\Extension;
use Twig_Environment;
use Illuminate\Foundation\Application;
use Twig_Function_Function;

class TwigConnector extends Extension
{
    private $registeredFunctions = array(
        "full_username",
        "chunk",
        "asset",
        "path",
        'link_to',
        'link_to_asset',
        'link_to_route',
        'link_to_action'
    );

    public function __construct(Application $app, Twig_Environment $twig)
    {
        parent::__construct($app, $twig);

        $regFunctions = $this->registeredFunctions;
        $twig->registerUndefinedFunctionCallback(
            function ($name) use ($regFunctions) {
                if (in_array($name, $regFunctions)) {
                    return new Twig_Function_Function($name);
                }

                return false;
            }
        );
    }

    public function getName()
    {
        return 'TwigConnector';
    }
}
