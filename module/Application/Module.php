<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\Config\Config,
    Zend\Config\Processor\Constant,
    Zend\Config\Factory as ConfigFactory;
use Symfony\Component\Yaml\Parser as YamlParser;

class Module {

    public function init()
    {
      // This first line is just for the shorter yml suffix
      ConfigFactory::registerReader( 'yml', 'yaml' );

      // Adding the parser to the reader
      $decoder = new YamlParser();
      $reader  = ConfigFactory::getReaderPluginManager()->get( 'yaml' );
      $reader->setYamlDecoder(array($decoder, 'parse'));
    }

   // Controle de layout
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

   public function getConfigs()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getConfig()
    {
        $reader  = ConfigFactory::getReaderPluginManager()->get( 'yaml' );

        $data   = $reader->fromFile(__DIR__ . '/config/module.config.yml');

        $config = new Config(array('__DIR__' => __DIR__.'/config'), true);
        $processor = new Constant();
        $processor->process($config);

        $data['translator']['translation_file_patterns'][0]['base_dir']= $config->__DIR__ . '/../language';
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

}
