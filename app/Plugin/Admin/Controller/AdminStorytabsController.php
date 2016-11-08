<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Storytab $Storytab
 */
class AdminStorytabsController extends AdminAppController
{
    public $uses = ['Storytab'];
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
        $list = $this->Storytab->find('all');
        $this->set('list', $list);
    }

    public function add()
    {
        return $this->edit(null);
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Storytab->id = $id;
            if ($this->Storytab->save($this->request->data)) {
                $this->setFlash('Story tab is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Story tab is not saved', 'error');
            }
        }
        $data = $this->Storytab->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }

    public function delete($id)
    {
        if ($this->Storytab->delete($id)) {
            $this->setFlash('Story tab is deleted', 'success');
        } else {
            $this->setFlash('Story tab is not deleted', 'error');
        }

        $this->redirect($this->referer());
    }

}