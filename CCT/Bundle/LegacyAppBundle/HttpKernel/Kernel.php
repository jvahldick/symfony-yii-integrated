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

use Composer\Autoload\ClassLoader;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
abstract class Kernel extends BaseKernel
{

    /**
     * @var ClassLoader
     */
    protected $loader;

    /**
     * Overrides the parent method to add the loader to the container.
     *
     * {@inheritdoc}
     */
    protected function initializeContainer()
    {
        parent::initializeContainer();

        $this->container->set('composer.loader', $this->loader);
    }

    /**
     * @param ClassLoader $loader
     */
    public function setLoader(ClassLoader $loader)
    {
        $this->loader = $loader;
    }

}