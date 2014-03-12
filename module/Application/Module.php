<?php
namespace Application;

use Application\Entity\Categoria;
use Application\Service\CategoriaService;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\BootstrapListenerInterface,
    Zend\ModuleManager\Feature\FormElementProviderInterface,
    Zend\Config\Factory;

use Zend\Form\Annotation\AnnotationBuilder;

class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface, BootstrapListenerInterface,
    FormElementProviderInterface
{

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        $data = Factory::fromFile(__DIR__ . '/config/module.yml');
        $data['view_manager']['template_path_stack'][__NAMESPACE__] = __DIR__.'/view';
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'categoria.service' => function($sm){
                        return new CategoriaService($sm->get('doctrine.entitymanager.orm_default'));
                    },
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getFormElementConfig()
    {
        return array(
            'factories' => array(
                'categoria.form' => function($sm){
                        $builder = new AnnotationBuilder();
                        $form = $builder->createForm(new Categoria());
                        $form->bind($builder->getEntity());

                        $form->add(array(
                                'name' => 'enviar',
                                'type' => 'Zend\Form\Element\Submit',
                                'attributes' => array(
                                    'value' => 'Enviar',
                                    'class' => 'btn btn-success btn-large',
                                )
                            ));
                        return $form;
                    },
            ),
        );
    }
}
