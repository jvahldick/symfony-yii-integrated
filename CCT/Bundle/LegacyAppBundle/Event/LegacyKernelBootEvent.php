<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCT\Bundle\LegacyAppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class LegacyKernelBootEvent extends Event
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array - legacy kernel options
     */
    protected $options;

    /**
     * @param Request $request
     * @param array $options
     */
    public function __construct(Request $request, array $options = array())
    {
        $this->request = $request;
        $this->options = $options;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

}