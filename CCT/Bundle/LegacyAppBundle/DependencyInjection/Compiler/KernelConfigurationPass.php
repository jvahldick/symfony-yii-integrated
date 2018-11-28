<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCT\Bundle\LegacyAppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class KernelConfigurationPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $kernelId = $container->getParameter('cct_legacy_app.legacy_kernel.id');
        $kernelOptions = $container->getParameter('cct_legacy_app.legacy_kernel.options');
        $classLoaderId = $container->getParameter('cct_legacy_app.autoload.class_loader.id');

        $container->setAlias('cct_legacy_app.legacy_kernel', $kernelId);

        if ( ! empty($kernelOptions)) {
            $definition = $container->findDefinition($kernelId);
            $definition->addMethodCall('setOptions', array($kernelOptions));
        }

        if ($classLoaderId) {
            $definition = $container->findDefinition($kernelId);
            $definition->addMethodCall('setClassLoader', array(new Reference($classLoaderId)));
        }
    }

}