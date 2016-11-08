<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Contacts Controller
 *
 * @property Contact $Contact
 * @property ClaimRequest $ClaimRequest
 *
 */
class AdminSubscribesController extends AdminAppController {
//    public $uses = array('Email');
//    public $components = array('Admin.DataTables');
//    public $helpers = array('Admin.DataTables');

//    const ACTIVE   = 1;
//    const INACTIVE = 0;
    public $useTable = "Subscribe";

    public function index()
    {
        $this->setActiveMenu(array('feedbacks'));

        $settings  = [
            'limit' => 15,
            'order' => ['id' => 'desc']
        ];

        $this->Paginator->settings = $settings;
        $this->loadModel("Subscribe");

        $list = $this->Paginator->paginate('Subscribe');

        $this->set('list', $list);
    }

    public function delete($id) {
        $this->loadModel("Subscribe");

        if ($this->Subscribe->delete($id)) {
            $this->setFlash('Message is deleted', 'success');
        } else {
            $this->setFlash('Message is not deleted', 'error');
        }
        $this->redirect($this->request->referer());
    }


}
