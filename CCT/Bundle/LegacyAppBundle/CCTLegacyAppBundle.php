<?php

namespace CCT\Bundle\LegacyAppBundle;

use CCT\Bundle\LegacyAppBundle\DependencyInjection\Compiler\KernelConfigurationPass;
use CCT\Bundle\LegacyAppBundle\DependencyInjection\Compiler\LoaderInjectorPass;
use CCT\Bundle\LegacyAppBundle\DependencyInjection\Compiler\ReplaceRouterPass;
use Composer\Autoload\ClassLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CCTLegacyAppBundle extends Bundle
{

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    private $loader;

    /**
     * @param ClassLoader $loader
     */
    public function __construct(ClassLoader $loader = null)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        // The loader can be null when clearing the cache.
        if (null !== $this->loader) {
            $container->addCompilerPass(new LoaderInjectorPass($this->loader));
        }

        $container->addCompilerPass(new KernelConfigurationPass());
        $container->addCompilerPass(new ReplaceRouterPass());
    }

}
