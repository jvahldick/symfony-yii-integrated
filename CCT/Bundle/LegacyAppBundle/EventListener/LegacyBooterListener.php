<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCT\Bundle\LegacyAppBundle\EventListener;

use CCT\Bundle\LegacyAppBundle\Kernel\LegacyKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class LegacyBooterListener implements EventSubscriberInterface
{

    /**
     * @var LegacyKernelInterface
     */
    private $kernel;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param LegacyKernelInterface $kernel
     * @param ContainerInterface $container
     */
    public function __construct(LegacyKernelInterface $kernel, ContainerInterface $container)
    {
        $this->kernel = $kernel;
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->kernel->isBooted()) {
            $this->kernel->boot($this->container);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', '35')
        );
    }

}