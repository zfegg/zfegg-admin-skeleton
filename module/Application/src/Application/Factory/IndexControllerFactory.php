<?php

namespace Application\Factory;
use Application\Controller\IndexController;
use Zend\Mvc\Controller\ControllerManager;


/**
 * Class IndexControllerFactory
 * @package Application\Factory
 * @author Xiemaomao
 * @version $Id$
 */
class IndexControllerFactory
{
    public function __invoke(ControllerManager $cm)
    {
        return new IndexController($cm->getServiceLocator()->get('config')['zfegg-admin']['ui']);
    }
}