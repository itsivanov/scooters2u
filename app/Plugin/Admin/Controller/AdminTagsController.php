<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Pages Controller
 * @property AdminPage $AdminPage
 */
class AdminTagsController extends AdminAppController
{
    public $uses = array('Tag');

    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Tag.id' => 'asc'
        )
    );

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index()
    {
        $this->setActiveMenu(array('blog'));

        $list = $this->Tag->find('all');

        $this->set(array(
            'list'=> $list
        ));
    }

    public function add()
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data) {
            $data = $this->request->data;
            if ($this->Tag->save($data)) {
                $this->setFlash(array('title' => 'Tag', 'msg' => 'Tag is created'), 'success');
                $this->redirect('/admin/tags');

            } else {
                $this->setFlash('Tag is not created', 'error');
            }
        }
    }

    public function edit($id)
    {
        $this->setActiveMenu(array('blog'));


        if ($this->request->data) {
            $this->Tag->id = $id;
            $data = $this->request->data;

            if ($this->Tag->save($data)) {
                $this->setFlash('Tag is saved', 'success');
                $this->redirect('/admin/tags');

            } else {
                $this->setFlash('Tag is not saved', 'error');
            }
        }
        $data = $this->Tag->read(null, $id);
        $this->request->data = $data;
        $this->set(array(
            'title'=> 'Edit Tag '
        ));

        $this->render('add');

    }

    public function activate($id)
    {
        $this->Tag->id = $id;
        $page = $this->Tag->read('active');
        if ($page['Tag']['active'] == self::ACTIVE) {
            $this->Tag->saveField('active', self::INACTIVE);
            $this->setFlash('Tag is blocked', 'success');
        } else {
            $this->Tag->saveField('active', self::ACTIVE);
            $this->setFlash('Tag is active', 'success');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function delete($id)
    {
        if ($this->Tag->delete($id)) {
            $this->setFlash('Tag is deleted', 'success');
        } else {
            $this->setFlash('Tag is not deleted', 'error');
        }
        $this->redirect(array('action' => 'index'));
    }
}
