<?php

/*
 * This file is part of the CCT Marketing package.
 *
 * (c) CCT Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Legacy;

use CCT\Bundle\LegacyAppBundle\Event\LegacyKernelBootEvent;
use CCT\Bundle\LegacyAppBundle\Kernel\LegacyKernel;
use CCT\Bundle\LegacyAppBundle\LegacyKernelEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @author Jorge Vahldick <jv@cct-marketing.com>
 */
class YiiLegacyKernel extends LegacyKernel
{

    /**
     * @var ContainerInterface
     */
    private $container;

    private $yiiApp;

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
//        if ($session->isStarted()) {
//            $session->save();
//        }

        if (\Yii::app()->user->getUserObject()) {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $user = $em->getRepository('CCTUserBundle:User')->find(\Yii::app()->user->id);

            // Authenticating user
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));
        }

        $response = new Response();
//        ob_start();
//        $dataResponse = $this->yiiApp->run();
//        ob_end_clean();

        // Restore the Symfony2 error handler
        // restore_error_handler();

        // Restart the Symfony 2 session
        // $session->migrate();

        if (404 !== $response->getStatusCode()) {
            $response->setContent($this->yiiApp->run());
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(ContainerInterface $container)
    {
        $this->container = $container;

        if (empty($this->options)) {
            throw new \RuntimeException('You must provide options for the CodeIgniter kernel.');
        }

        if ($this->isBooted()) {
            return;
        }

        if ($this->classLoader && !$this->classLoader->isAutoloaded()) {
            $this->classLoader->autoload();
        }

        if ($container->has('request_stack')) {
            $request = $container->get('request_stack')->getCurrentRequest();
        } else {
            $request = $container->get('request');
        }

        $dispatcher = $container->get('event_dispatcher');
        $event = new LegacyKernelBootEvent($request, $this->options);
        $dispatcher->dispatch(LegacyKernelEvents::BOOT, $event);
//        $this->options = $event->getOptions();


        // Always initialize the session by YII (Reading and writing on $_SESSION)
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

        $rootDir = realpath($this->container->getParameter('kernel.root_dir') . '/../../');
        $yii = $rootDir . '/yii/yii/framework/yii.php';

        require_once($yii);

        require_once $rootDir . '/yii/protected/config/main_config.php';
        $config = cfg_web_dev();

        $this->yiiApp = \Yii::createWebApplication($config);
        $this->yiiApp->session->init();

        $session = $container->get('session');
        if ($session->isStarted()) {
            $session->save();
        }

        // Replacing the session
//        $this->migrateSession();
//        $sessionData = \Yii::app()->session->toArray();
//        foreach ($sessionData as $k => $v) {
//            if (false !== strpos($k, '_sf2') || false !== strpos($k, '_security_main')) {
//                continue;
//            }
//
//            $session->set($k, $v);
//        }

        \Yii::setPathOfAlias('webroot', $rootDir . '/yii');

        $this->isBooted = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'yii';
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

}