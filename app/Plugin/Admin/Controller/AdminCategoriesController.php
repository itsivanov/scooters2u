<?php
App::uses('AdminAppController', 'Admin.Controller');
/**
 *@property Category $Category
 */
class AdminCategoriesController extends AdminAppController
{
    public $uses = ['Category'];
    public $helpers = array('Admin.ExtTree');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->setHoverFlag('categories');
        $this->setActiveMenu(array('categories'));
    }

    public function index()
    {
        $list = $this->Category->find('all', array(
            'order' => 'Category.lft'
        ));
        $this->set('list', $list);
    }

    public function add()
    {
        if ($this->request->data) {
            $key = str_replace(array('_', ' '), '-', $this->request->data['Category']['name']);
            $this->request->data['Category']['key'] = preg_replace('/[^a-z0-9-]+/is','',$key);

            if ($this->Category->save($this->request->data)) {
                $this->setFlash('Category is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->data) {
            $this->Category->id = $id;

            $key = str_replace(array('_', ' '), '-', $this->request->data['Category']['name']);
            $this->request->data['Category']['key'] = preg_replace('/[^a-z0-9-]+/is','',$key);

            if ($this->Category->save($this->request->data)) {
                $this->setFlash('Category is saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
        $data = $this->Category->read('', $id);
        $this->request->data = $data;

        $this->set(compact('data'));
        $this->render('add');
    }


    public function delete($id)
    {
        if ($this->Category->delete($id)) {
            $this->setFlash('Category is deleted', 'success');
        } else {
            $this->setFlash('Category is not deleted', 'error');
        }

        $this->redirect($this->referer());
    }

    public function getList()
    {
        //code;
    }
}