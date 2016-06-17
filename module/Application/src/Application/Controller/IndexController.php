<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $uiConfigs = [];

    public function __construct($configs)
    {
        $this->uiConfigs = $configs;
    }

    public function indexAction()
    {
        $viewModel = new ViewModel([
            'modules' => $this->uiConfigs['modules'],
            'configs' => [
                'oauth' => [
                    'path' => '/oauth',
                    'clientId' => '1000',
                    'clientSecret' => 'testpass',
                ],
            ],
        ]);
        $viewModel->setTemplate('zfegg-admin-ui');
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function loginAction()
    {
        return new JsonModel(['sdf' => 1]);
    }
}
