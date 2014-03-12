<?php
namespace App;

use Zend\Config\Factory,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 * @package App
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        $data = Factory::fromFile(__DIR__ . '/config/module.yml');

        $data['translator']['translation_file_patterns'][0]['base_dir']= getcwd().'/module/'.__NAMESPACE__.'/language';
        $data['view_manager']['template_path_stack'][__NAMESPACE__] = __DIR__.'/view';
        $data['view_manager']['template_map']['layout/layout'] = getcwd().'/module/'.__NAMESPACE__.'/view/layout/layout.twig';
        $data['view_manager']['template_map']['error/404'] = getcwd().'/module/'.__NAMESPACE__.'/view/error/404.phtml';
        $data['view_manager']['template_map']['error/index'] = getcwd().'/module/'.__NAMESPACE__.'/view/error/index.phtml';

        return $data;
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
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
