<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Contacts Controller
 *
 * @property Contact $Contact
 * @property ClaimRequest $ClaimRequest
 *
 */
class AdminContactsController extends AdminAppController {
    public $uses = array('Email');
    public $components = array('Admin.DataTables');
    public $helpers = array('Admin.DataTables');

    const ACTIVE   = 1;
    const INACTIVE = 0;

    public function index()
    {
        $this->setActiveMenu(array('feedbacks'));

        $settings  = [
            'limit' => 15,
            'order' => ['id' => 'desc']
        ];

        $this->Paginator->settings = $settings;

        $list = $this->Paginator->paginate('Contact');
        $this->set('list', $list);
    }

    public function emails() {
        $this->setActiveMenu(array('messages'));

        if ($this->request->data) {
            if ($this->Email->save($this->request->data)) {
                $this->setFlash('Email is saved', 'success');
                $this->redirect(array('action' => 'emails'));
            } else {
                $this->setFlash('Email is not saved', 'error');
            }
        }
        $list = $this->Email->find('all');
        $this->set('list', $list);
    }

    public function assignments()
    {
        $this->setActiveMenu(array('messages'));
//        $assignments = $this->ClaimRequest->find('all', array('order' => 'created DESC'));
//        $this->set('list', $assignments);
    }

    public function assignmentView($id)
    {
//        $this->ClaimRequest->id = $id;
//        $data = $this->ClaimRequest->read();
//        $data['ClaimRequest']['service']  = ClassRegistry::init('Service')->getService($data['ClaimRequest']['main_service_id']);
//        $data['ClaimRequest']['category'] = ClassRegistry::init('Category')->getTitle($data['ClaimRequest']['category_id']);
//
//        if ($data['ClaimRequest']['parent_service_id']) {
//            $data['ClaimRequest']['parentService']  = $data['ClaimRequest']['parent_service_id'] == 1? 'Property claim': 'Casualty claim';
//        }
//
//        if ($data['ClaimRequest']['type_id']) {
//            $data['ClaimRequest']['type'] = $data['ClaimRequest']['type_id'] == 1? 'First Party': 'Third Party';
//        }
//
//        $data['LossInformation']['attachments'] = unserialize($data['LossInformation']['attachments']);
//        $this->request->data = $data;
//        $this->ClaimRequest->saveField('viewed', 1);
    }

    public function assignmentDelete($id) {
//        $this->ClaimRequest->delete($id);
        $this->redirect($this->referer());
    }

    public function view($id) {
        $this->Contact->id = $id;
        $data = $this->Contact->read();
        $this->Contact->saveField('viewed', 1);
        $this->set('contact', $data);
//        $this->request->data = $data;
    }

    public function delete($id) {
        if ($this->Contact->delete($id)) {
            $this->setFlash('Message is deleted', 'success');
        } else {
            $this->setFlash('Message is not deleted', 'error');
        }
        $this->redirect($this->request->referer());
    }

    public function activate($id)
    {
        $this->Contact->id = $id;
        $data = $this->Contact->read();

        $status = ($data['Contact']['viewed'] == self::ACTIVE) ? self::INACTIVE : self::ACTIVE;
        $this->Contact->saveField('viewed', $status);
        $this->setFlash('Changes saved', 'success');

        $this->redirect($this->request->referer());
    }

}
