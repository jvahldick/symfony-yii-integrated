<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCT\Bundle\LegacyAppBundle;

use CCT\Bundle\LegacyAppBundle\Kernel\LegacyKernelInterface;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
interface LegacyClassLoaderInterface
{

    /**
     * Autoload the legacy code.
     *
     * @return void
     */
    public function autoload();

    /**
     * Check whether the legacy is already autoloaded.
     *
     * @return bool
     */
    public function isAutoLoaded();

    /**
     * Inject the kernel into the class looader.
     *
     * @param LegacyKernelInterface $kernel
     */
    public function setKernel(LegacyKernelInterface $kernel);

}