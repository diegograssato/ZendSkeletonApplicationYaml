<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\ModuleManager\Feature\FormElementProviderInterface,
    Zend\Config\Factory as ConfigFactory,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;
use Symfony\Component\Yaml\Parser as YamlParser;

class Module implements
    FormElementProviderInterface,
    AutoloaderProviderInterface,
    ConfigProviderInterface
{

    public function init()
    {
      // This first line is just for the shorter yml suffix
      ConfigFactory::registerReader( 'yml', 'yaml' );

      // Adding the parser to the reader
      $decoder = new YamlParser();
      $reader  = ConfigFactory::getReaderPluginManager()->get( 'yaml' );
      $reader->setYamlDecoder( [ $decoder, 'parse' ] );
    }
   // Controle de layout
    public function onBootstrap(MvcEvent $e)
    {

        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $e->getApplication()->getEventManager()->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
                $controller      = $e->getTarget();
                $controllerClass = get_class($controller);
                $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                $config          = $e->getApplication()->getServiceManager()->get('config');
                if (isset($config['module_layouts'][$moduleNamespace])) {
                    $controller->layout($config['module_layouts'][$moduleNamespace]);
                 //   echo $config['module_layouts'][$moduleNamespace];
                }
            }, 100);

    }

   public function getConfigs()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getConfig()
    {
        $decoder = new YamlParser();
        $reader  = ConfigFactory::getReaderPluginManager()->get( 'yaml' );

        $data   = $reader->fromFile(__DIR__ . '/config/module.config.yml');

        $config = new \Zend\Config\Config(array('__DIR__' => __DIR__.'/config'), true);
        $processor = new \Zend\Config\Processor\Constant();
        $processor->process($config);

        //$data['translator']['translation_file_patterns']['base_dir']= $config->__DIR__ . '/../language';
        $data['view_manager']['template_path_stack']['Application'] = $config->__DIR__.'/../view';
        $data['view_manager']['template_map']['not_found_template'] = $config->__DIR__ . '/../view/error/404.phtml';
        $data['view_manager']['template_map']['exception_template'] = $config->__DIR__ . '/../view/error/index.phtml';

        return $data;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {

        return array(

            'factories' => array(
                    /**
                     * MongoDB
                     */
                    'manager' => function ($sm) {
                        $manager = $sm->get('Doctrine\ODM\MongoDB\DocumentManager');
                        return $manager;
                    },
                    /*
                     MySQL

                    'manager' => function ($sm) {
                        $manager = $sm->get('Doctrine\ORM\EntityManager');
                        return $manager;
                    },
                    */

                    'Application\Service\User' => function($sm) {
                        return new Service\User(
                            $sm->get('View'));
                    },

            )
        );

    }
     public function getFormElementConfig()
    {
        return array(
            'invokables' => array(
                'phone' => 'Application\Form\Element\Phone'
            )
        );
    }

}
