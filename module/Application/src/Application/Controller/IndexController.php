<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use SpiffyRoutes\Annotation as Route;
use Application\Document\Usuario as UsuarioODM;
use Zend\Stdlib\Hydrator;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{

    public function __construct()
    {

        $this->entityODM = "Application\Document\Usuario";

    }

    /**
     * @Route\Literal("/", name="home")
     */
    public function indexAction()
    {
        return new ViewModel();
    }

     /**
     * @Route\Segment("/novo[/:id]", constraints={"id"="\d+"})
     */
    public function novoAction()
    {


        return new ViewModel(array('data' => 'Olá este é novo! '));
    }

     /**
     * @Route\Segment("/odm")
     */
    public function odmAction()
    {
        $data = array(
            'nome' => 'Otavio Grassato',
            'email' => 'diego@tux.org',
            'password' => '1234',
        );

        $usuario = new UsuarioODM($data);

        $entityManager =  $this->getServiceLocator()->get('Doctrine\ODM\MongoDB\DocumentManager');
        $entityManager->persist($usuario);
        $entityManager->flush();

        $alterEntity = $entityManager->getReference($this->entityODM, $usuario->getId());
        $alterData = array(
                       'nome' => 'Diego Grassato',
                      );
        (new Hydrator\ClassMethods())->hydrate($alterData, $alterEntity);

       $entityManager->persist($alterEntity);
       $entityManager->flush();

        return new ViewModel(array('data' => 'Cadastro e alteracao via Mongo! '.$usuario->getNome()));
    }
}