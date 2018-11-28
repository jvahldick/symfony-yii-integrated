<?php

namespace CCT\Bundle\LegacyAppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cct_legacy_app');

        $rootNode
            ->children()
                ->scalarNode('root_dir')
                    ->info('The path where the legacy app lives')
                    ->isRequired()
                ->end()
                ->append($this->addKernelNode())
                ->scalarNode('class_loader_id')->end()
            ->end()
        ;

        return $treeBuilder;
    }

    public function addKernelNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('kernel');

        $node
            ->example('cct_legacy_app.legacy_kernel.yii')
            ->beforeNormalization()

            ->ifString()
                ->then(function($v) { return array('id'=> $v); })
            ->end()

            ->children()
                ->scalarNode('id')->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('application')->end()
                        ->scalarNode('environment')->end()
                        ->scalarNode('debug')->end()
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->validate()
                ->ifTrue(function($v) {
                    // Validates Yii 1.* configuration
                    if (strpos($v['id'], 'yii')) {
                        return !isset(
                            $v['options']['application'],
                            $v['options']['environment'],
                            $v['options']['debug']
                        );
                    }
                })
                ->thenInvalid('To use the symfony1 kernel you must set an application, environment and debug options.')
            ->end()
        ;

        return $node;
    }

}
