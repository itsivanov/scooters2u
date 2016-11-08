<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Publication $Publication
 */
class AdminPublicationsController extends AdminAppController
{
    public $uses = ['Publication'];
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
        $list = $this->Publication->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            if ($this->Publication->save($this->request->data)) {
                $this->setFlash('Publication is created', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Publication is not created', 'error');
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Publication->id = $id;
            if ($this->Publication->save($this->request->data)) {
                $this->setFlash('Publication is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Publication is not saved', 'error');
            }
        }
        $data = $this->Publication->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }

    public function delete($id) {
        if ($this->Publication->delete($id)) {
            $this->setFlash('Publication is deleted', 'success');
        } else {
            $this->setFlash('Publication is not deleted', 'error');
        }
        $this->redirect($this->referer());
    }

}