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

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class ReplaceRouterPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ( ! $container->hasDefinition('cct_legacy_app.router_listener')) {
            return;
        }

        $routerListener = $container->getDefinition('router_listener');

        $definition = $container->getDefinition('cct_legacy_app.router_listener');
        $definition->replaceArgument(1, $routerListener);

        $container->setAlias('router_listener', 'cct_legacy_app.router_listener');
    }

}