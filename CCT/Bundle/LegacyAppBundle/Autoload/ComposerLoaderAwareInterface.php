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

use Composer\Autoload\ClassLoader;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
interface ComposerLoaderAwareInterface
{

    /**
     * @param ClassLoader $loader
     * @return mixed
     */
    public function setLoader(ClassLoader $loader);

}