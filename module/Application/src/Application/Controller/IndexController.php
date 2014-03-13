<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use SpiffyRoutes\Annotation as Route;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * @Route\Literal("/", name="home")
     * @Route\Segment("/[page/:page]", constraints={"page"="\d+"}, name="paginacao")
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * @Route\Segment("/editar[/:id]", constraints={"id"="\d+"})
     * @return ViewModel
     */
    public function novoAction()
    {
        return new ViewModel(array('data' => 'Olá este é o editar!'));
    }


    /**
     * @Route\Segment("/novo[/:id]", constraints={"id"="\d+"})
     * @return ViewModel
     */
    public function editarAction()
    {
        return new ViewModel(array('data' => 'Olá este é novo!'));
    }
}