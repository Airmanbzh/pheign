<?php
namespace pheign\kernel;

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use pheign\aspect\PheignAspect;
use pheign\aspect\PheignSubAspect;

/**
 * Application Aspect Kernel
 */
class PheignKernel extends AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new PheignAspect());
    }
}