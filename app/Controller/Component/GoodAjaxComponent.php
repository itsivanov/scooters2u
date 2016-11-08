<?php
App::uses('Component', 'Controller');

/**
 * Author Mike S. <misilakov@gmail.com>
 *
 */
class GoodAjaxComponent extends Component
{
    private $controller = null;

    public $ajaxResponse = array(
        'error' => false,
        'errorDesc' => '',
        'message' => ''
    );

    public function initialize(Controller $controller, $settings = array())
    {
        $this->controller =& $controller;
    }

    public function sendAjax()
    {
        $this->autoRender = false;
        if (isset($this->ajaxResponse['content'])) {
            try {
                $response = $this->controller->render($this->ajaxResponse['content'], 'ajax');
                $this->ajaxResponse['content'] = $response->body();
            } catch (Exception $e) {
                if (Configure::read("debug") > 0) {
                    $this->ajaxResponse['errorDesc'] = $e->getMessage();
                } else {
                    $this->ajaxResponse['errorDesc'] = 'No template!';
                }
            }
        }
        if (!empty($this->ajaxResponse['errorDesc'])) $this->ajaxResponse['error'] = true;
        Configure::write("debug", 0);
        exit(json_encode($this->ajaxResponse));

    }


}
