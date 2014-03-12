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
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    public function novoAction()
    {
        return new ViewModel(array('data' => 'Olá este é novo!'));
    }
}