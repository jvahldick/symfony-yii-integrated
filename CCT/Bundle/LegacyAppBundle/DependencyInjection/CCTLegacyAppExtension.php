<?php

namespace CCT\Bundle\LegacyAppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class CCTLegacyAppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->register('composer.loader', 'Composer\Autoload\ClassLoader')->setSynthetic(true);

        $container->setParameter('cct_legacy_app.root_dir', $config['root_dir']);
        $container->setParameter('cct_legacy_app.legacy_kernel.id', $config['kernel']['id']);
        $container->setParameter('cct_legacy_app.legacy_kernel.options', isset($config['kernel']['options']) ? $config['kernel']['options'] : array());
        $container->setParameter('cct_legacy_app.autoload.class_loader.id', isset($config['class_loader_id']) ? $config['class_loader_id'] : null);
    }
}
