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
    protected $em;

    public function __construct()
    {

        $this->entityODM = "Application\Document\Usuario";

    }

    /**
     * @Route\Literal("/", name="home")
     * @Route\Segment("/[page/:page]", constraints={"page"="\d+"}, name="paginacao")
     */
    public function indexAction()
    {
        return new ViewModel();
    }


    /**
     * @Route\Segment("/categoria", name="categoria")
     * @return ViewModel
     */
    public function categoriaAction()
    {
        /**
         * @var $form \Zend\Form\Form
         */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('categoria.form');

        if($this->getRequest()->isPost()){
            $form->setData($this->getRequest()->getPost()->toArray());

            if($form->isValid()){

                /**
                 * @var $service \Application\Service\CategoriaService
                 */
                $service = $this->getServiceLocator()->get('categoria.service');

                $service->save($form->getData());

                $this->flashMessenger()->addSuccessMessage('Cadatrado com sucesso!');
                return $this->redirect()->toRoute('categoria', array('controller' => 'categoria'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    /**
     * @Route\Segment("/categoria-editar[/:id]", constraints={"id"="\d+"}, name="categoria-edite")
     * @return ViewModel
     */
    public function categoriaEditarAction()
    {
        $param = $this->params()->fromRoute('id', 0);
        /**
         * @var $form \Zend\Form\Form
         */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('categoria.form');

        $repository = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        /**
         * @var $entity \Application\Entity\Categoria
         */
        $entity = $repository->getRepository('Application\Entity\Categoria')->find($param);

        $form->setData($entity->getArrayCopy());

        if ($this->getRequest()->isPost()) {


            $form->setData($this->getRequest()->getPost()->toArray());

            if ( $form->isValid() ) {

                /**
                 * @var $service \Application\Service\CategoriaService
                 */
                $service = $this->getServiceLocator()->get('categoria.service');

                /**
                 * @var $categoria \Application\Entity\Categoria
                 */
                $array = $form->getData()->getArrayCopy();
                $array['id'] = $entity->getId();
                $array['data_cadastro'] = $entity->getDataCadastro();

                $hydrator = $form->getHydrator();
                $categoria = $hydrator->hydrate($array, $entity);

                $service->update($categoria);

                $this->flashMessenger()->addSuccessMessage('Atualizado com sucesso!');
                return $this->redirect()->toRoute('categoria-edite', array('controller' => 'categoria', 'action' => 'categoria-editar', 'id' => $param));
            }
        }


        return new ViewModel(array('form' => $form, 'id' => $param));
    }

    /**
     * @Route\Segment("/categoria-remover[/:id]", constraints={"id"="\d+"}, name="categoria-remover")
     * @return \Zend\Http\Response
     */
    public function categoriaRemoveAction()
    {
        $param = $this->params()->fromRoute('id', 0);

        $repository = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        /**
         * @var $entity \Application\Entity\Categoria
         */
        $entity = $repository->getRepository('Application\Entity\Categoria')->find($param);

        /**
         * @var $service \Application\Service\CategoriaService
         */
        $service = $this->getServiceLocator()->get('categoria.service');

        $service->remove($entity);

        return $this->redirect()->toRoute('categoria', array('controller' => 'categoria', 'action' => 'categoria'));
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
        $hydrator = new Hydrator\ClassMethods();
        $hydrator->hydrate($alterData, $alterEntity);

       $entityManager->persist($alterEntity);
       $entityManager->flush();

        return new ViewModel(array('data' => 'Cadastro e alteracao via Mongo! '.$usuario->getNome()));
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        if( null === $this->em )
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;

    }
}