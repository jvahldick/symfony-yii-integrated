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

use CCT\Bundle\LegacyAppBundle\Autoload\LegacyClassLoaderInterface;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
abstract class LegacyKernel implements LegacyKernelInterface
{

    /**
     * @var bool
     */
    protected $isBooted = false;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var LegacyClassLoaderInterface
     */
    protected $classLoader;

    /**
     * @var array
     */
    protected $options;

    /**
     * {@inheritdoc}
     */
    public function isBooted()
    {
        return $this->isBooted;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * {@inheritdoc}
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     */
    public function setClassLoader(LegacyClassLoaderInterface $classLoader)
    {
        $classLoader->setKernel($this);
        $this->classLoader = $classLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

}