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

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\EventListener\RouterListener as SymfonyRouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use CCT\Bundle\LegacyAppBundle\Kernel\LegacyKernelInterface;

/**
 * RouterListener delegates the request handling to the Symfony router listener.
 * If the later does not match any controller, then this listener catches the NotFoundHttpException
 * and tells the wrapper to handle the request instead.
 *
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class RouterListener implements EventSubscriberInterface
{

    /**
     * @var LegacyKernelInterface
     */
    protected $legacyKernel;

    /**
     * @var RouterListener
     */
    protected $routerListener;

    /**
     * @var \Symfony\Component\HttpKernel\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param LegacyKernelInterface $legacyKernel
     * @param SymfonyRouterListener $routerListener
     * @param LoggerInterface       $logger
     */
    public function __construct(LegacyKernelInterface $legacyKernel, SymfonyRouterListener $routerListener, LoggerInterface $logger = null)
    {
        $this->legacyKernel = $legacyKernel;
        $this->routerListener = $routerListener;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return GetResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        try {
            $this->routerListener->onKernelRequest($event);
        } catch (NotFoundHttpException $e) {
            if (null !== $this->logger) {
                $this->logger->info('Request handled by the '.$this->legacyKernel->getName().' kernel.');
            }

            $response = $this->legacyKernel->handle($event->getRequest(), $event->getRequestType(), true);

            if ($response->getStatusCode() !== 404) {
                $event->setResponse($response);

                return $event;
            }
        }
    }

    /**
     * @param FinishRequestEvent $event
     */
    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        $this->routerListener->onKernelFinishRequest($event);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                [ 'onKernelRequest', 31 ]
            ]
        ];
    }

}