<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class MyListener
{

    protected $container;

    protected $yiiApp;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // Always initialize the session by YII (Reading and writing on $_SESSION)
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

        $rootDir = realpath($this->container->getParameter('kernel.root_dir') . '/../../');
        $yii = $rootDir . '/yii/yii/framework/yii.php';

        require_once($yii);

        require_once $rootDir . '/yii/protected/config/main_config.php';
        $config = cfg_web_dev();

        $this->yiiApp = \Yii::createWebApplication($config);
        \Yii::setPathOfAlias('webroot', $rootDir . '/yii');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        \Yii::app()->session->open();

        if (\Yii::app()->user) {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $user = $em->getRepository('CCTUserBundle:User')->find(\Yii::app()->user->id);

            // Authenticating user
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        /*
        $response  = $event->getResponse();
        $request   = $event->getRequest();
        $kernel    = $event->getKernel();
        */
    }

    public function onKernelError(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof NotFoundHttpException) {
            $data = $this->yiiApp->run();

            $event->setResponse(new Response($data));
        }
    }

}