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
     * @Route\Segment("[/:id]", constraints={"id"="\d+"}, name="home")
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * @Route\Segment("[/:id]", constraints={"id"="\d+"})
     * @return ViewModel
     */
    public function editarAction()
    {
        return new ViewModel(array('data' => 'Olá este é novo!'));
    }
}