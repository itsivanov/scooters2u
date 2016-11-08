<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Company $Company
 */
class AdminCompaniesController extends AdminAppController
{
    public $uses = ['Company'];
    public $helpers = array('Admin.ExtTree');

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->setHoverFlag('companies');
        $this->setActiveMenu(array('companies'));
    }

    public function index()
    {
        $list = $this->Company->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            if ($this->Company->save($this->request->data)) {
                $this->setFlash('Company is created', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Company is not created', 'error');
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Company->id = $id;
            if ($this->Company->save($this->request->data)) {
                $this->setFlash('Company is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
        $data = $this->Company->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }

    public function delete($id)
    {
        if ($this->Company->delete($id)) {
            $this->setFlash('Company is deleted', 'success');
        } else {
            $this->setFlash('Company is not deleted', 'error');
        }
        $this->redirect($this->referer());
    }

}