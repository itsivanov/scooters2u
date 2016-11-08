<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Service $Service
 */
class AdminServicesController extends AdminAppController
{
    public $uses = ['Service'];
    public $helpers = array('Admin.ExtTree');

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->setHoverFlag('content');
        $this->setActiveMenu(array('content'));
    }

    public function index()
    {
        $list = $this->Service->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            if ($this->Service->save($this->request->data)) {
                $this->setFlash('Service is created', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Service is not created', 'error');
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Service->id = $id;
            if ($this->Service->save($this->request->data)) {
                $this->setFlash('Service is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
        $data = $this->Service->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }

    public function delete($id) {
        if ($this->Service->delete($id)) {
            $this->setFlash('Service is deleted', 'success');
        } else {
            $this->setFlash('Service is not deleted', 'error');
        }
        $this->redirect($this->referer());
    }

}