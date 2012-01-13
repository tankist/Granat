<?php

require_once 'application/ControllerTestCase.php';

class ModelsControllerTest extends ControllerTestCase
{

    protected $_model_id = 1;

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'models', 'module' => 'default');
        $url = $this->url($this->urlizeOptions($params));
        $this->dispatch($url);

        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
        /*$this->assertQueryContentContains(
                      'div#view-content p',
                      'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
                      );*/
    }

    public function testViewAction()
    {
        $params = array('action' => 'view', 'controller' => 'Models', 'module' => 'default');
        $params['model_id'] = $this->_model_id;
        $url = $this->url($this->urlizeOptions($params));
        $this->dispatch($url);

        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
        /*$this->assertQueryContentContains(
                      'div#view-content p',
                      'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
                      );*/
    }


}





