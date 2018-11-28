<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCT\Bundle\LegacyAppBundle\Kernel;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use CCT\Bundle\LegacyAppBundle\Autoload\LegacyClassLoaderInterface;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
interface LegacyKernelInterface extends HttpKernelInterface
{

    /**
     * Boot the legacy kernel.
     *
     * @throws \RuntimeException
     * @param  ContainerInterface $container
     * @return mixed
     */
    public function boot(ContainerInterface $container);

    /**
     * Check whether the legacy kernel is already booted or not.
     *
     * @return boolean
     */
    public function isBooted();

    /**
     * Return the directory where the legacy app lives.
     *
     * @return string
     */
    public function getRootDir();

    /**
     * Set the directory where the legacy app lives.
     *
     * @param string $rootDir
     */
    public function setRootDir($rootDir);

    /**
     * Set the class loader to use to load the legacy project.
     *
     * @param LegacyClassLoaderInterface $classLoader
     */
    public function setClassLoader(LegacyClassLoaderInterface $classLoader);

    /**
     * Return the name of the kernel.
     *
     * @return string
     */
    public function getName();

    /**
     * Set kernel options
     *
     * @param array $options
     */
    public function setOptions(array $options = array());

}