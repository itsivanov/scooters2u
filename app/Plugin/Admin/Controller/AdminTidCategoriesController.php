<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * Pages Controller
 * @property TidCategory $TidCategory
 */
class AdminTidCategoriesController extends AdminAppController
{
    public $uses = array('TidCategory');

    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'TidCategory.id' => 'asc'
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

        $list = $this->TidCategory->find('all');

        $this->set(array(
            'list'=> $list
        ));
    }

    public function add()
    {
        $this->setActiveMenu(array('blog'));


        if ($this->request->data) {
            $data = $this->request->data;
            if ($this->TidCategory->save($data)) {
                $this->setFlash(array('title' => 'Category', 'msg' => 'Category is created'), 'success');
                $this->redirect('/admin/tidcategory');

            } else {
                $this->setFlash('Category is not created', 'error');
            }
        }
        $this->set(array(
            'title'=> 'Add news category'
        ));
    }

    public function edit($id)
    {
        $this->setActiveMenu(array('blog'));

        if ($this->request->data) {
            $this->TidCategory->id = $id;
            $data = $this->request->data;
            if ($this->TidCategory->save($data)) {
                $this->setFlash('Category is saved', 'success');
                $this->redirect('/admin/tidcategory');

            } else {
                $this->setFlash('Category is not saved', 'error');
            }
        }
        $data = $this->TidCategory->read('', $id);
        $this->request->data = $data;
        $this->set(array(
            'title'=> 'Edit news category'

        ));

        $this->render('add');

    }

    public function activate($id)
    {
        $this->TidCategory->id = $id;
        $page = $this->TidCategory->read('active');
        if ($page['TidCategory']['active'] == self::ACTIVE) {
            $this->TidCategory->saveField('active', self::INACTIVE);
            $this->setFlash('Category is blocked', 'success');
        } else {
            $this->TidCategory->saveField('active', self::ACTIVE);
            $this->setFlash('Category is active', 'success');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function delete($id)
    {
        if ($this->TidCategory->delete($id)) {
            $this->setFlash('Category is deleted', 'success');
        } else {
            $this->setFlash('Category is not deleted', 'error');
        }
        $this->redirect(array('action' => 'index'));
    }
}
